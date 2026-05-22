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
        // ✅ VALIDASI TANPA user_id & status_jemput di body
        $validated = $request->validate([
            'nama_pasien' => 'required|string|max:255',
            'umur' => 'required|integer|min:1|max:120',
            'keluhan' => 'required|string',
            // ✅ user_id AUTO dari auth
            // ✅ status_jemput DEFAULT
        ]);

        $kunjungan = Kunjungan::create([
            'nama_pasien' => $validated['nama_pasien'],
            'umur' => $validated['umur'],
            'keluhan' => $validated['keluhan'],
            'user_id' => $request->user()->id,  // ✅ AUTO
            'status_jemput' => 'datang',        // ✅ DEFAULT
            'status' => 'menunggu',
        ]);

        return response()->json($kunjungan, 201);
    }

    public function show(Kunjungan $kunjungan)
    {
        return response()->json($kunjungan->load('user'));
    }

    public function update(Request $request, Kunjungan $kunjungan)
    {
        $request->validate([
            'status' => 'sometimes|in:menunggu,proses,selesai',
            'catatan_dokter' => 'nullable|string',
        ]);

        $kunjungan->update($request->only(['status', 'catatan_dokter']));
        return response()->json($kunjungan->load('user'));
    }

    public function destroy(Kunjungan $kunjungan)
    {
        $kunjungan->delete();
        return response()->json(['message' => 'Deleted']);
    }
}