<?php

namespace App\Http\Controllers;

use App\Models\{Kategori, Produk, Transaksi, DetailTransaksi};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function index(Request $request): View
    {
        $page = $request->query('page', 'dashboard');
        $data = [];

        $view = match ($page) {
            'category' => 'pages-admin.category.index',
            'product' => 'pages-admin.product.index',
            'report' => 'pages-admin.report.index',
            default => 'pages-admin.dashboard.index',
        };

        if (!view()->exists($view)) {
            abort(404);
        }

        if ($page === 'dashboard') {
            // Penjualan Hari Ini
            $data['totalPenjualanHariIni'] = Transaksi::where('status', 'lunas')
                ->whereDate('tanggal_pembayaran', today())
                ->sum('total_harga');

            // Penjualan Bulan Ini
            $data['penjualanBulanIni'] = Transaksi::where('status', 'lunas')
                ->whereMonth('tanggal_pembayaran', now()->month)
                ->whereYear('tanggal_pembayaran', now()->year)
                ->sum('total_harga');

            // Rata-rata Penjualan Harian
            $subQuery = DB::table('transaksis')
                ->selectRaw('SUM(total_harga) as daily_total')
                ->where('status', 'lunas')
                ->whereNotNull('tanggal_pembayaran')
                ->groupByRaw('DATE(tanggal_pembayaran)');

            $data['rataRataPenjualanHarian'] = DB::table($subQuery, 'daily_totals')->avg('daily_total') ?? 0;

            // Rata-rata Penjualan Bulanan
            $subQueryBulanan = DB::table('transaksis')
                ->selectRaw('SUM(total_harga) as monthly_total')
                ->where('status', 'lunas')
                ->whereNotNull('tanggal_pembayaran')
                ->groupByRaw('YEAR(tanggal_pembayaran), MONTH(tanggal_pembayaran)');
                    
            $data['rataRataPenjualanBulanan'] = DB::table($subQueryBulanan, 'monthly_totals')->avg('monthly_total') ?? 0;


            // Pesanan Berjalan (status bukan lunas)
            $data['pesananBerjalan'] = Transaksi::where('status', '!=', 'lunas')->count();

            $data['totalKategori'] = Kategori::count();
            $data['totalProduk'] = Produk::count();

            // Top Produk All Time
            $data['topProdukAllTime'] = DB::table('detail_transaksis')
                ->join('transaksis', 'detail_transaksis.transaksi_id', '=', 'transaksis.id')
                ->join('produks', 'detail_transaksis.produk_id', '=', 'produks.id')
                ->where('transaksis.status', 'lunas')
                ->select('produks.id', 'produks.nama', DB::raw('SUM(detail_transaksis.kuantitas) as jumlah'))
                ->groupBy('produks.id', 'produks.nama')
                ->orderByDesc('jumlah')
                ->limit(100)
                ->get();

            // Top Produk Bulanan
            $data['topProdukBulan'] = DB::table('detail_transaksis')
                ->join('transaksis', 'detail_transaksis.transaksi_id', '=', 'transaksis.id')
                ->join('produks', 'detail_transaksis.produk_id', '=', 'produks.id')
                ->where('transaksis.status', 'lunas')
                ->whereMonth('transaksis.tanggal_pembayaran', now()->month)
                ->whereYear('transaksis.tanggal_pembayaran', now()->year)
                ->select('produks.id', 'produks.nama', DB::raw('SUM(detail_transaksis.kuantitas) as jumlah'))
                ->groupBy('produks.id', 'produks.nama')
                ->orderByDesc('jumlah')
                ->limit(100)
                ->get();

            // Produk Tidak Laku
            $data['produkTidakLaku'] = Produk::whereNotIn('id', function ($query) {
                $query->select('detail_transaksis.produk_id')
                    ->from('detail_transaksis')
                    ->join('transaksis', 'detail_transaksis.transaksi_id', '=', 'transaksis.id')
                    ->where('transaksis.status', 'lunas');
            })->get();

            // Chart Data
            $chartData = [
                'harian' => ['labels' => [], 'data' => []],
                'bulanan' => ['labels' => [], 'data' => []],
                'tahunan' => ['labels' => [], 'data' => []],
            ];

            // Harian (7 hari terakhir)
            for ($i = 6; $i >= 0; $i--) {
                $tanggal = today()->subDays($i);
                $total = Transaksi::where('status', 'lunas')
                    ->whereDate('tanggal_pembayaran', $tanggal)
                    ->sum('total_harga');
                $chartData['harian']['labels'][] = $tanggal->format('D, d M');
                $chartData['harian']['data'][] = $total;
            }

            // Bulanan (12 bulan)
            for ($i = 11; $i >= 0; $i--) {
                $bulan = now()->subMonths($i);
                $total = Transaksi::where('status', 'lunas')
                    ->whereMonth('tanggal_pembayaran', $bulan->month)
                    ->whereYear('tanggal_pembayaran', $bulan->year)
                    ->sum('total_harga');
                $chartData['bulanan']['labels'][] = $bulan->format('M Y');
                $chartData['bulanan']['data'][] = $total;
            }

            // Tahunan (5 tahun)
            for ($i = 4; $i >= 0; $i--) {
                $tahun = now()->subYears($i)->year;
                $total = Transaksi::where('status', 'lunas')
                    ->whereYear('tanggal_pembayaran', $tahun)
                    ->sum('total_harga');
                $chartData['tahunan']['labels'][] = $tahun;
                $chartData['tahunan']['data'][] = $total;
            }

            $data['chartData'] = $chartData;
        }

        return view($view, $data);
    }
}