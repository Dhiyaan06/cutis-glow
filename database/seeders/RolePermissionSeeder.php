<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Dokter;
use App\Models\Pasien;
use App\Models\JadwalDokter;
use App\Models\MasterLayanan;
use App\Models\BookingKonsultasi;
use App\Models\RiwayatLayanan;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

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
        $admin = User::create([
            'name' => 'Admin Cutis Glow',
            'email' => 'admin@cutisglow.com',
            'password' => Hash::make('password'),
            'no_hp' => '081234567890',
            'status_aktif' => 'aktif',
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);
        $admin->assignRole($adminRole);

        // 3. Akun Dokter
        $dokterUser = User::create([
            'name' => 'Dr. Clarissa Pinkan',
            'email' => 'dokter@cutisglow.com',
            'password' => Hash::make('password'),
            'no_hp' => '085555555555',
            'status_aktif' => 'aktif',
            'role' => 'dokter',
            'email_verified_at' => now(),
        ]);
        $dokterUser->assignRole($dokterRole);

        $dokter = Dokter::create([
            'id_pengguna' => $dokterUser->id_pengguna,
            'spesialis' => 'Spesialis Kulit & Kecantikan (Dermatologist)',
            'no_str' => 'STR-987654321099',
            'no_hp' => '085555555555',
            'alamat' => 'Klinik Utama Cutis Glow, Lantai 2 R.201',
            'jadwal_praktek' => 'Senin - Jumat (09:00 - 17:00)'
        ]);

        // Akun Dokter Tambahan untuk Pilihan
        $dokterUser2 = User::create([
            'name' => 'Dr. Bramasta Putera',
            'email' => 'bramasta@cutisglow.com',
            'password' => Hash::make('password'),
            'no_hp' => '081223344556',
            'status_aktif' => 'aktif',
            'role' => 'dokter',
            'email_verified_at' => now(),
        ]);
        $dokterUser2->assignRole($dokterRole);

        $dokter2 = Dokter::create([
            'id_pengguna' => $dokterUser2->id_pengguna,
            'spesialis' => 'Spesialis Bedah Plastik & Rekonstruksi Estetik',
            'no_str' => 'STR-887766554422',
            'no_hp' => '081223344556',
            'alamat' => 'Klinik Utama Cutis Glow, Lantai 2 R.205',
            'jadwal_praktek' => 'Selasa, Kamis, Sabtu (13:00 - 18:00)'
        ]);

        // 4. Akun Pasien
        $pasienUser = User::create([
            'name' => 'Adelia Putri',
            'email' => 'pasien@cutisglow.com',
            'password' => Hash::make('password'),
            'no_hp' => '089876543210',
            'status_aktif' => 'aktif',
            'role' => 'pasien',
            'email_verified_at' => now(),
        ]);
        $pasienUser->assignRole($pasienRole);

        $pasien = Pasien::create([
            'id_pengguna' => $pasienUser->id_pengguna,
            'no_hp' => '089876543210',
            'alamat' => 'Jl. Mawar Indah Blok B4 No. 12, Bandung',
            'tanggal_lahir' => '2000-08-20',
            'jenis_kelamin' => 'P'
        ]);

        $pasienUser2 = User::create([
            'name' => 'Rian Kurnia',
            'email' => 'rian@cutisglow.com',
            'password' => Hash::make('password'),
            'no_hp' => '087811223344',
            'status_aktif' => 'aktif',
            'role' => 'pasien',
            'email_verified_at' => now(),
        ]);
        $pasienUser2->assignRole($pasienRole);

        $pasien2 = Pasien::create([
            'id_pengguna' => $pasienUser2->id_pengguna,
            'no_hp' => '087811223344',
            'alamat' => 'Jl. Sukajadi No. 154, Bandung',
            'tanggal_lahir' => '1995-12-10',
            'jenis_kelamin' => 'L'
        ]);

        // 5. Seed Jadwal Dokter
        // Jadwal Dr. Clarissa (Senin, Rabu, Jumat)
        $hariClarissa = ['Senin', 'Rabu', 'Jumat'];
        foreach ($hariClarissa as $h) {
            JadwalDokter::create([
                'id_dokter' => $dokter->id_dokter,
                'hari' => $h,
                'jam_mulai' => '09:00:00',
                'jam_selesai' => '12:00:00',
                'status' => 'aktif'
            ]);
            JadwalDokter::create([
                'id_dokter' => $dokter->id_dokter,
                'hari' => $h,
                'jam_mulai' => '13:00:00',
                'jam_selesai' => '16:00:00',
                'status' => 'aktif'
            ]);
        }

        // Jadwal Dr. Bramasta (Selasa, Kamis)
        $hariBram = ['Selasa', 'Kamis'];
        foreach ($hariBram as $h) {
            JadwalDokter::create([
                'id_dokter' => $dokter2->id_dokter,
                'hari' => $h,
                'jam_mulai' => '13:00:00',
                'jam_selesai' => '17:00:00',
                'status' => 'aktif'
            ]);
        }

        // 6. Seed Layanan (Skincare Clinic)
        $layananList = [
            [
                'nama_layanan' => 'Brightening Glow Facial',
                'deskripsi' => 'Perawatan wajah untuk mencerahkan, membersihkan komedo, dan mengangkat sel kulit mati agar wajah glowing bercahaya.',
                'harga' => 250000,
                'diskon' => 10,
            ],
            [
                'nama_layanan' => 'Acne Laser Therapy',
                'deskripsi' => 'Terapi sinar laser intensitas khusus untuk mematikan bakteri jerawat, meredakan peradangan, dan memudarkan noda bekas jerawat.',
                'harga' => 600000,
                'diskon' => 0,
            ],
            [
                'nama_layanan' => 'Korean Glass Skin Peeling',
                'deskripsi' => 'Peeling medis menggunakan formula botani premium untuk regenerasi kulit instan, menghasilkan efek kulit halus licin seperti kaca.',
                'harga' => 450000,
                'diskon' => 15,
            ],
            [
                'nama_layanan' => 'Anti-Aging Microdermabrasion',
                'deskripsi' => 'Pengikisan kulit mati dengan kristal mikro medis untuk memudarkan kerutan halus, flek hitam, dan merangsang produksi kolagen.',
                'harga' => 380000,
                'diskon' => 5,
            ],
            [
                'nama_layanan' => 'Silk Peel & Collagen Booster Infusion',
                'deskripsi' => 'Dermal infusion yang memasukkan serum kolagen konsentrat tinggi secara mendalam untuk melembabkan kulit dehidrasi.',
                'harga' => 500000,
                'diskon' => 20,
            ]
        ];

        foreach ($layananList as $lay) {
            MasterLayanan::create($lay);
        }

        // Ambil ID layanan untuk riwayat
        $l1 = MasterLayanan::first();
        $l2 = MasterLayanan::skip(1)->first();

        // 7. Seed Bookings & Riwayat Layanan (untuk Grafik Statistik)
        // Booking kemarin (Selesai)
        $b1 = BookingKonsultasi::create([
            'id_pasien' => $pasien->id_pasien,
            'id_dokter' => $dokter->id_dokter,
            'tanggal_booking' => Carbon::yesterday()->toDateString(),
            'jam_booking' => '09:00:00',
            'status' => 'selesai',
            'catatan' => 'Ingin konsultasi jerawat membandel'
        ]);

        RiwayatLayanan::create([
            'id_booking' => $b1->id_booking,
            'id_pasien' => $pasien->id_pasien,
            'id_dokter' => $dokter->id_dokter,
            'id_layanan' => $l2->id_layanan,
            'tanggal_treatment' => Carbon::yesterday()->toDateString(),
            'status' => 'selesai',
            'catatan' => 'Diberikan krim anti-acne dan dilakukan terapi laser.',
            'harga' => $l2->harga,
            'qty' => 1
        ]);

        // Booking hari ini (Dikonfirmasi)
        BookingKonsultasi::create([
            'id_pasien' => $pasien2->id_pasien,
            'id_dokter' => $dokter->id_dokter,
            'tanggal_booking' => Carbon::today()->toDateString(),
            'jam_booking' => '13:00:00',
            'status' => 'dikonfirmasi',
            'catatan' => 'Ingin mencerahkan kulit kusam'
        ]);

        // Booking besok (Pending)
        BookingKonsultasi::create([
            'id_pasien' => $pasien->id_pasien,
            'id_dokter' => $dokter2->id_dokter,
            'tanggal_booking' => Carbon::tomorrow()->toDateString(),
            'jam_booking' => '14:00:00',
            'status' => 'pending',
            'catatan' => 'Konsultasi bekas luka parut'
        ]);
    }
}
