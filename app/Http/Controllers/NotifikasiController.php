<?php
namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    public function index()
    {
        $notifikasis = Notifikasi::with(['pasien', 'kunjungan'])
            ->when(auth()->user()->petugasUks, function($q) {
                $q->where('petugas_id', auth()->user()->petugasUks->id);
            })
            ->latest()
            ->get();

        return response()->json($notifikasis);
    }

    public function markAsRead($id)
    {
        $notifikasi = Notifikasi::findOrFail($id);
        $notifikasi->update(['status' => 'sudah_dibaca']);
        return response()->json($notifikasi);
    }
}