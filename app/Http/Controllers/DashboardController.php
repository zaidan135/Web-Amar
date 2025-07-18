<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Transaksi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $kasirId = Auth::id();

        // Total Transaksi Hari Ini (jumlah, bukan uang)
        $totalTransaksiHariIni = Transaksi::where('user_id', $kasirId)
            ->whereDate('created_at', today())
            ->where('status', 'lunas')
            ->count();

        // Rata-rata Transaksi Harian (jumlah transaksi per hari)
        $transaksiPerHari = Transaksi::where('user_id', $kasirId)
            ->whereDate('created_at', '<', today())
            ->where('status', 'lunas')
            ->select(DB::raw('DATE(created_at) as tanggal, COUNT(*) as jumlah'))
            ->groupBy('tanggal')
            ->pluck('jumlah'); // hasilnya array: [3, 5, 2, ...]

        $rataRataTransaksiHarian = $transaksiPerHari->avg() ?? 0;

        $jumlahPesananSaatIni = Transaksi::where('user_id', $kasirId)
        ->where('status', 'menunggu')
        ->count();


        // Data chart jumlah transaksi 7 hari terakhir
        $chartData = ['labels' => [], 'data' => []];
        for ($i = 6; $i >= 0; $i--) {
            $tanggal = today()->subDays($i);
            $jumlahTransaksi = Transaksi::where('user_id', $kasirId)
                ->whereDate('created_at', $tanggal)
                ->where('status', 'lunas')
                ->count(); // jumlah, bukan total_harga

            $chartData['labels'][] = $tanggal->format('D, d M');
            $chartData['data'][] = $jumlahTransaksi;
        }

        // Ambil order berjalan
        $orderBerjalan = Transaksi::where('user_id', $kasirId)
            ->where('status', 'menunggu')
            ->with('details.produk')
            ->latest()
            ->take(10)
            ->get();

        return view('pages.dashboard.dashboard', [
            'totalPenjualanHariIni' => $totalTransaksiHariIni,
            'rataRataPenjualanHarian' => $rataRataTransaksiHarian,
            'chartData' => $chartData,
            'orderBerjalan' => $orderBerjalan,
            'jumlahPesananSaatIni' => $jumlahPesananSaatIni,
        ]);
    }
}