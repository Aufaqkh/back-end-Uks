<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use Illuminate\Http\Request;

class ObatController extends Controller
{
    // Fungsi bawaan lu buat nampilin data (Udah bener banget ini)
   public function store(Request $request)
    {
        // 1. Validasi inputan dari Vue
        $request->validate([
            'nama_obat' => 'required|string|max:255',
            'stok'      => 'required|integer',
            'kategori'  => 'required|string',
        ]);

        try {
            // 2. Simpan ke database
            $obat = Obat::create([
                // 🔥 INI YANG DIGANTI! Biar nyambung sama kolom database lu 🔥
                'nama'      => $request->nama_obat,
                'stok'      => $request->stok,
                'kategori'  => $request->kategori,
                'harga'     => 0,
            ]);

            // 3. Laporan sukses ke Vue
            return response()->json([
                'status'  => 'success',
                'message' => 'Obat berhasil ditambahkan bre!',
                'data'    => $obat
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal simpan ke database: ' . $e->getMessage()
            ], 500);
        }
    }
}
