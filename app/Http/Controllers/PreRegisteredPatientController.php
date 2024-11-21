<?php

namespace App\Http\Controllers;

use App\Models\PreRegisteredPatient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;

class PreRegisteredPatientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return view('pre-reg.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('pre-reg.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        // dd(request());

        $validatedData = $request->validate([
            // Personal Information
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'], // Allow middle name to be optional
            'last_name' => ['required', 'string', 'max:255'],
            'birthdate' => ['required', 'date_format:m-d-Y', function ($attribute, $value, $fail) {
                // Convert to Carbon instance
                $date = Carbon::createFromFormat('m-d-Y', $value);

                // Check if the date is before today
                if ($date->isAfter(Carbon::today())) {
                    $fail('The birthdate must be before today.');
                }
            }], // Ensure birthdate is a valid date in the past
            'sex' => ['required', Rule::in(['Male', 'Female'])],
            'religion' => ['required', 'string', 'max:255'], // Optional but validated if present
            'civil_status' => ['required', Rule::in(['Single', 'Married', 'Divorced'])],
            'citizenship' => ['required', 'string', 'max:255'],
            'healthcard_number' => ['nullable', 'string', 'max:50'], // Assuming healthcard_number is alphanumeric

            // Contact Information
            'address' => ['required', 'string', 'max:500'],
            'email' => ['required', 'email', 'max:255', function ($attribute, $value, $fail) {
                if (User::where('email', $value)->exists() || PreRegisteredPatient::where('email', $value)->exists()) {
                    $fail('This email is already taken.');
                }
            }], // Validate email format
            'contact_number' => ['required', 'regex:/^09[0-9]{7,13}$/', "min:11"], // Validate phone number format (e.g., +123456789)

            // Emergency Contacts
            'emergency_contact1_name' => ['required', 'string', 'max:255'],
            'emergency_contact1_number' => ['required', 'regex:/^09[0-9]{7,13}$/', "min:11"], // Validate phone number
            'emergency_contact1_relationship' => ['required', 'string', 'max:100'],
            'emergency_contact2_name' => ['required', 'string', 'max:255'], // Second contact is optional
            'emergency_contact2_number' => ['required', 'regex:/^09[0-9]{7,13}$/', "min:11"],
            'emergency_contact2_relationship' => ['required', 'string', 'max:100'],

            // Terms
            'terms_and_conditions' => ['accepted', 'required',], // 'accepted' ensures it's checked
            'privacy_policy' => ['accepted', 'required'], // 'accepted' ensures it's checked
        ], [
            'required' => 'This field is required', // Overrides all required fields
            'accepted' => 'This field is required', // Overrides all accepted fields

            'contact_number.regex' => 'Invalid contact number',
            'contact_number.min' => 'Invalid contact number',
            'emergency_contact1_number.regex' => 'Invalid contact number',
            'emergency_contact1_number.min' => 'Invalid contact number',
            'emergency_contact2_number.regex' => 'Invalid contact number',
            'emergency_contact2_number.min' => 'Invalid contact number',
        ]);

        dd($validatedData);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}