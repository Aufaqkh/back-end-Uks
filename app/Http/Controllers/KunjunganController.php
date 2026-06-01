<?php

namespace App\Http\Controllers;

use App\Models\Kunjungan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KunjunganController extends Controller
{
    public function index()
    {
        return Kunjungan::with('user')->latest()->paginate(10);
    }

    public function store(Request $request)
    {
        // ✅ VALIDASI DIBUKA BIAR NERIMA STATUS & JEMPUT DARI VUE
        $validated = $request->validate([
            'nama_pasien' => 'required|string|max:255',
            'umur' => 'required|integer|min:1|max:120',
            'keluhan' => 'required|string',
            'status' => 'required|string',         // 🔥 Wajib ada
            'status_jemput' => 'required|string',  // 🔥 Wajib ada
        ]);

        $kunjungan = Kunjungan::create([
            'nama_pasien' => $validated['nama_pasien'],
            'umur' => $validated['umur'],
            'keluhan' => $validated['keluhan'],
            'user_id' => $request->user()->id,              // ✅ AUTO dari user yang login
            'status' => $validated['status'],               // ✅ Ngambil dari pilihan Vue (menunggu/proses/selesai)
            'status_jemput' => $validated['status_jemput'], // ✅ Ngambil dari pilihan Vue (ya/tidak)
        ]);

        return response()->json($kunjungan, 201);
    }

    public function show(Kunjungan $kunjungan)
    {
        return response()->json($kunjungan->load('user'));
    }

    public function update(Request $request, Kunjungan $kunjungan)
    {
        // ✅ Gua tambahin status_jemput di sini biar nanti kalau lu bikin fitur Edit, datanya aman
        $request->validate([
            'status' => 'sometimes|in:menunggu,proses,selesai',
            'status_jemput' => 'sometimes|in:tidak,ya',
            'catatan_dokter' => 'nullable|string',
        ]);

        $kunjungan->update($request->only(['status', 'status_jemput', 'catatan_dokter']));
        return response()->json($kunjungan->load('user'));
    }

    public function destroy(Kunjungan $kunjungan)
    {
        $kunjungan->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
