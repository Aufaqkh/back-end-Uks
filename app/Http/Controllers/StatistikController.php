<?php
namespace App\Http\Controllers;

use App\Models\Kunjungan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class StatistikController extends Controller
{
    public function index()
    {
        $totalKunjungan = Kunjungan::count();
        $kunjunganHariIni = Kunjungan::whereDate('tanggal', today())->count();
        $kunjunganMingguIni = Kunjungan::whereBetween('tanggal', [
            now()->startOfWeek(), 
            now()->endOfWeek()
        ])->count();

        $bulanan = Kunjungan::selectRaw('MONTH(tanggal) as bulan, COUNT(*) as total')
            ->whereYear('tanggal', now()->year)
            ->groupBy('bulan')
            ->get();

        return response()->json([
            'total_kunjungan' => $totalKunjungan,
            'hari_ini' => $kunjunganHariIni,
            'minggu_ini' => $kunjunganMingguIni,
            'bulanan' => $bulanan
        ]);
    }
}