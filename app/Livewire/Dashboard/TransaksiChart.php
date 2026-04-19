<?php

namespace App\Livewire\Dashboard;

use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class TransaksiChart extends Component
{
    public string $period = 'monthly';

    // ✅ Fix 1: Tidak pakai computed property, pakai method biasa
    public function getChartData(): array
    {
        $tokoId = Auth::user()->effective_toko_id;

        // ✅ Fix 2: Query berbeda untuk yearly (group by bulan)
        if ($this->period === 'yearly') {
            $sales = Sale::where('toko_id', $tokoId)
                ->completed()
                ->byPeriod($this->period)
                ->selectRaw('DATE_FORMAT(tanggal, "%Y-%m") as date, COUNT(*) as total_transaksi, SUM(total) as total_penjualan')
                ->groupBy('date')
                ->orderBy('date')
                ->get();
        } else {
            $sales = Sale::where('toko_id', $tokoId)
                ->completed()
                ->byPeriod($this->period)
                ->selectRaw('DATE(tanggal) as date, COUNT(*) as total_transaksi, SUM(total) as total_penjualan')
                ->groupBy('date')
                ->orderBy('date')
                ->get();
        }

        $labels = $this->generateLabels();
        $transaksiData = [];
        $penjualanData = [];

        foreach ($labels as $label => $date) {
            $found = $sales->firstWhere('date', $date);
            $transaksiData[] = $found ? (int) $found->total_transaksi : 0;
            $penjualanData[] = $found ? (float) $found->total_penjualan : 0;
        }

        return [
            'labels'    => array_keys($labels),
            'transaksi' => $transaksiData,
            'penjualan' => $penjualanData,
        ];
    }

    private function generateLabels(): array
    {
        $labels = [];

        match ($this->period) {
            'weekly' => (function () use (&$labels) {
                $start = now()->startOfWeek();
                for ($i = 0; $i < 7; $i++) {
                    $date = $start->copy()->addDays($i);
                    $labels[$date->isoFormat('ddd, D MMM')] = $date->format('Y-m-d');
                }
            })(),

            'monthly' => (function () use (&$labels) {
                $daysInMonth = now()->daysInMonth;
                for ($i = 1; $i <= $daysInMonth; $i++) {
                    $date = now()->copy()->setDay($i);
                    $labels[$date->format('d')] = $date->format('Y-m-d');
                }
            })(),

            'yearly' => (function () use (&$labels) {
                for ($m = 1; $m <= 12; $m++) {
                    $date = Carbon::create(now()->year, $m, 1);
                    $labels[$date->isoFormat('MMM')] = $date->format('Y-m');
                }
            })(),
        };

        return $labels;
    }

    public function updatedPeriod(): void
    {
        // ✅ Fix 3: Dispatch pakai method biasa bukan computed property
        $this->dispatch('transaksiChartUpdated', data: $this->getChartData());
    }

    public function render()
    {
        // ✅ Fix 3: Pass via method
        return view('livewire.dashboard.transaksi-chart', [
            'chartData' => $this->getChartData(),
        ]);
    }
}
