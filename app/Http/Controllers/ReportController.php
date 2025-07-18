<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    /**
     * Menampilkan halaman laporan penjualan.
     */
    public function index(Request $request): View
    {
        $search = $request->query('search');
    
        $query = Transaksi::with(['user', 'pelanggan', 'details.produk'])
            ->where('status', 'lunas')
            ->whereNotNull('tanggal_pembayaran');
    
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nomor_transaksi', 'like', "%{$search}%")
                  ->orWhere('no_table', 'like', "%{$search}%")
                  ->orWhereHas('pelanggan', function ($q2) use ($search) {
                      $q2->where('nama', 'like', "%{$search}%");
                  })
                  ->orWhereRaw("DATE_FORMAT(tanggal_pembayaran, '%d %M %Y') LIKE ?", ["%$search%"])
                  ->orWhereRaw("DATE_FORMAT(tanggal_pembayaran, '%Y-%m-%d') LIKE ?", ["%$search%"]);
            });
        }
    
        $laporanTransaksis = $query->latest('tanggal_pembayaran')->paginate(9);
    
        return view('pages.reports.reports', [
            'laporanTransaksis' => $laporanTransaksis
        ]);
    }

}