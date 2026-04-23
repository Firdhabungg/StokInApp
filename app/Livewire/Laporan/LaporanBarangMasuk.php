<?php

namespace App\Livewire\Laporan;

use App\Models\StockIn;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Laporan Barang Masuk')]
#[Layout('layouts.dashboard')]
class LaporanBarangMasuk extends Component
{
    use WithPagination;

    public string $dari   = '';
    public string $sampai = '';

    public bool $canExportReport = false;

    protected $paginationTheme = 'tailwind';

    protected $queryString = [
        'dari'   => ['except' => ''],
        'sampai' => ['except' => ''],
    ];

    public function mount(): void
    {
        $this->dari   = $this->dari   ?: now()->startOfMonth()->toDateString();
        $this->sampai = $this->sampai ?: now()->toDateString();

        $toko = Auth::user()->toko;
        $this->canExportReport = $toko ? $toko->canExportReport() : false;
    }

    public function updatedDari(): void
    {
        $this->resetPage();
    }

    public function updatedSampai(): void
    {
        $this->resetPage();
    }

    public function terapkanFilter(): void
    {
        $this->resetPage();
    }

    public function exportExcel(): void
    {
        if (! $this->canExportReport) {
            $this->dispatch('export-denied', message: 'Fitur export tidak tersedia untuk paket langganan Anda. Silakan upgrade ke paket Pro atau Business.');
            return;
        }

        $this->dispatch('open-url', url: route('laporan.barang-masuk.export.excel', [
            'dari'   => $this->dari,
            'sampai' => $this->sampai,
        ]));
    }

    public function exportPdf(): void
    {
        if (! $this->canExportReport) {
            $this->dispatch('export-denied', message: 'Fitur export tidak tersedia untuk paket langganan Anda. Silakan upgrade ke paket Pro atau Business.');
            return;
        }

        $this->dispatch('open-url', url: route('laporan.barang-masuk.export.pdf', [
            'dari'   => $this->dari,
            'sampai' => $this->sampai,
        ]));
    }

    public function render()
    {
        $tokoId = Auth::user()->effective_toko_id;

        $stockIns = StockIn::with(['barang', 'user'])
            ->where('toko_id', $tokoId)
            ->whereBetween('tgl_masuk', [$this->dari, $this->sampai])
            ->orderBy('tgl_masuk', 'desc')
            ->paginate(5);

        $totalItem      = $stockIns->sum('jumlah');
        $totalTransaksi = $stockIns->count();

        $perBarang = $stockIns->getCollection()
            ->groupBy('barang_id')
            ->map(fn($items) => [
                'barang'    => $items->first()->barang->nama_barang,
                'total'     => $items->sum('jumlah'),
                'transaksi' => $items->count(),
            ])
            ->sortByDesc('total')
            ->take(10);

        return view('livewire.laporan.laporan-barang-masuk', compact(
            'stockIns',
            'totalItem',
            'totalTransaksi',
            'perBarang'
        ));
    }
}
