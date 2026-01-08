<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Toko;
use App\Models\Subscription;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | 1. SUMMARY STATISTICS
        |--------------------------------------------------------------------------
        */
        // Invoice Pending (pembayaran yang belum selesai)
        $invoicePending = Payment::where('status', 'pending')->count();
        
        $totalToko   = Toko::count();

        /*
        |--------------------------------------------------------------------------
        | 2. OMZET PLATFORM (SUBSCRIPTION BERBAYAR AKTIF)
        |--------------------------------------------------------------------------
        */
        $omzetTotal = Subscription::where('status', 'active')
            ->whereHas('plan', function ($q) {
                $q->where('price', '>', 0)->where('is_active', true);
            })
            ->with('plan:id,price')
            ->get()
            ->sum(fn($s) => $s->plan->price ?? 0);

        $omzetFormatted = 'Rp ' . number_format($omzetTotal, 0, ',', '.');

        // Status Omzet berdasarkan target
        if ($omzetTotal >= 5000000) {
            $omzetStatus = 'Sangat Baik';
        } elseif ($omzetTotal >= 1000000) {
            $omzetStatus = 'Naik Signifikan';
        } elseif ($omzetTotal > 0) {
            $omzetStatus = 'Naik';
        } else {
            $omzetStatus = 'Belum Ada Pendapatan';
        }

        /*
        |--------------------------------------------------------------------------
        | 3. GRAFIK PENDAPATAN BULANAN (12 BULAN TERAKHIR)
        |--------------------------------------------------------------------------
        */
        $monthlyRevenue = Subscription::where('status', 'active')
            ->whereHas('plan', function ($q) {
                $q->where('price', '>', 0);
            })
            ->get()
            ->groupBy(fn($sub) => $sub->starts_at->format('Y-m'))
            ->map(fn($subs) => $subs->sum(fn($s) => $s->plan->price ?? 0));

        $growthLabels = [];
        $growthData   = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthKey = $date->format('Y-m');
            $growthLabels[] = $date->format('M Y');
            $growthData[]   = $monthlyRevenue[$monthKey] ?? 0;
        }

        /*
        |--------------------------------------------------------------------------
        | DISTRIBUSI PAKET (SINKRONISASI 100% DENGAN DATABASE)
        |--------------------------------------------------------------------------
        */
        // Ambil ID terbaru untuk setiap toko
        $latestIds = Subscription::selectRaw('MAX(id)')->groupBy('toko_id')->pluck('MAX(id)');
        
        // Ambil data berdasarkan status
        $paket = Subscription::whereIn('id', $latestIds)
            ->selectRaw("
                CASE 
                    WHEN status = 'active' THEN 'Pro' 
                    WHEN status = 'trial' THEN 'Trial' 
                    ELSE 'Lainnya' 
                END as plan_name, 
                COUNT(*) as total
            ")
            ->groupBy('plan_name')
            ->get();
        
        $paketLabels = $paket->pluck('plan_name');
        $paketData   = $paket->pluck('total');
        
        // Hitung Toko yang Belum Berlangganan (Total Toko - Toko dengan status Pro/Trial)
        $tokoTerhitungCount = Subscription::whereIn('id', $latestIds)
            ->whereIn('status', ['active', 'trial'])
            ->count();
        
        $totalTokoReal = Toko::count();
        $tokoBelumLangganan = $totalTokoReal - $tokoTerhitungCount;
        
        if ($tokoBelumLangganan > 0) {
            $paketLabels->push('Belum Langganan');
            $paketData->push($tokoBelumLangganan);
        }
        
        $totalPaket = $paketData->sum();

        /*
        |--------------------------------------------------------------------------
        | 5. INSIGHT & ALERT PLATFORM
        |--------------------------------------------------------------------------
        */
        // Toko yang tidak update data selama 2 minggu
        $tokoTidakAktif = Toko::where('updated_at', '<=', now()->subDays(14))->count();

        // Langganan Pro yang akan habis dalam 3 hari
        $langgananHampirHabis = Subscription::where('status', 'active')
            ->whereDate('expires_at', '<=', now()->addDays(3))
            ->whereDate('expires_at', '>', now())
            ->count();

        // Akun yang masih dalam masa Trial
        $trialAktif = Subscription::where('status', 'trial')
            ->where('expires_at', '>', now())
            ->count();

        return view('admin.dashboard.index', compact(
            'invoicePending',
            'totalToko',
            'omzetFormatted',
            'omzetStatus',
            'growthLabels',
            'growthData',
            'paketLabels',
            'paketData',
            'totalPaket',
            'tokoTidakAktif',
            'langgananHampirHabis',
            'trialAktif'
        ));
    }
}