# Cutis Glow — Backend (Laravel)

Sistem manajemen klinik kecantikan berbasis **Laravel** (panel web untuk admin/dokter/pasien) yang juga menyediakan **REST API** untuk dikonsumsi aplikasi mobile (Flutter).

---

## Tim Pengembang
1. Dhiya'an sani (230102036)
2. Dini sri ayu p (230102040)
3. Hilmi durroh t (230102060)
4. Putri leonita cb (230102108)


## 1. Spesifikasi & Pemenuhan Fitur Wajib

| # | Requirement | Status | Keterangan |
|---|---|---|---|
| 1 | **Migration & Seeder** | ✅ | Seluruh 8 tabel dibuat lewat `database/migrations/`, tidak ada tabel manual. Seeder (`RolePermissionSeeder`) mengisi 3 role, 5 akun contoh, 5 layanan, jadwal dokter, dan beberapa booking contoh — demo bisa langsung jalan dari database kosong lewat `migrate:fresh --seed`. |
| 2 | **Autentikasi** | ✅ | Login/Register/Logout memakai **Laravel Breeze** (scaffolding bawaan). Validasi input di setiap `Request`/`validate()`, proteksi CSRF otomatis aktif lewat middleware `web` bawaan Laravel (`@csrf` di semua form Blade). |
| 3 | **Role & Permission** | ✅ | 3 role (**admin, dokter, pasien** — melebihi minimal 2) memakai **Spatie Laravel-Permission**. Akses halaman dibatasi middleware `role:admin` / `role:dokter` / `role:pasien` di `routes/web.php` & `routes/api.php`. |
| 4 | **3 Halaman CRUD** | ✅ | 4 entitas dengan Create/Read/Update/Delete lengkap + konfirmasi hapus: **Layanan**, **Dokter**, **Pasien**, **Jadwal Dokter** (di luar model User/Role bawaan). |
| 5 | **Dashboard** | ✅ | `DashboardController` menampilkan ringkasan berbeda per role: total dokter/pasien/layanan/booking aktif (admin), total booking & jadwal (dokter), total treatment & pengeluaran (pasien) — plus daftar 5 booking/treatment terbaru. Semua query pakai `count()`/`sum()` agregat, bukan `get()` seluruh tabel. |
| 6 | **Search & Pagination** | ✅ | Halaman Layanan, Dokter, dan Pasien memakai `->paginate(10)->withQueryString()` dan mendukung pencarian by nama/spesialis/email. Tidak ada halaman yang menampilkan seluruh data sekaligus. |
| 7 | **UI/UX Responsif** | ✅ | Tailwind CSS, layout konsisten lewat `resources/views/layouts/`. |
| 8 | **REST API + Dokumentasi** | ✅ | 8 resource API (jauh di atas minimal 2) — lihat [Dokumentasi Endpoint](#5-dokumentasi-rest-api) di bawah. |

### Integrasi Client-Server (poin tambahan)

| Requirement | Status | Keterangan |
|---|---|---|
| Terhubung ke client via RESTful API | ✅ | Aplikasi mobile Flutter 100% berkomunikasi lewat REST API (JSON) di atas, tidak ada akses langsung ke database. |
| Sinkronisasi real-time / on-demand | ✅ (on-demand) | Data disinkronkan **on-demand** (pull-to-refresh & auto-refresh setelah aksi CRUD/booking) — dipilih di atas *real-time push* (SSE) karena server development (`php artisan serve`) bersifat single-threaded sehingga tidak cocok untuk koneksi long-lived; solusi realtime penuh membutuhkan server production-grade (Octane/WebSocket) yang di luar cakupan tugas ini. Requirement mengizinkan salah satu dari real-time **atau** on-demand. |
| Autentikasi & otorisasi aman | ✅ | Token **Laravel Sanctum** per perangkat, disimpan di secure storage HP (Keychain/Keystore), dikirim lewat header `Authorization: Bearer <token>`. Endpoint admin-only dilindungi middleware `role:admin` di level route, bukan cuma disembunyikan di UI. |

---

## 2. Tech Stack

| Komponen | Teknologi |
|---|---|
| Framework | Laravel ^13.8 (PHP ^8.3) |
| Autentikasi web | Laravel Breeze |
| Autentikasi API | Laravel Sanctum (token-based) |
| Role & Permission | Spatie Laravel-Permission |
| Database | MySQL |
| Styling | Tailwind CSS |
| Mobile client | Flutter (Riverpod + Dio) — repo terpisah |

---

## 3. Struktur Database

| Tabel | Fungsi |
|---|---|
| `users` | Akun login semua role (admin/dokter/pasien) |
| `dokter` | Profil dokter (spesialis, no. STR, alamat), relasi 1-1 ke `users` |
| `pasien` | Profil pasien (alamat, tanggal lahir, jenis kelamin), relasi 1-1 ke `users` |
| `master_layanan` | Katalog layanan/treatment (nama, harga, diskon %) |
| `jadwal_dokter` | Slot jadwal praktek per dokter (hari, jam mulai, jam selesai) |
| `booking_konsultasi` | Booking pasien ke dokter (status: pending/dikonfirmasi/selesai/dibatalkan) |
| `riwayat_layanan` | Rekam treatment yang sudah selesai dilakukan |
| `notifikasi` | Notifikasi per user |

---

## 4. Instalasi

### Prasyarat
- PHP ^8.3, Composer
- MySQL (atau MariaDB)
- Node.js (untuk compile asset Tailwind, opsional kalau cuma pakai API)

### Langkah

```bash
# 1. Clone & masuk folder
cd cutis-glow

# 2. Install dependency PHP
composer install

# 3. Copy & konfigurasi environment
cp .env.example .env
php artisan key:generate
```

Edit `.env`, ganti bagian database ke MySQL:
```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_cutis_glow
DB_USERNAME=root
DB_PASSWORD=
```

```bash
# 4. Buat database db_cutis_glow (lewat phpMyAdmin/HeidiSQL/CLI), lalu:
php artisan migrate:fresh --seed

# 5. (Opsional) compile asset frontend
npm install
npm run build

# 6. Jalankan server
php artisan serve
```

Buka `http://127.0.0.1:8000` di browser.

### Menjalankan agar bisa diakses aplikasi mobile (HP fisik)

```bash
php artisan serve --host=0.0.0.0 --port=8000
```

Pastikan HP & komputer terhubung ke WiFi yang sama, lalu di aplikasi Flutter arahkan `API_BASE_URL` ke IP lokal komputer (cek lewat `ipconfig`), contoh: `http://192.168.1.5:8000/api`.

---

## 5. Akun Default (dari Seeder)

Semua akun memakai password: **`password`**

| Role | Email | Nama |
|---|---|---|
| Admin | `admin@cutisglow.com` | Admin Cutis Glow |
| Dokter | `dokter@cutisglow.com` | Dr. Clarissa Pinkan |
| Dokter | `bramasta@cutisglow.com` | Dr. Bramasta Putera |
| Pasien | `pasien@cutisglow.com` | Adelia Putri |
| Pasien | `rian@cutisglow.com` | Rian Kurnia |

---

## 6. Dokumentasi REST API

Base URL: `http://<host>:8000/api`

Semua endpoint (kecuali Register & Login) butuh header:
```
Authorization: Bearer <token>
Accept: application/json
```

### Autentikasi (Publik)

| Method | Endpoint | Deskripsi |
|---|---|---|
| POST | `/register` | Registrasi akun baru (role pasien) |
| POST | `/login` | Login, mengembalikan token Sanctum |

### Umum (butuh login, semua role)

| Method | Endpoint | Deskripsi |
|---|---|---|
| POST | `/logout` | Logout, menghapus token aktif |
| GET | `/user` | Data akun yang sedang login |
| PUT | `/profile` | Update nama/no HP/password akun sendiri |
| GET | `/layanan` | Daftar semua layanan |
| GET | `/dokter` | Daftar semua dokter (support `?search=` & `?spesialis=`) |
| GET | `/dokter/schedules?id_dokter=` | Jadwal praktek dokter tertentu |
| GET | `/bookings` | Daftar booking (admin: semua; dokter/pasien: miliknya) |
| POST | `/bookings` | Buat booking baru |
| POST | `/bookings/{id}/konfirmasi` | Konfirmasi booking |
| POST | `/bookings/{id}/batal` | Batalkan booking |
| POST | `/bookings/{id}/selesai` | Selesaikan booking + catat riwayat treatment |
| GET | `/riwayat-layanan` | Riwayat treatment |
| GET | `/notifikasi` | Daftar notifikasi milik user |
| POST | `/notifikasi/{id}/read` | Tandai notifikasi sudah dibaca |

### Khusus Role `dokter`

| Method | Endpoint | Deskripsi |
|---|---|---|
| GET | `/me/dokter` | Profil dokter milik akun yang login (untuk lihat jadwal sendiri) |

### Khusus Role `admin`

| Method | Endpoint | Deskripsi |
|---|---|---|
| POST | `/layanan` | Tambah layanan |
| GET / PUT / DELETE | `/layanan/{id}` | Detail / ubah / hapus layanan |
| POST | `/dokter` | Tambah dokter (otomatis buat akun login) |
| GET / PUT / DELETE | `/dokter/{id}` | Detail / ubah / hapus dokter |
| POST | `/jadwal-dokter` | Tambah slot jadwal praktek |
| PUT / DELETE | `/jadwal-dokter/{id}` | Ubah / hapus slot jadwal |
| GET | `/pasien` | Daftar semua pasien (support `?search=`) |
| POST | `/pasien` | Tambah pasien (otomatis buat akun login) |
| GET / PUT / DELETE | `/pasien/{id}` | Detail / ubah / hapus pasien |

**Format response** (konsisten di semua endpoint):
```json
{
  "status": "success",
  "message": "...",
  "data": { }
}
```
Error validasi (422) mengembalikan tambahan field `errors` per-field, mengikuti format bawaan Laravel.

---

## 7. Struktur Folder (ringkas)

```
app/
├── Http/Controllers/          # Controller untuk web (Blade)
├── Http/Controllers/Api/      # Controller untuk REST API (dikonsumsi Flutter)
├── Models/                    # Eloquent model (1 per tabel)
routes/
├── web.php                    # Route halaman web (session-based, Breeze)
├── api.php                    # Route REST API (token-based, Sanctum)
database/
├── migrations/                # Skema tabel
├── seeders/                   # Data awal (role, akun, layanan, jadwal, booking contoh)
resources/views/                # Halaman Blade (Tailwind)
```

---

## 8. Catatan Pengembangan

- Notifikasi realtime (Server-Sent Events) sudah diimplementasikan di `RealtimeApiController` namun **tidak diaktifkan di sisi mobile** karena `php artisan serve` tidak mendukung koneksi concurrent/long-lived dengan baik (single-threaded). Data tetap tersinkronisasi lewat mekanisme on-demand (refresh manual/otomatis setelah aksi).
