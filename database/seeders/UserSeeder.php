<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ==========================
        // Admin
        // ==========================
        $admin = User::firstOrCreate(
            ['email' => 'admin@cutisglow.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password123'),
                'no_hp' => '081234567890',
                'status_aktif' => 'aktif',
            ]
        );

        $admin->assignRole('admin');

        // ==========================
        // Doctor
        // ==========================
        $doctor = User::firstOrCreate(
            ['email' => 'doctor@cutisglow.com'],
            [
                'name' => 'Doctor',
                'password' => Hash::make('password123'),
                'no_hp' => '081234567891',
                'status_aktif' => 'aktif',
            ]
        );

        $doctor->assignRole('doctor');

        // ==========================
        // Patient
        // ==========================
        $patient = User::firstOrCreate(
            ['email' => 'patient@cutisglow.com'],
            [
                'name' => 'Patient',
                'password' => Hash::make('password123'),
                'no_hp' => '081234567892',
                'status_aktif' => 'aktif',
            ]
        );

        $patient->assignRole('patient');
    }
}
