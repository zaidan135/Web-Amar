<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProdukController extends Controller
{
    /**
     * Menampilkan halaman daftar produk.
     */
    public function index(Request $request): View
    {
        $search = $request->query('search');

        $produks = Produk::with('kategori')
            ->when($search, function($q) use ($search){
                $q->where('nama','like','%'.$search.'%')
                  ->orWhereHas('kategori', function($cat) use ($search){
                     $cat->where('nama','like','%'.$search.'%');
                  });
            })
            ->latest()
            ->paginate(9);

        $kategoris = Kategori::where('status','aktif')->orderBy('nama')->get();

        return view('pages-admin.product.index', compact('produks','kategoris'));
    }

    /**
     * Menyimpan produk baru ke database.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255|unique:produks',
            'kategori_id' => 'required|exists:kategoris,id',
            'harga' => 'required|numeric|min:0',
            'status' => ['required', Rule::in(['aktif', 'nonaktif'])],
        ]);

        Produk::create($validated);

        return redirect()->route('products.index')->with('success', 'Produk baru berhasil ditambahkan.');
    }

    /**
     * Memperbarui data produk yang ada (Pendekatan Manual).
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $produk = Produk::findOrFail($id);

        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255', Rule::unique('produks')->ignore($produk->id)],
            'kategori_id' => 'required|exists:kategoris,id',
            'harga' => 'required|numeric|min:0',
            'status' => ['required', Rule::in(['aktif', 'nonaktif'])],
        ]);

        $produk->update($validated);

        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Menghapus data produk (Pendekatan Manual).
     */
    public function destroy(string $id): RedirectResponse
    {
        $produk = Produk::findOrFail($id);
        $produk->delete();

        return back()->with('success', 'Produk berhasil dihapus.');
    }
}