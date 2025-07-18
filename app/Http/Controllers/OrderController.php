<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Pelanggan;
use App\Models\Produk;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    /**
     * Menampilkan halaman utama order dengan daftar produk.
     */
    public function index(): View
    {
        $kategoris = Kategori::where('status', 'aktif')
            ->with(['produks' => function ($query) {
                $query->where('status', 'aktif')->orderBy('nama');
            }])
            ->orderBy('nama')
            ->get();
            
        return view('pages.orders.orders', compact('kategoris'));
    }

    /**
     * Memvalidasi, memproses, dan menyimpan transaksi baru ke database.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'cart' => 'required|json',
            'customer_details' => 'required|json',
        ]);

        $cartItems = json_decode($request->cart, true);
        $customerDetails = json_decode($request->customer_details, true);

        if (empty($cartItems)) {
            return back()->with('error', 'Keranjang tidak boleh kosong.');
        }

        // Validasi kombinasi input pelanggan sesuai logika yang diperbolehkan
        $nama = $customerDetails['nama_pelanggan'] ?? null;
        $telepon = $customerDetails['telepon'] ?? null;
        $email = $customerDetails['email'] ?? null;
        $no_table = $customerDetails['no_table'] ?? null;

        if (empty($nama) && empty($no_table)) {
            return back()->with('error', 'Harap isi nama pelanggan atau nomor meja.');
        }

        if ((empty($nama) && (!empty($telepon) || !empty($email))) && empty($no_table)) {
            return back()->with('error', 'Nama pelanggan wajib diisi jika ingin mengisi nomor telepon atau email.');
        }

        DB::beginTransaction();
        try {
            $pelangganId = null;
            if (!empty($nama)) {
                $pelanggan = Pelanggan::Create(
                    ['nama' => $nama],
                    [
                        'telepon' => $telepon,
                        'email' => $email,
                    ]
                );
                $pelangganId = $pelanggan->id;
            }

            $subtotal = 0;
            $productIds = array_column($cartItems, 'id');
            $productsInDB = Produk::find($productIds)->keyBy('id');

            foreach ($cartItems as $item) {
                $subtotal += $productsInDB[$item['id']]->harga * $item['quantity'];
            }

            $pajak = $subtotal * 0.11;
            $totalHarga = $subtotal + $pajak;

            $transaksi = Transaksi::create([
                'user_id' => Auth::id(),
                'pelanggan_id' => $pelangganId,
                'no_table' => $no_table,
                'nomor_transaksi' => 'INV-' . now()->format('Ymd-His'),
                'total_harga' => $totalHarga,
                'pajak' => $pajak,
                'status' => 'menunggu',
                'tanggal_transaksi' => now(),
            ]);

            foreach ($cartItems as $item) {
                $hargaProduk = $productsInDB[$item['id']]->harga;
                $transaksi->details()->create([
                    'produk_id' => $item['id'],
                    'kuantitas' => $item['quantity'],
                    'harga_saat_transaksi' => $hargaProduk,
                    'subtotal' => $hargaProduk * $item['quantity'],
                    'catatan' => $item['catatan'] ?? null,
                ]);
            }

            DB::commit();

            return redirect()->route('orders.index')->with('success', 'Transaksi berhasil dibuat dengan nomor ' . $transaksi->nomor_transaksi);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat memproses transaksi: ' . $e->getMessage());
        }
    }
}
