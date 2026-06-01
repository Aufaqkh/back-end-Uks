<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\KunjunganController;
use App\Http\Controllers\ObatController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\StatistikController;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\DashboardController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    // Rute Umum (Semua yang login bisa akses)
    Route::get('/user', function (Request $request) { return $request->user(); });
    Route::get('/me', [UserController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // 🔥 INI OBATNYA: Jalur kunjungan kita buka buat semua role biar Siswa bisa ngeluh sakit!
    Route::middleware('role:admin,petugas,siswa,pasien')->group(function () {
        Route::apiResource('kunjungan', KunjunganController::class);
    });

    // Rute yang cuma bisa diakses Admin DAN Petugas (Siswa gak boleh ke sini)
    Route::middleware('role:admin,petugas')->group(function () {
        Route::get('/pasien', [PasienController::class, 'index']);
        Route::post('/pasien', [PasienController::class, 'store']);
        Route::post('kunjungan/{kunjungan}/status', [KunjunganController::class, 'updateStatus']);
        Route::apiResource('obats', ObatController::class);
        Route::get('notifikasis', [NotifikasiController::class, 'index']);
        Route::post('notifikasis/{id}/read', [NotifikasiController::class, 'markAsRead']);
    });

    // Rute Khusus ADMIN saja
    Route::middleware('role:admin')->group(function () {
        Route::get('statistik', [StatistikController::class, 'index']);
    });
});
