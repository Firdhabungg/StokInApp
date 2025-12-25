<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Toko;
use App\Models\User;
use App\Models\Barang;
use App\Models\Sale;
use Illuminate\Http\Request;

class AdminTokoController extends Controller
{
    /**
     * Display a listing of all tokos.
     */
    public function index()
    {
        $tokos = Toko::withCount(['users', 'barangs'])->get();

        // Get summary stats
        $totalToko = $tokos->count();
        $totalUsers = User::whereNotNull('toko_id')->count();
        $totalBarang = Barang::count();

        return view('admin.toko.index', compact('tokos', 'totalToko', 'totalUsers', 'totalBarang'));
    }

    /**
     * Display the specified toko.
     */
    public function show(Toko $toko)
    {
        $toko->load(['users', 'barangs']);
        
        // Get toko statistics
        $totalStok = $toko->barangs->sum('stok');
        $totalPenjualan = Sale::where('toko_id', $toko->id)->where('status', 'selesai')->sum('total');
        $totalTransaksi = Sale::where('toko_id', $toko->id)->count();

        return view('admin.toko.show', compact('toko', 'totalStok', 'totalPenjualan', 'totalTransaksi'));
    }
}
