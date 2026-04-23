<?php

namespace App\Livewire\Laporan;

use App\Models\Sale;
use App\Models\SaleItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Laporan Penjualan')]
#[Layout('layouts.dashboard')]
class LaporanPenjualan extends Component
{
    public string $filter  = 'harian';
    public string $tanggal = '';
    public string $bulan   = '';

    public bool $canExportReport = false;

    // Computed display properties
    public string  $labelPeriode   = '';
    public float   $totalPenjualan = 0;
    public int     $totalTransaksi = 0;
    public float   $rataRata       = 0;

    protected $queryString = [
        'filter'  => ['except' => 'harian'],
        'tanggal' => ['except' => ''],
        'bulan'   => ['except' => ''],
    ];

    public function mount(): void
    {
        $this->tanggal = $this->tanggal ?: now()->toDateString();
        $this->bulan   = $this->bulan   ?: now()->format('Y-m');

        $toko = Auth::user()->toko;
        $this->canExportReport = $toko ? $toko->canExportReport() : false;
    }

    public function updatedFilter(): void
    {
        $this->resetPage();
    }

    public function updatedTanggal(): void
    {
        $this->resetPage();
    }

    public function updatedBulan(): void
    {
        $this->resetPage();
    }

    private function buildQuery()
    {
        $tokoId = Auth::user()->effective_toko_id;

        $query = Sale::with(['items.barang', 'user'])
            ->where('toko_id', $tokoId)
            ->where('status', 'selesai');

        if ($this->filter === 'harian') {
            $query->whereDate('tanggal', $this->tanggal);
            $this->labelPeriode = Carbon::parse($this->tanggal)->format('d F Y');
        } else {
            $query->whereYear('tanggal', substr($this->bulan, 0, 4))
                ->whereMonth('tanggal', substr($this->bulan, 5, 2));
            $this->labelPeriode = Carbon::parse($this->bulan . '-01')->format('F Y');
        }

        return $query;
    }

    public function exportExcel(): void
    {
        if (! $this->canExportReport) {
            $this->dispatch('export-denied', message: 'Fitur export tidak tersedia untuk paket langganan Anda. Silakan upgrade ke paket Pro atau Business.');
            return;
        }

        $this->dispatch('open-url', url: route('laporan.penjualan.export.excel', [
            'filter'  => $this->filter,
            'tanggal' => $this->tanggal,
            'bulan'   => $this->bulan,
        ]));
    }

    public function exportPdf(): void
    {
        if (! $this->canExportReport) {
            $this->dispatch('export-denied', message: 'Fitur export tidak tersedia untuk paket langganan Anda. Silakan upgrade ke paket Pro atau Business.');
            return;
        }

        $this->dispatch('open-url', url: route('laporan.penjualan.export.pdf', [
            'filter'  => $this->filter,
            'tanggal' => $this->tanggal,
            'bulan'   => $this->bulan,
        ]));
    }

    public function render()
    {
        $tokoId = Auth::user()->effective_toko_id;

        $sales = $this->buildQuery()->orderBy('tanggal', 'desc')->get();

        $this->totalPenjualan = $sales->sum('total');
        $this->totalTransaksi = $sales->count();
        $this->rataRata       = $this->totalTransaksi > 0 ? $this->totalPenjualan / $this->totalTransaksi : 0;

        $topItems = SaleItem::whereHas('sale', function ($q) use ($tokoId) {
            $q->where('toko_id', $tokoId)->where('status', 'selesai');
            if ($this->filter === 'harian') {
                $q->whereDate('tanggal', $this->tanggal);
            } else {
                $q->whereYear('tanggal', substr($this->bulan, 0, 4))
                    ->whereMonth('tanggal', substr($this->bulan, 5, 2));
            }
        })
            ->with('barang')
            ->select('barang_id', DB::raw('SUM(jumlah) as total_qty'), DB::raw('SUM(subtotal) as total_nilai'))
            ->groupBy('barang_id')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        return view('livewire.laporan.laporan-penjualan', compact(
            'sales',
            'topItems'
        ));
    }
}
