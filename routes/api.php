<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\LayananApiController;
use App\Http\Controllers\Api\DokterApiController;
use App\Http\Controllers\Api\BookingApiController;
use App\Http\Controllers\Api\RealtimeApiController;

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
