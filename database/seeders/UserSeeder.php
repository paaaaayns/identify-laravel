<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        User::create([
            'user_id' => 'U-000001',
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => 'admin',  // Hash the password
            'type' => 'admin',
            'otp_code' => null,
            'is_otp_verified' => null,
        ]);
    }
}
