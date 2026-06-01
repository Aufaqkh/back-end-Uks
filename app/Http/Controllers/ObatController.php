<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ObatController extends Controller
{
    // 1. Tampil Semua Data
    public function index()
    {
        return Obat::latest()->paginate(10);
    }

    // 2. Tambah Data Baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_obat' => 'required|string|max:255',
            'stok'      => 'required|integer',
            'kategori'  => 'required|string',
        ]);

        try {
            $obat = Obat::create([
                'nama'      => $request->nama_obat,
                'stok'      => $request->stok,
                'kategori'  => $request->kategori,
                'harga'     => 0,
            ]);

            return response()->json(['message' => 'Obat berhasil ditambahkan!', 'data' => $obat], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal: ' . $e->getMessage()], 500);
        }
    }

    // 3. Edit / Update Data
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_obat' => 'required|string|max:255',
            'stok'      => 'required|integer',
            'kategori'  => 'required|string',
        ]);

        try {
            $obat = Obat::findOrFail($id);
            $obat->update([
                'nama'      => $request->nama_obat,
                'stok'      => $request->stok,
                'kategori'  => $request->kategori,
            ]);

            return response()->json(['message' => 'Obat berhasil diupdate!']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal update: ' . $e->getMessage()], 500);
        }
    }

    // 4. Hapus Data
    public function destroy($id)
    {
        try {
            $obat = Obat::findOrFail($id);
            $obat->delete();
            return response()->json(['message' => 'Obat berhasil dihapus!']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal hapus: ' . $e->getMessage()], 500);
        }
    }
}
