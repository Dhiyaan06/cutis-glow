<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MasterLayananController;
use App\Http\Controllers\BookingKonsultasiController;
use App\Http\Controllers\DokterController;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\JadwalDokterController;
use App\Http\Controllers\RiwayatLayananController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Shared Access to viewing resources, booking creation, and service list
Route::middleware(['auth', 'role:admin|dokter|pasien'])->group(function () {
    Route::get('layanan', [MasterLayananController::class, 'index'])->name('layanan.index');
    Route::get('dokter', [DokterController::class, 'index'])->name('dokter.index');

    // NOTE: Route get('dokter/{id}') dipindahkan ke paling bawah file ini agar tidak bentrok dengan dokter/create

    Route::get('booking-konsultasi', [BookingKonsultasiController::class, 'index'])->name('booking-konsultasi.index');
    Route::get('booking-konsultasi/create', [BookingKonsultasiController::class, 'create'])->name('booking-konsultasi.create');
    Route::post('booking-konsultasi', [BookingKonsultasiController::class, 'store'])->name('booking-konsultasi.store');
    Route::get('booking-konsultasi/{id}', [BookingKonsultasiController::class, 'show'])->name('booking-konsultasi.show');

    Route::get('riwayat-layanan', [RiwayatLayananController::class, 'index'])->name('riwayat-layanan.index');
});

// Admin and Dokter Access to booking actions and writing treatment history
Route::middleware(['auth', 'role:admin|dokter'])->group(function () {
    Route::post('booking-konsultasi/{id}/konfirmasi', [BookingKonsultasiController::class, 'konfirmasi'])->name('booking-konsultasi.konfirmasi');
    Route::post('booking-konsultasi/{id}/selesai', [BookingKonsultasiController::class, 'selesai'])->name('booking-konsultasi.selesai');
    Route::post('booking-konsultasi/{id}/batal', [BookingKonsultasiController::class, 'batal'])->name('booking-konsultasi.batal');
    Route::get('riwayat-layanan/create', [RiwayatLayananController::class, 'create'])->name('riwayat-layanan.create');
    Route::post('riwayat-layanan', [RiwayatLayananController::class, 'store'])->name('riwayat-layanan.store');
});

// Admin-only Access to Management
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('layanan', MasterLayananController::class)->except(['index']);
    Route::resource('dokter', DokterController::class)->except(['index', 'show']); // Di sini dokter/create dibuat
    Route::resource('pasien', PasienController::class);
    Route::resource('booking-konsultasi', BookingKonsultasiController::class)->except(['index', 'show', 'create', 'store', 'konfirmasi', 'selesai', 'batal']);

    Route::resource('jadwal-dokter', JadwalDokterController::class);
    Route::resource('riwayat-layanan', RiwayatLayananController::class)->except(['index', 'create', 'store']);
});

// Route berparameter/wildcard ditaruh di paling bawah agar tidak memakan URL spesifik (seperti dokter/create)
Route::middleware(['auth', 'role:admin|dokter|pasien'])->group(function () {
    Route::get('dokter/{id}', [DokterController::class, 'show'])->name('dokter.show');
});

require __DIR__.'/auth.php';
