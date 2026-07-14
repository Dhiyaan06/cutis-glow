<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\LayananApiController;
use App\Http\Controllers\Api\DokterApiController;
use App\Http\Controllers\Api\BookingApiController;
use App\Http\Controllers\Api\RealtimeApiController;
use App\Http\Controllers\Api\PasienApiController;
use App\Http\Controllers\Api\JadwalDokterApiController;

// Public Routes
Route::post('/register', [AuthApiController::class, 'register']);
Route::post('/login', [AuthApiController::class, 'login']);

// Protected Routes (Sanctum)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthApiController::class, 'logout']);

    // Catalog & Master Data
    Route::get('/layanan', [LayananApiController::class, 'index']);
    Route::get('/dokter', [DokterApiController::class, 'index']);
    Route::get('/dokter/schedules', [DokterApiController::class, 'schedules']);

    // Bookings & Treatment History
    Route::get('/bookings', [BookingApiController::class, 'index']);
    Route::post('/bookings', [BookingApiController::class, 'store']);
    Route::post('/bookings/{id}/konfirmasi', [BookingApiController::class, 'konfirmasi']);
    Route::post('/bookings/{id}/batal', [BookingApiController::class, 'batal']);
    Route::post('/bookings/{id}/selesai', [BookingApiController::class, 'selesai']);
    Route::get('/riwayat-layanan', [BookingApiController::class, 'riwayat']);

    // Notifications
    Route::get('/notifikasi', [BookingApiController::class, 'notifications']);
    Route::post('/notifikasi/{id}/read', [BookingApiController::class, 'markNotificationRead']);

    // Server-Sent Events Realtime Updates
    Route::get('/realtime-updates', [RealtimeApiController::class, 'stream']);

    // ================= Admin Only: Manajemen Data Master =================
    Route::middleware('role:admin')->group(function () {
        // Kelola Layanan
        Route::post('/layanan', [LayananApiController::class, 'store']);
        Route::get('/layanan/{id}', [LayananApiController::class, 'show']);
        Route::put('/layanan/{id}', [LayananApiController::class, 'update']);
        Route::delete('/layanan/{id}', [LayananApiController::class, 'destroy']);

        // Kelola Dokter (otomatis bikin akun user dokter)
        Route::post('/dokter', [DokterApiController::class, 'store']);
        Route::get('/dokter/{id}', [DokterApiController::class, 'show']);
        Route::put('/dokter/{id}', [DokterApiController::class, 'update']);
        Route::delete('/dokter/{id}', [DokterApiController::class, 'destroy']);

        // Kelola Jadwal Praktek Dokter
        Route::post('/jadwal-dokter', [JadwalDokterApiController::class, 'store']);
        Route::put('/jadwal-dokter/{id}', [JadwalDokterApiController::class, 'update']);
        Route::delete('/jadwal-dokter/{id}', [JadwalDokterApiController::class, 'destroy']);

        // Kelola Pasien (otomatis bikin akun user pasien)
        Route::get('/pasien', [PasienApiController::class, 'index']);
        Route::get('/pasien/{id}', [PasienApiController::class, 'show']);
        Route::post('/pasien', [PasienApiController::class, 'store']);
        Route::put('/pasien/{id}', [PasienApiController::class, 'update']);
        Route::delete('/pasien/{id}', [PasienApiController::class, 'destroy']);
    });

    // Get current logged-in user profile
    Route::get('/user', function (Request $request) {
        $user = $request->user();
        return response()->json([
            'status' => 'success',
            'data' => [
                'id_pengguna' => $user->id_pengguna,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'no_hp' => $user->no_hp,
                'status_aktif' => $user->status_aktif
            ]
        ]);
    });
});
