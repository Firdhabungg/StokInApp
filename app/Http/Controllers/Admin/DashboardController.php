<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Toko;
use App\Models\Subscription;

class DashboardController extends Controller
{
    public function index()
    {
        // Total produk di seluruh toko
        $totalProduk = Barang::count();
        
        // Total toko terdaftar
        $totalToko = Toko::count();
        
        // Stok menipis (status = 'menipis')
        $stokMenipis = Barang::where('status', 'menipis')->count();
        
        // Hitung pertumbuhan omzet (contoh: perbandingan bulan ini vs bulan lalu)
        // Untuk sementara ini dummy, bisa diimplementasikan nanti
        $pertumbuhanOmzet = 0;
        
        return view('admin.dashboard.index', compact(
            'totalProduk',
            'totalToko',
            'stokMenipis',
            'pertumbuhanOmzet'
        ));
    }
}
