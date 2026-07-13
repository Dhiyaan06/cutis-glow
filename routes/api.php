<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\LayananApiController;
use App\Http\Controllers\Api\DokterApiController;

Route::post('/login', [AuthApiController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthApiController::class, 'logout']);
    Route::apiResource('layanan', LayananApiController::class);
    Route::apiResource('dokter', DokterApiController::class);

});
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
