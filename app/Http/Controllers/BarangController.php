<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\KategoriBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BarangController extends Controller
{
    public function index()
    {
        $tokoId = Auth::user()->toko_id;
        $barangs = Barang::where('toko_id', $tokoId)
            ->with('kategori')
            ->orderBy('nama_barang')
            ->get();
        return view('barang.index', compact('barangs'));
    }

    public function create()
    {
        $tokoId = Auth::user()->toko_id;
        $kategoris = KategoriBarang::where('toko_id', $tokoId)->get();
        
        // Auto-generate kode barang
        $kodeBarang = $this->generateKodeBarang($tokoId);
        
        return view('barang.create', compact('kategoris', 'kodeBarang'));
    }

    /**
     * Generate unique kode barang for toko.
     * Format: BRG-XXXXX (5 digit angka)
     */
    private function generateKodeBarang($tokoId)
    {
        $lastBarang = Barang::where('toko_id', $tokoId)
            ->where('kode_barang', 'like', 'BRG-%')
            ->orderBy('kode_barang', 'desc')
            ->first();

        if ($lastBarang) {
            // Extract number from last kode
            $lastNumber = (int) str_replace('BRG-', '', $lastBarang->kode_barang);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return 'BRG-' . str_pad($newNumber, 5, '0', STR_PAD_LEFT);
    }

    public function store(Request $request)
    {
        $tokoId = Auth::user()->toko_id;
        
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'kategori_id' => 'required|integer|exists:kategoris,kategori_id',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'tgl_kadaluwarsa' => 'nullable|date|after:today'
        ]);
        
        // Generate kode barang jika tidak diinput atau kosong
        $kodeBarang = $request->kode_barang;
        if (empty($kodeBarang)) {
            $kodeBarang = $this->generateKodeBarang($tokoId);
        }
        
        // Tentukan status berdasarkan stok
        $status = 'habis';
        if ($request->stok > 10) {
            $status = 'tersedia';
        } elseif ($request->stok > 0) {
            $status = 'menipis';
        }
        
        $hargaJual = $request->harga * 1.2;
        
        Barang::create([
            'toko_id' => $tokoId,
            'nama_barang' => $request->nama_barang,
            'kode_barang' => $kodeBarang,
            'kategori_id' => $request->kategori_id,
            'harga' => $request->harga,
            'harga_jual' => $hargaJual,
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
        $tokoId = Auth::user()->toko_id;
        $barang = Barang::where('toko_id', $tokoId)->findOrFail($id);
        $kategoris = KategoriBarang::where('toko_id', $tokoId)->get();
        return view('barang.edit', compact('barang', 'kategoris'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $tokoId = Auth::user()->toko_id;
        $barang = Barang::where('toko_id', $tokoId)->findOrFail($id);
        
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'kode_barang' => 'required|string|max:50|unique:barangs,kode_barang,' . $id . ',id,toko_id,' . $tokoId,
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
        $tokoId = Auth::user()->toko_id;
        $barang = Barang::where('toko_id', $tokoId)->findOrFail($id);
        $barang->delete();

        return redirect()->route('barang.index')->with('success', 'Barang berhasil dihapus');
    }
}
