<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\KunjunganController;
use App\Http\Controllers\ObatController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\StatistikController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    // User
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::get('/me', [UserController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // ✅ KUNJUNGAN (Core)
    Route::apiResource('kunjungans', KunjunganController::class);
    Route::post('kunjungans/{kunjungan}/status', [KunjunganController::class, 'updateStatus']);
    
    // ✅ OBAT
    Route::get('obats', [ObatController::class, 'index']);
    
    // ✅ NOTIFIKASI
    Route::get('notifikasis', [NotifikasiController::class, 'index']);
    Route::post('notifikasis/{id}/read', [NotifikasiController::class, 'markAsRead']);
    
    // ✅ STATISTIK (Petugas only)
    Route::middleware('role:petugas')->get('statistik', [StatistikController::class, 'index']);
});