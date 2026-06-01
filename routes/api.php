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

    // 🔥 JALUR AJIB: Kita buka fitur LIHAT OBAT di sini biar Siswa bisa akses!
    Route::get('/obats', [ObatController::class, 'index']);
    Route::get('/obats/{obat}', [ObatController::class, 'show']);

    // Jalur kunjungan terbuka buat semua role biar Siswa bisa ngeluh sakit!
    Route::middleware('role:admin,petugas,siswa,pasien')->group(function () {
        Route::apiResource('kunjungan', KunjunganController::class);
    });

    // Rute yang cuma bisa diakses Admin DAN Petugas (Siswa gak boleh ke sini)
    Route::middleware('role:admin,petugas')->group(function () {
        Route::get('/pasien', [PasienController::class, 'index']);
        Route::post('/pasien', [PasienController::class, 'store']);
        Route::post('kunjungan/{kunjungan}/status', [KunjunganController::class, 'updateStatus']);

        // 🔥 JALUR AMAN: Fitur manipulasi obat dikurung ketat di sini (Siswa dijamin gak bisa tembus)
        Route::post('/obats', [ObatController::class, 'store']);
        Route::put('/obats/{obat}', [ObatController::class, 'update']);
        Route::delete('/obats/{obat}', [ObatController::class, 'destroy']);

        Route::get('notifikasis', [NotifikasiController::class, 'index']);
        Route::post('notifikasis/{id}/read', [NotifikasiController::class, 'markAsRead']);
    });

    // Rute Khusus ADMIN saja
    Route::middleware('role:admin')->group(function () {
        Route::get('statistik', [StatistikController::class, 'index']);
    });
});
