<?php

namespace App\Observers;

use App\Models\IrisBiometrics;
use App\Models\Patient;
use App\Models\PreRegisteredPatient;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PatientObserver
{
    /**
     * Handle the Patient "creating" event.
     *
     * @param  \App\Models\Patient  $patient
     * @return void
     */
    public function creating(Patient $patient)
    {
        $leftIrisImage = public_path("storage/patients/{$patient->ulid}/biometrics/left_iris.png");
        if (!file_exists($leftIrisImage)) {
            Log::error('PatientObserver@created: Left iris image does not exist.', [
                'image_path' => $leftIrisImage,
                'patient_id' => $patient->id,
                'email' => $patient->email,
            ]);
        }

        $rightIrisImage = public_path("storage/patients/{$patient->ulid}/biometrics/right_iris.png");
        if (!file_exists($rightIrisImage)) {
            Log::error('PatientObserver@created: Right iris image does not exist.', [
                'image_path' => $rightIrisImage,
                'patient_id' => $patient->id,
                'email' => $patient->email,
            ]);
        }

        try {
            $response = Http::asMultipart()
                ->attach('left_iris', file_get_contents($leftIrisImage), 'left_iris.png')
                ->attach('right_iris', file_get_contents($rightIrisImage), 'right_iris.png')
                ->post('http://127.0.0.1:8000/fast-api/store', []);

            $responseData = $response->json();

            if ($responseData['success'] === true) {
                Log::info('PatientObserver@creating: Iris biometrics processed successfully.', [
                    'patient_id' => $patient->ulid,
                    'email' => $patient->email,
                    'response' => $responseData,
                ]);
            } else {
                throw new \Exception("API Error: " . ($responseData['message'] ?? 'Unknown error'));
            }

            $leftIrisBiometrics = new IrisBiometrics();
            $leftIrisBiometrics->ulid = Str::ulid();
            $leftIrisBiometrics->patient_ulid = $patient->ulid;
            $leftIrisBiometrics->orientation = "left";
            $leftIrisBiometrics->iris_code = gzdecode(base64_decode($responseData["data"]["left_iris_code"]));
            $leftIrisBiometrics->mask_code = gzdecode(base64_decode($responseData["data"]["left_mask_code"]));

            $rightIrisBiometrics = new IrisBiometrics();
            $rightIrisBiometrics->ulid = Str::ulid();
            $rightIrisBiometrics->patient_ulid = $patient->ulid;
            $rightIrisBiometrics->orientation = "right";
            $rightIrisBiometrics->iris_code = gzdecode(base64_decode($responseData["data"]["right_iris_code"]));
            $rightIrisBiometrics->mask_code = gzdecode(base64_decode($responseData["data"]["right_mask_code"]));

            $leftIrisBiometrics->save();
            $rightIrisBiometrics->save();
        } catch (\Exception $e) {
            Log::error('PatientObserver@creating: Error storing iris biometrics: ' . $e->getMessage());

            return false;
        }
    }

    /**
     * Handle the Patient "created" event.
     *
     * @param  \App\Models\Patient  $patient
     * @return void
     */
    public function created(Patient $patient)
    {
        try {
            $generatedId = 'P-' . str_pad($patient->id, 5, '0', STR_PAD_LEFT);
            $patient->user_id = $generatedId;
            $patient->saveQuietly();

            $pre_reg = PreRegisteredPatient::where('email', $patient->email)->first();
            if ($pre_reg) {
                $pre_reg->delete();
            }

            $user = User::create([
                'user_id' => $patient->user_id, 
                'email' => $patient->email,
                'password' => Hash::make('patient'),
                'role' => 'patient',
            ]);

            $user->assignRole('patient');

            Log::info('PatientObserver@created: Patient created successfully.', [
                'patient_id' => $patient->user_id,
            ]);

            // Send email verification notification
            // event(new Registered($user));
        } catch (\Exception $e) {
            Log::error('PatientObserver@created: Error creating User for patient: ' . $e->getMessage(), [
                'patient_ulid' => $patient->ulid,
                'email' => $patient->email,
            ]);

            // Delete the patient record to maintain data consistency
            $patient->delete();

            // Check if $user exists before deleting it
            if (isset($user)) {
                $user->delete();
            }
        }
    }
}
