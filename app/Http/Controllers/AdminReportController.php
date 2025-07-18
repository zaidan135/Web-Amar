<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Transaksi;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminReportController extends Controller
{
public function index(Request $r): View
{
    $search = $r->query('search');
    $range  = $r->query('range','today');   // today|month|year|custom
    $query  = Transaksi::with(['user','pelanggan','details.produk'])
              ->where('status','lunas')
              ->whereNotNull('tanggal_pembayaran');

    /* ------- FILTER RANGE ------- */
    if ($range==='today') {
        $query->whereDate('tanggal_pembayaran', today());
    }
    elseif ($range==='month') {
        $query->whereMonth('tanggal_pembayaran', now()->month)
              ->whereYear ('tanggal_pembayaran', now()->year);
    }
    elseif ($range==='year') {
        $query->whereYear('tanggal_pembayaran', now()->year);
    }

$search = trim($search);

if ($search) {

    /* 1. KHUSUS pola "meja 01" atau "meja01" */
    if (preg_match('/^meja\s*([0-9]+)$/i', $search, $m)) {
        // $m[1] berisi angka meja → cari persis / like
        $query->where('no_table', 'like', '%' . $m[1] . '%');
    }

    /* 2. POLA UMUM (tanpa kata 'meja') */
    else {
        $query->where(function ($q) use ($search) {
            $like = '%' . $search . '%';

            $q->where('nomor_transaksi', 'like', $like)
              ->orWhere('no_table',        'like', $like)
              ->orWhereHas('pelanggan', fn($p) =>
                    $p->where('nama', 'like', $like))
              ->orWhereHas('user',      fn($u) =>
                    $u->where('name', 'like', $like));
        });
    }
}



    $reports = $query->latest('tanggal_pembayaran')->paginate(10)
                     ->withQueryString();

    /* ----- KARTU TOTAL ----- */
    $totalToday = Transaksi::where('status','lunas')
                   ->whereDate('tanggal_pembayaran',today())->sum('total_harga');

    $totalMonth = Transaksi::where('status','lunas')
                   ->whereMonth('tanggal_pembayaran',now()->month)
                   ->whereYear('tanggal_pembayaran',now()->year)->sum('total_harga');

    $totalYear  = Transaksi::where('status','lunas')
                   ->whereYear('tanggal_pembayaran',now()->year)->sum('total_harga');

    return view('pages-admin.report.index', compact(
        'reports','totalToday','totalMonth','totalYear','range','search'
    ));
}

public function print(Request $r)
{
    /* ---------- CETAK SATU TRANSAKSI ---------- */
    if ($id = $r->query('single')) {
        $trx = Transaksi::with(['user','pelanggan','details.produk'])
                ->whereKey($id)->where('status','lunas')->firstOrFail();

        $title = 'Laporan ' . $trx->nomor_transaksi;   // ← judul khusus

        $pdf = Pdf::loadView('pdf.transaction', compact('trx','title'));
        return $pdf->stream("trx-{$trx->nomor_transaksi}.pdf");
    }

    /* ---------- CETAK LAPORAN RENTANG ---------- */
    $range  = $r->query('range','today');
    $search = $r->query('search');
    $query  = Transaksi::with(['user','pelanggan'])
                ->where('status','lunas')
                ->whereNotNull('tanggal_pembayaran');

    // filter range
    if ($range==='today') {
        $query->whereDate('tanggal_pembayaran', today());
    } elseif ($range==='month') {
        $query->whereMonth('tanggal_pembayaran', now()->month)
              ->whereYear ('tanggal_pembayaran', now()->year);
    } elseif ($range==='year') {
        $query->whereYear('tanggal_pembayaran', now()->year);
    } elseif ($range==='custom') {
        $from = Carbon::parse($r->query('date_from'))->startOfDay();
        $to   = Carbon::parse($r->query('date_to'))->endOfDay();
        $query->whereBetween('tanggal_pembayaran', [$from,$to]);
    }

    // filter search (sama persis seperti index)
    if ($search) {
        $query->where(function ($q) use ($search) {
            $like = "%{$search}%";

            $q->where('nomor_transaksi', 'like', $like)
              ->orWhere('no_table',        'like', $like)
              ->orWhereHas('pelanggan', function ($p) use ($like) {
                    $p->where('nama', 'like', $like);
              })
              ->orWhereHas('user', function ($u) use ($like) {
                    $u->where('name', 'like', $like);
              });
        });
    }

    $rows = $query->orderBy('tanggal_pembayaran')->get();

    // total rekap
    $grandTotal = $rows->sum('total_harga');

    switch ($range) {
        case 'today':
            $title = 'Laporan Harian';
            break;
        case 'month':
            $title = 'Laporan Bulanan';
            break;
        case 'year':
            $title = 'Laporan Tahunan';
            break;
        case 'custom':
            $from = Carbon::parse($r->query('date_from'))->format('d M Y');
            $to   = Carbon::parse($r->query('date_to'))  ->format('d M Y');
            $title = "Laporan $from – $to";
            break;
        default:
            $title = 'Laporan Penjualan';
    }

$pdf = Pdf::loadView('pdf.report', compact('rows','grandTotal','title'));
    
        return $pdf->stream('laporan-penjualan.pdf');
    }
}
