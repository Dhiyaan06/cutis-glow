<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Dashboard umum (login + verifikasi email)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// ======================
// ADMIN
// ======================
Route::middleware(['auth', 'role:admin'])->group(function () {

    Route::get('/admin/dashboard', function () {
        return 'Selamat Datang Admin';
    })->name('admin.dashboard');

});

// ======================
// DOCTOR
// ======================
Route::middleware(['auth', 'role:doctor'])->group(function () {

    Route::get('/doctor/dashboard', function () {
        return 'Selamat Datang Doctor';
    })->name('doctor.dashboard');

});

// ======================
// PATIENT
// ======================
Route::middleware(['auth', 'role:patient'])->group(function () {

    Route::get('/patient/dashboard', function () {
        return 'Selamat Datang Patient';
    })->name('patient.dashboard');

});

// Profile (Bawaan Breeze)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
