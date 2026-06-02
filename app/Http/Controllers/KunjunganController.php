<?php

namespace App\Http\Controllers;

use App\Models\Kunjungan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class KunjunganController extends Controller
{
    // 1. INI PINTU BUAT NAMPILIN DATA (UDAH DIMODIFIKASI JALUR SISWA)
    public function index(Request $request) // 🔥 Pastiin ada Request $request di sini
    {
        // Kalo yang ngakses adalah SISWA, cuma tampilin riwayat dia sendiri bray
        if ($request->user()->role === 'siswa') {
            return Kunjungan::where('user_id', $request->user()->id)->latest()->paginate(10);
        }

        // Kalo ADMIN / PETUGAS, tampilin semua data pasien
        return Kunjungan::with('user')->latest()->paginate(10);
    }

    // 2. INI BUAT NYIMPAN DATA BARU
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_pasien' => 'required|string|max:255',
            'umur' => 'required|integer',
            'keluhan' => 'required|string',
            'status' => 'required|string',
            'status_jemput' => 'required|string',
            'lokasi_jemput' => 'nullable|string',
        ]);

        return Kunjungan::create([
            'nama_pasien' => $validated['nama_pasien'],
            'umur' => $validated['umur'],
            'keluhan' => $validated['keluhan'],
            'user_id' => $request->user()->id,
            'status' => $validated['status'],
            'status_jemput' => $validated['status_jemput'],
            'lokasi_jemput' => $validated['lokasi_jemput'] ?? null,
        ]);
    }

    // 3. INI FUNGSI SAKTI BUAT JEMPUTAN
    public function updateStatus(Request $request, Kunjungan $kunjungan)
    {
        $request->validate([
            'status' => 'sometimes|in:menunggu,proses,selesai',
            'status_jemput' => 'sometimes|in:datang,jemput,disetujui',
        ]);

        try {
            if ($request->has('status')) { $kunjungan->status = $request->status; }
            if ($request->has('status_jemput')) { $kunjungan->status_jemput = $request->status_jemput; }

            $kunjungan->save();
            return response()->json($kunjungan->load('user'));

        } catch (\Exception $e) {
            Log::error('Gagal update status: ' . $e->getMessage());
            return response()->json(['message' => 'Gagal simpan: ' . $e->getMessage()], 500);
        }
    }

    // 4. JANGAN LUPA FUNGSI LAINNYA
    public function destroy(Kunjungan $kunjungan)
    {
        $kunjungan->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
