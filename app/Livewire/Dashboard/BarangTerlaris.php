<?php

namespace App\Livewire\Dashboard;

use App\Models\SaleItem;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class BarangTerlaris extends Component
{
    public string $period = 'monthly';

    // ✅ Fix: method biasa, bukan computed property
    public function getChartData(): array
    {
        $tokoId = Auth::user()->effective_toko_id;

        $items = SaleItem::whereHas('sale', function ($q) use ($tokoId) {
            $q->where('toko_id', $tokoId)
                ->where('status', 'selesai')
                ->byPeriod($this->period);
        })
            ->with('barang:id,nama_barang')
            ->selectRaw('barang_id, SUM(jumlah) as total_terjual')
            ->groupBy('barang_id')
            ->orderByDesc('total_terjual')
            ->limit(7)
            ->get();

        return [
            'labels' => $items->map(fn($i) => $i->barang->nama_barang ?? 'Unknown')->toArray(),
            'data'   => $items->map(fn($i) => (int) $i->total_terjual)->toArray(),
            'colors' => $this->generateColors(count($items)),
        ];
    }

    private function generateColors(int $count): array
    {
        $palette = [
            '#f97316',
            '#3b82f6',
            '#10b981',
            '#f59e0b',
            '#8b5cf6',
            '#ec4899',
            '#06b6d4',
        ];

        return array_slice($palette, 0, $count);
    }

    public function updatedPeriod(): void
    {
        // ✅ Fix: pakai method biasa
        $this->dispatch('barangTerlarisUpdated', data: $this->getChartData());
    }

    public function render()
    {
        return view('livewire.dashboard.barang-terlaris', [
            'chartData' => $this->getChartData(),
        ]);
    }
}
