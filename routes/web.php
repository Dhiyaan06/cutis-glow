<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MasterLayananController;
use App\Http\Controllers\BookingKonsultasiController;
use App\Http\Controllers\ManajemenDokterController;
use App\Http\Controllers\ManajemenPasienController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
   return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::get('/dashboard', function () {
       return view('dashboard');
    })->middleware(['auth', 'verified'])->name('dashboard');


    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Booking
    Route::resource('booking', BookingKonsultasiController::class);

    Route::get('/booking-riwayat', [BookingKonsultasiController::class, 'riwayat'])
        ->name('booking.riwayat');

    Route::put('/booking/{id}/selesai', [BookingKonsultasiController::class, 'selesai'])
        ->name('booking.selesai');
});

Route::resource('layanan', MasterLayananController::class);

Route::middleware(['auth'])->group(function () {
Route::resource('booking', BookingKonsultasiController::class);

Route::get('/booking-riwayat', [BookingKonsultasiController::class, 'riwayat'])
    ->name('booking.riwayat');

Route::put('/booking/{id}/selesai', [BookingKonsultasiController::class, 'selesai'])
    ->name('booking.selesai');
});

Route::resource('dokter', ManajemenDokterController::class);


Route::middleware(['auth', 'role:admin'])->group(function () {

    Route::resource('layanan', MasterLayananController::class);
    Route::resource('pasien', ManajemenPasienController::class);
    Route::resource('dokter', ManajemenDokterController::class);

});

require __DIR__.'/auth.php';
