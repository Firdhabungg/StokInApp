<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Toko;
use App\Models\Sale;
use App\Models\Subscription;

class DashboardController extends Controller
{
    public function index()
    {
        // ================= SUMMARY =================
        $totalProduk = Barang::count();
        $totalToko   = Toko::count();
        $stokMenipis = Barang::where('status', 'menipis')->count();
        $pertumbuhanOmzet = 0;

        // ================= GRAFIK (TIDAK DIUBAH) =================
        $sales = Sale::selectRaw('DAYOFWEEK(created_at) as day, COUNT(*) as total')
            ->groupBy('day')
            ->pluck('total', 'day');

        $dayMap = [
            2 => 'Sen', 3 => 'Sel', 4 => 'Rab',
            5 => 'Kam', 6 => 'Jum', 7 => 'Sab', 1 => 'Min',
        ];

        $growthLabels = [];
        $growthData   = [];

        foreach ($dayMap as $day => $label) {
            $growthLabels[] = $label;
            $growthData[]   = $sales[$day] ?? 0;
        }

        // ================= DISTRIBUSI PAKET =================
        $paket = Subscription::whereIn('status', ['trial', 'active'])
            ->selectRaw("
                CASE
                    WHEN plan_id = 1 THEN 'Trial'
                    WHEN plan_id = 2 THEN 'Pro'
                    ELSE 'Lainnya'
                END as plan_name,
                COUNT(*) as total
            ")
            ->groupBy('plan_name')
            ->orderBy('plan_name')
            ->get();

        $paketLabels = $paket->pluck('plan_name');
        $paketData   = $paket->pluck('total');
        $totalPaket  = $paketData->sum();

        // ================= LOG AKTIVITAS (AMBIL 20 DATA) =================
        $activityLogs = Sale::with(['toko', 'user'])
            ->latest()
            ->take(20)
            ->get();

        return view('admin.dashboard.index', compact(
            'totalProduk',
            'totalToko',
            'stokMenipis',
            'pertumbuhanOmzet',
            'growthLabels',
            'growthData',
            'paketLabels',
            'paketData',
            'totalPaket',
            'activityLogs'
        ));
    }
}
