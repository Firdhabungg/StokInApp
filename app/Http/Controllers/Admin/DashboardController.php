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
        /*
        |--------------------------------------------------------------------------
        | SUMMARY (TIDAK DIUBAH)
        |--------------------------------------------------------------------------
        */
        $totalProduk = Barang::count();
        $totalToko   = Toko::count();
        $stokMenipis = Barang::where('status', 'menipis')->count();

        /*
        |--------------------------------------------------------------------------
        | OMZET PLATFORM (DIBENAHI – DARI SUBSCRIPTION)
        |--------------------------------------------------------------------------
        */

        // TOTAL OMZET = semua subscription aktif & berbayar
        $omzetTotal = Subscription::where('status', 'active')
            ->whereHas('plan', function ($q) {
                $q->where('price', '>', 0)
                  ->where('is_active', true);
            })
            ->with('plan:id,price')
            ->get()
            ->sum(fn ($s) => $s->plan->price ?? 0);

        // FORMAT RUPIAH (AMAN, TANPA HELPER)
        $omzetFormatted = 'Rp ' . number_format($omzetTotal, 0, ',', '.');

        // STATUS OMZET (TEKS)
        if ($omzetTotal >= 5_000_000) {
            $omzetStatus = 'Sangat Baik';
        } elseif ($omzetTotal >= 1_000_000) {
            $omzetStatus = 'Naik Signifikan';
        } elseif ($omzetTotal > 0) {
            $omzetStatus = 'Naik';
        } else {
            $omzetStatus = 'Belum Ada Pendapatan';
        }

        /*
|--------------------------------------------------------------------------
| GRAFIK PENDAPATAN BULANAN (SAAS)
|--------------------------------------------------------------------------
*/

$monthlyRevenue = Subscription::where('status', 'active')
    ->whereHas('plan', function ($q) {
        $q->where('price', '>', 0)
          ->where('is_active', true);
    })
    ->with('plan:id,price')
    ->get()
    ->groupBy(function ($sub) {
        return $sub->starts_at->format('Y-m');
    })
    ->map(function ($subs) {
        return $subs->sum(fn ($s) => $s->plan->price ?? 0);
    });

// ambil 12 bulan terakhir
$growthLabels = [];
$growthData   = [];

for ($i = 11; $i >= 0; $i--) {
    $monthKey = now()->subMonths($i)->format('Y-m');
    $growthLabels[] = now()->subMonths($i)->format('M Y');
    $growthData[]   = $monthlyRevenue[$monthKey] ?? 0;
}


        /*
        |--------------------------------------------------------------------------
        | DISTRIBUSI PAKET (TIDAK DIUBAH)
        |--------------------------------------------------------------------------
        */
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

        /*
        |--------------------------------------------------------------------------
        | INSIGHT PLATFORM (TIDAK DIUBAH)
        |--------------------------------------------------------------------------
        */
        $tokoTidakAktif = Toko::where('updated_at', '<=', now()->subDays(14))->count();

        $langgananHampirHabis = Subscription::where('status', 'active')
            ->whereDate('expires_at', '<=', now()->addDays(3))
            ->count();

        $trialAktif = Subscription::where('status', 'trial')
            ->where('expires_at', '>', now())
            ->count();

        return view('admin.dashboard.index', compact(
            'totalProduk',
            'totalToko',
            'stokMenipis',
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
