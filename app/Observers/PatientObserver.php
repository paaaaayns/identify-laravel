<?php

namespace App\Observers;

use App\Models\IrisBiometrics;
use App\Models\Patient;
use App\Models\PreRegisteredPatient;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
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
        Log::info('PatientObserver@creating: Creating patient...', [
            'ulid' => $patient->ulid,
        ]);


        $leftIrisImage = public_path("storage/patients/{$patient->ulid}/biometrics/left_iris.png");
        if (!file_exists($leftIrisImage)) {
            Log::error('PatientObserver@created: Left iris image does not exist.', [
                'image_path' => $leftIrisImage,
                'patient_id' => $patient->id,
                'email' => $patient->email,
            ]);
        }

        try {
            // make a post request to http://127.0.0.1:8000/store

            $response = Http::asMultipart()
                ->attach('file', file_get_contents($leftIrisImage), 'left_iris.png')
                ->post('http://127.0.0.1:8000/upload', []);

            // Convert JSON response to an array
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

            $irisBiometrics = new IrisBiometrics();
            $irisBiometrics->ulid = Str::ulid();
            $irisBiometrics->patient_id = $patient->ulid;
            $irisBiometrics->orientation = "left";
            $irisBiometrics->iris_code = $responseData["data"]["iris_code"];
            $irisBiometrics->iris_mask_code = $responseData["data"]["iris_mask_code"];
            $irisBiometrics->save();


        } catch (\Exception $e) {
            Log::error('PatientObserver@created: Error storing iris biometrics: ' . $e->getMessage());

            // cancel creating the patient
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
            // Generate a unique user_id based on the patient's ID
            $patient->user_id = $generatedId;
            // $patient->ulid = Str::ulid();
            $patient->saveQuietly(); // Save without triggering model events

            // Delete from PreregisteredPatient table
            $pre_reg = PreRegisteredPatient::where('email', $patient->email)->first();
            if ($pre_reg) {
                $pre_reg->delete();
            }

            // Create the associated user
            $user = User::create([
                'user_id' => $patient->user_id,  // Use the custom user_id
                'email' => $patient->email, // patient's email
                'password' => Hash::make('patient'), // Default password
                'role' => 'patient',  // Define user type
            ]);

            $user->assignRole('patient');

            Log::info('PatientObserver@created: Patient created successfully.', [
                'patient_id' => $patient->user_id,
            ]);

            // Send email verification notification
            // event(new Registered($user));


            $rightIrisImage = Storage::url("patients/{$patient->user_id}/biometrics/right_iris.png");
        } catch (\Exception $e) {
            // Log any issues during user creation
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
