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

    public string $dariTanggal   = '';
    public string $sampaiTanggal = '';

    public bool $canExportReport = false;


    protected $queryString = [
        'dariTanggal'   => ['except' => ''],
        'sampaiTanggal' => ['except' => ''],
    ];

    public function mount(): void
    {
        $this->dariTanggal   = $this->dariTanggal   ?: now()->startOfMonth()->toDateString();
        $this->sampaiTanggal = $this->sampaiTanggal ?: now()->toDateString();

        $toko = Auth::user()->toko;
        $this->canExportReport = $toko ? $toko->canExportReport() : false;
    }

    public function updatedDariTanggal(): void
    {
        $this->resetPage();
    }

    public function updatedSampaiTanggal(): void
    {
        $this->resetPage();
    }

    public function clearFilter(): void
    {
        $this->dariTanggal   = now()->startOfMonth()->toDateString();
        $this->sampaiTanggal = now()->toDateString();
        $this->resetPage();
    }

    public function isFiltered(): bool
    {
        return $this->dariTanggal !== now()->startOfMonth()->toDateString()
            || $this->sampaiTanggal !== now()->toDateString();
    }

    public function exportExcel(): void
    {
        if (! $this->canExportReport) {
            $this->dispatch('export-denied', message: 'Fitur export tidak tersedia untuk paket langganan Anda. Silakan upgrade ke paket Pro atau Business.');
            return;
        }

        $this->redirect(route('laporan.barang-masuk.export.excel', [
            'dari'   => $this->dariTanggal,
            'sampai' => $this->sampaiTanggal,
        ]), navigate: false);
    }

    public function exportPdf(): void
    {
        if (! $this->canExportReport) {
            $this->dispatch('export-denied', message: 'Fitur export tidak tersedia untuk paket langganan Anda. Silakan upgrade ke paket Pro atau Business.');
            return;
        }

        $this->redirect(route('laporan.barang-masuk.export.pdf', [
            'dari'   => $this->dariTanggal,
            'sampai' => $this->sampaiTanggal,
        ]), navigate: false);
    }

    public function render()
    {
        $tokoId = Auth::user()->effective_toko_id;

        $stockIns = StockIn::with(['barang', 'user'])
            ->where('toko_id', $tokoId)
            ->whereBetween('tgl_masuk', [$this->dariTanggal, $this->sampaiTanggal])
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

        return view('laporan.barang-masuk', compact(
            'stockIns',
            'totalItem',
            'totalTransaksi',
            'perBarang'
        ));
    }
}
