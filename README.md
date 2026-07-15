# 🌸 Cutis Glow - Sistem Informasi & Portal Klinik Kecantikan

Cutis Glow merupakan aplikasi portal dan manajemen klinik kecantikan berbasis **Laravel 13** yang dikembangkan untuk membantu proses pengelolaan klinik, mulai dari booking konsultasi, pengelolaan layanan, data pasien, data dokter, hingga dashboard admin.

---

## ✨ Fitur Utama

### 🔐 Authentication & Authorization
- Login & Logout
- Role & Permission Management
- Multi Role (Admin, Dokter, Pasien)

### 📊 Dashboard
- Dashboard Admin
- Dashboard Dokter
- Dashboard Pasien
- Statistik Data
- Grafik Interaktif

### 📝 CRUD Management
- **CRUD Dokter**
  - Kelola data dokter
  - Spesialisasi
  - Jadwal praktik

- **CRUD Pasien**
  - Kelola profil pasien
  - Data rekam medis

- **CRUD Master Layanan**
  - Tambah layanan
  - Edit layanan
  - Hapus layanan
  - Harga treatment

- **CRUD Booking Konsultasi**
  - Booking pasien
  - Jadwal konsultasi dokter
  - Status booking

### 🔎 Fitur Pendukung
- Search
- Sorting
- Filter
- Pagination
- Flash Message
- Validasi Form

---

## 🛠 Tech Stack

- Laravel 13
- PHP 8.5.7
- MySQL 8
- Tailwind CSS
- Laravel Breeze
- Spatie Laravel Permission

---

## 🚀 Instalasi

Clone repository

```bash
git clone https://github.com/Dhiyaan06/cutis-glow.git
```

Masuk ke folder project

```bash
cd cutis-glow
```

Install dependency

```bash
composer install
npm install
```

Copy file environment

```bash
cp .env.example .env
```

Generate application key

```bash
php artisan key:generate
```

Sesuaikan konfigurasi database pada file **.env**

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cutis_glow
DB_USERNAME=root
DB_PASSWORD=
```

Jalankan migrasi dan seeder

```bash
php artisan migrate --seed
```

Jalankan Vite

```bash
npm run dev
```

Jalankan server Laravel

```bash
php artisan serve
```

---

## 👤 Akun Default

| Role | Email | Password |
|------|-------------------------|----------|
| Admin | admin@cutisglow.com | password |
| Dokter | dokter@cutisglow.com | password |
| Pasien | pasien@cutisglow.com | password |

---

# 📢 Update Terbaru

## ✅ Modul CRUD Master Layanan

Berhasil diimplementasikan modul **Master Layanan Klinik**, meliputi:

- Menampilkan daftar layanan
- Menambahkan layanan baru
- Mengubah data layanan
- Menghapus layanan
- Validasi input
- Flash message setelah aksi CRUD

---

## ⚠️ Perubahan Database

Terdapat perubahan pada tabel **users** dengan penambahan kolom:

- `no_hp`

Setelah melakukan **clone** atau **pull** terbaru, jalankan migrasi berikut:

```bash
php artisan migrate
```

Apabila terjadi konflik struktur database, gunakan:

```bash
php artisan migrate:fresh --seed
```

> **Perhatian:** Perintah `migrate:fresh --seed` akan menghapus seluruh data pada database dan membuat ulang tabel beserta data awal.

---

## 📂 Struktur Fitur

```
app/
├── Models
├── Http
│   ├── Controllers
│   └── Requests
├── Policies
├── Providers

resources/
├── views
├── css
└── js

database/
├── migrations
├── seeders
└── factories
```

---

## 👥 Tim Pengembang

**Kelompok 4**

- Dhiya'an Sani
- Dini Sri Ayu Priyono
- Hilmi Durroh Taqwiyah
- Putri Leonita Cikal Buchori

---

## 📄 Keterangan

Project ini dikembangkan sebagai tugas besar mata kuliah **Pemrograman Web (PWeb)** Program Studi Teknik Informatika, Fakultas Sains dan Teknologi, Universitas Muhammadiyah Bandung.
