<?php

namespace App\Http\Controllers;

use App\Models\KategoriBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tokoId = Auth::user()->toko_id;
        $kategoris = KategoriBarang::where('toko_id', $tokoId)
            ->withCount('barangs')
            ->orderBy('nama_kategori')
            ->get();
        
        return view('kategori.index', compact('kategoris'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('kategori.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $tokoId = Auth::user()->toko_id;
        
        $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:kategoris,nama_kategori,NULL,kategori_id,toko_id,' . $tokoId,
            'deskripsi_kategori' => 'nullable|string|max:255',
        ]);

        KategoriBarang::create([
            'toko_id' => $tokoId,
            'nama_kategori' => $request->nama_kategori,
            'deskripsi_kategori' => $request->deskripsi_kategori,
        ]);

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $tokoId = Auth::user()->toko_id;
        $kategori = KategoriBarang::where('toko_id', $tokoId)->findOrFail($id);
        
        return view('kategori.edit', compact('kategori'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $tokoId = Auth::user()->toko_id;
        $kategori = KategoriBarang::where('toko_id', $tokoId)->findOrFail($id);
        
        $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:kategoris,nama_kategori,' . $id . ',kategori_id,toko_id,' . $tokoId,
            'deskripsi_kategori' => 'nullable|string|max:255',
        ]);

        $kategori->update([
            'nama_kategori' => $request->nama_kategori,
            'deskripsi_kategori' => $request->deskripsi_kategori,
        ]);

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $tokoId = Auth::user()->toko_id;
        $kategori = KategoriBarang::where('toko_id', $tokoId)->findOrFail($id);
        
        // Check if kategori has barangs
        if ($kategori->barangs()->count() > 0) {
            return redirect()->route('kategori.index')
                ->with('error', 'Kategori tidak bisa dihapus karena masih memiliki barang');
        }
        
        $kategori->delete();

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil dihapus');
    }
}
