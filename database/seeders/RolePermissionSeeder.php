<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Buat Role Spatie di Database
        $adminRole  = Role::updateOrCreate(['name' => 'admin']);
        $dokterRole = Role::updateOrCreate(['name' => 'dokter']);
        $pasienRole = Role::updateOrCreate(['name' => 'pasien']);

        // 2. Akun Admin
        $admin = User::firstOrCreate([
            'name' => 'Admin Cutis Glow',
            'email' => 'admin@cutisglow.com',
            'password' => Hash::make('password'),
            'no_hp' => '081234567890',
            'status_aktif' => true,
        ]);
        $admin->assignRole($adminRole);

        // 3. Akun Dokter
        $dokter = User::firstOrCreate([
            'name' => 'Dr. Cutis Glow',
            'email' => 'dokter@cutisglow.com',
            'password' => Hash::make('password'),
            'no_hp' => '085555555555',
            'status_aktif' => true,
        ]);
        $dokter->assignRole($dokterRole);

        // 4. Akun Pasien
        $pasien = User::firstOrCreate([
            'name' => 'Pasien Dami',
            'email' => 'pasien@cutisglow.com',
            'password' => Hash::make('password'),
            'no_hp' => '089876543210',
            'status_aktif' => true,
        ]);
        $pasien->assignRole($pasienRole);
    }
}
