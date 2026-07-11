# Cutis Glow - Sistem Informasi & Portal Klinik Kecantikan
Aplikasi portal dan manajemen klinik kecantikan berbasis Laravel 13 untuk memfasilitasi booking pasien, rekam medis dokter, serta kontrol penuh oleh admin.

## Fitur Utama
- Autentikasi & manajemen role-permission (Admin, Dokter, Pasien)
- Dashboard statistik + grafik interaktif (Multi-role Dashboard)
- CRUD Kompleks (4 Entitas Mandiri untuk 4 Anggota Kelompok):
  1. CRUD Dokter (Kelola data profil & spesialisasi dokter)
  2. CRUD Pasien (Kelola data rekam medis & profil pasien)
  3. CRUD Master Layanan (Kelola jenis perawatan & harga treatment)
  4. CRUD Booking Konsultasi (Kelola reservasi jadwal antara pasien & dokter)
- Search, sort, filter, pagination

## Tech Stack
Laravel 13 • PHP 8.5.7 • MySQL 8 (Laragon) • Tailwind CSS

## Instalasi
```bash
git clone https://github.com/Dhiyaan06/cutis-glow.git && cd cutis-glow
composer install && npm install && npm run build
php artisan migrate --seed
php artisan serve
```

## Akun Default
| Email | Password | Role |
| :--- | :--- | :--- |
| admin@cutisglow.com | password | admin |
| dokter@cutisglow.com | password | dokter |
| pasien@cutisglow.com | password | pasien |

## Update Terbaru: Fitur & Perubahan Database

### 1. Modul CRUD Master Layanan Klinik (Cutis Glow)
Berhasil mengimplementasikan modul manajemen layanan klinik secara utuh:
- Halaman utama daftar layanan (`index`)
- Form tambah dan logika simpan data (`create` & `store`)
- Form ubah dan logika perbarui data (`edit` & `update`)
- Logika hapus data (`destroy`)

### PERINTAH WAJIB SETELAH CLONE / PULL (PENTING!)
Ada perubahan pada struktur tabel `users` dengan penambahan kolom `no_hp`. Agar aplikasi tidak mengalami error saat registrasi, jalankan perintah migrasi database berikut di terminal lokal masing-masing:

```bash
php artisan migrate
```

Catatan: Jika migrasi standar mengalami kendala struktur, gunakan perintah fresh (peringatan: akan mengulang data dari awal):

```bash
php artisan migrate:fresh --seed
```

