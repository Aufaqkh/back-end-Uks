<?php

namespace App\Http\Controllers;

// 1. KITA IMPORT MODEL PASIEN YANG BARU DAN BENAR DI SINI BRAY!
use App\Models\Pasien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PasienController extends Controller
{
    /**
     * 1. FUNGSI TAMPILKAN PASIEN
     */
    public function index()
    {
        try {
            // Mengambil semua data dari tabel pasien asli bray
            $pasien = Pasien::orderBy('created_at', 'desc')->get();

            return response()->json([
                'status' => 'success',
                'data' => $pasien
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * 2. FUNGSI SIMPAN DATA PASIEN
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required|string|max:255',
            'nisn'         => 'required|string|unique:pasien,nisn',
            'kelas'        => 'required|string|max:50',
            'no_hp'        => 'required|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => $validator->errors()->first()
            ], 422);
        }

        // Memasukkan data ke kolom database asli kamu bray
        $pasien = Pasien::create([
            'user_id' => $request->user()->id,
            'name'    => $request->nama_lengkap,
            'nisn'    => $request->nisn,
            'kelas'   => $request->kelas,
            'no_hp'   => $request->no_hp,
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Pasien berhasil ditambahkan bray! 🎉',
            'data'    => $pasien
        ], 201);
    }
}
