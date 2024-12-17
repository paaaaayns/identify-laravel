<?php

namespace App\Models;

use App\Models\PatientQueue;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class Patient extends Model
{
    /** @use HasFactory<\Database\Factories\PatientFactory> */
    use HasFactory;
    protected $guarded = [];

    // Hook into the creating and created model events
    protected static function booted()
    {
        // When the patient is being created
        static::creating(function (Patient $patient) {
            // No user_id yet; ID will be available after creation.
        });

        // After the patient is created
        static::created(function (Patient $patient) {
            try {
                // Generate a unique user_id based on the patient's ID
                $patient->user_id = 'P-' . str_pad($patient->id, 5, '0', STR_PAD_LEFT);
                $patient->ulid = Str::ulid();
                $patient->saveQuietly(); // Save without triggering model events

                // Create the associated user
                $user = User::create([
                    'user_id' => $patient->user_id,  // Use the custom user_id
                    'username' => $patient->user_id, // Use the custom username
                    'password' => Hash::make('patient'), // Default password
                    'email' => $patient->email, // patient's email
                    'type' => 'PATIENT',  // Define user type
                ]);

                // Send email verification notification
                // event(new Registered($user));
            } catch (\Exception $e) {
                // Log any issues during user creation
                Log::error('Error creating User for patient: ' . $e->getMessage(), [
                    'patient_id' => $patient->id,
                    'email' => $patient->email,
                ]);

                // Delete the patient record to maintain data consistency
                $patient->delete();
            }
        });
    }

    public function medicalHistory()
    {
        return $this->hasMany(PatientQueue::class, 'patient_id'); // Adjust based on actual foreign key
    }
}
