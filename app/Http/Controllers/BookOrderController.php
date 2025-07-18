<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class BookOrderController extends Controller
{
    /**
     * Menampilkan halaman riwayat pesanan (Book Order).
     */
    public function index(Request $request): View
    {
        $query = Transaksi::with(['user', 'pelanggan', 'details.produk'])
            ->where('status', 'menunggu');

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->Where('no_table', 'like', "%{$search}%")
                  ->orWhereHas('pelanggan', function ($q2) use ($search) {
                      $q2->where('nama', 'like', "%{$search}%");
                  });
            });
        }

        $transaksis = $query->latest()->paginate(9);

        return view('pages.books.books', compact('transaksis'));
    }


    /**
     * Mengubah status transaksi menjadi 'lunas'.
     */
    public function pay(Request $request, Transaksi $transaksi): JsonResponse
    {
        if ($transaksi->status === 'menunggu') {
            $transaksi->update([
                'status' => 'lunas',
                'tanggal_pembayaran' => now(),
            ]);
        }
        
        $transaksi->loadMissing(['user', 'pelanggan', 'details.produk']);

        $user = $request->user()->fresh();

        return response()->json([
            'message'   => "Transaksi {$transaksi->nomor_transaksi} berhasil dibayar!",
            'mode'      => $user->print_mode ?? 'pdf',
            'printer'   => $user->printer_name,
            'pdfUrl'    => route('receipt.pdf', ['transaksi' => $transaksi->id]),
            'raw'       => view('print.escpos', ['trx' => $transaksi])->render(),
            'redirect'  => route('book-order.index')
        ]);
    }

    public function receiptPdf(Transaksi $transaksi)
    {
        $transaksi->loadMissing(['user', 'pelanggan', 'details.produk']);

        $customPaper = [0, 0, 255.11, 500];
        
        $pdf = Pdf::loadView('print.pdf', ['trx' => $transaksi])->setPaper($customPaper);
        
        return $pdf->stream("receipt-{$transaksi->nomor_transaksi}.pdf");
    }
}