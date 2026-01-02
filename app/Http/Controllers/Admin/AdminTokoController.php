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
        $tokos = Toko::withCount(['users', 'barangs'])
            ->with(['activeSubscription.plan'])
            ->get();

        // Get summary stats
        $totalToko = $tokos->count();
        $totalUsers = User::whereNotNull('toko_id')->count();
        $totalBarang = Barang::count();
        
        // Subscription stats
        $tokoAktif = $tokos->filter(fn($t) => $t->activeSubscription)->count();
        $tokoTrial = $tokos->filter(fn($t) => $t->activeSubscription && $t->activeSubscription->status === 'trial')->count();
        $tokoExpired = $totalToko - $tokoAktif;

        return view('admin.toko.index', compact(
            'tokos', 'totalToko', 'totalUsers', 'totalBarang',
            'tokoAktif', 'tokoTrial', 'tokoExpired'
        ));
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
