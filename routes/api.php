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

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    // User
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::get('/me', [UserController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);


    Route::get('/pasien', [PasienController::class, 'index']);
    Route::post('/pasien', [PasienController::class, 'store']);


    Route::apiResource('kunjungan', KunjunganController::class);
    Route::post('kunjungan/{kunjungan}/status', [KunjunganController::class, 'updateStatus']);


    Route::get('obats', [ObatController::class, 'index']);


    Route::get('notifikasis', [NotifikasiController::class, 'index']);
    Route::post('notifikasis/{id}/read', [NotifikasiController::class, 'markAsRead']);

    Route::middleware('role:petugas')->get('statistik', [StatistikController::class, 'index']);
});
