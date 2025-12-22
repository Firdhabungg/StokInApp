<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\KategoriBarang;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $barangs = Barang::all();
        return view('barang.index', compact('barangs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kategoris = KategoriBarang::all();
        return view('barang.create', compact('kategoris'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'kode_barang' => 'required|string|unique:barangs,kode_barang|max:50',
            'kategori_id' => 'required|integer|exists:kategoris,kategori_id',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'tgl_kadaluwarsa' => 'nullable|date|after:today'
        ]);
        // Tentukan status berdasarkan stok
        $status = 'habis';
        if ($request->stok > 10) {
            $status = 'tersedia';
        } elseif ($request->stok > 0) {
            $status = 'menipis';
        }
        Barang::create([
            'nama_barang' => $request->nama_barang,
            'kode_barang' => $request->kode_barang,
            'kategori_id' => $request->kategori_id,
            'harga' => $request->harga,
            'stok' => $request->stok,
            'tgl_kadaluwarsa' => $request->tgl_kadaluwarsa,
            'status' => $status
        ]);

        return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $barang = Barang::findOrFail($id);
        $kategoris = KategoriBarang::all();
        return view('barang.edit', compact('barang', 'kategoris'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'kode_barang' => 'required|string|max:50|unique:barangs,kode_barang,' . $id,
            'kategori_id' => 'required|integer|exists:kategoris,kategori_id',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'tgl_kadaluwarsa' => 'nullable|date|after:today'
        ]);
        $status = 'habis';
        if ($request->stok > 10) {
            $status = 'tersedia';
        } elseif ($request->stok > 0) {
            $status = 'menipis';
        }

        $barang = Barang::findOrFail($id);
        $barang->update([
            'nama_barang' => $request->nama_barang,
            'kode_barang' => $request->kode_barang,
            'kategori_id' => $request->kategori_id,
            'harga' => $request->harga,
            'stok' => $request->stok,
            'tgl_kadaluwarsa' => $request->tgl_kadaluwarsa,
            'status' => $status
        ]);

        return redirect()->route('barang.index')->with('success', 'Barang berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $barang = Barang::findOrFail($id);
        $barang->delete();

        return redirect()->route('barang.index')->with('success', 'Barang berhasil dihapus');
    }
}
