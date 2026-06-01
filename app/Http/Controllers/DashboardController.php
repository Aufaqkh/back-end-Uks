<?php

namespace App\Http\Controllers;

use App\Models\Kunjungan;
use App\Models\Obat;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Ngitung Kunjungan Khusus Hari Ini
        $kunjunganHariIni = Kunjungan::whereDate('created_at', Carbon::today())->count();

        // 2. Ngitung Pasien yang masih 'menunggu' atau di'proses'
        $pasienMengantre = Kunjungan::whereIn('status', ['menunggu', 'proses'])->count();

        // 3. Ngitung Total Seluruh Stok Obat di lemari UKS
        $stokObat = Obat::sum('stok');

        // 4. Ngambil 3 data pasien terakhir yang baru aja masuk UKS
        $kunjunganTerbaru = Kunjungan::latest()->take(3)->get();

        // Kirim semua datanya ke Vue
        return response()->json([
            'kunjungan_hari_ini' => $kunjunganHariIni,
            'pasien_mengantre' => $pasienMengantre,
            'stok_obat' => $stokObat,
            'kunjungan_terbaru' => $kunjunganTerbaru
        ]);
    }
}
