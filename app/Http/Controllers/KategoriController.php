<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

class KategoriController extends Controller
{
    /**
     * Menampilkan halaman daftar kategori.
     */
    public function index(Request $request): View
    {
        $search = $request->query('search');

        $kategoris = Kategori::when($search, function($q) use ($search){
                $q->where('nama','like','%'.$search.'%');
            })
            ->latest()
            ->paginate(9);

        return view('pages-admin.category.index', compact('kategoris'));
    }

    /**
     * Menyimpan kategori baru ke database.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255|unique:kategoris',
            'status' => ['required', Rule::in(['aktif', 'nonaktif'])],
        ]);

        Kategori::create($validated);

        return redirect()->route('categories.index')->with('success', 'Kategori baru berhasil ditambahkan.');
    }

    /**
     * Memperbarui data kategori yang ada (PENDEKATAN MANUAL).
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $kategori = Kategori::findOrFail($id);

        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255', Rule::unique('kategoris')->ignore($kategori->id)],
            'status' => ['required', Rule::in(['aktif', 'nonaktif'])],
        ]);

        $kategori->update($validated);
        
        return redirect()->route('categories.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    /**
     * Menghapus data kategori (PENDEKATAN MANUAL).
     */
    public function destroy(string $id): RedirectResponse
    {
        $kategori = Kategori::findOrFail($id);

        $kategori->delete();

        return back()->with('success', 'Kategori berhasil dihapus.');
    }
}