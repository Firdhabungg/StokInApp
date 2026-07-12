<?php

namespace App\Livewire\Laporan;

use App\Models\StockOut;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Laporan Barang Keluar')]
#[Layout('layouts.dashboard')]
class LaporanBarangKeluar extends Component
{
    use WithPagination;

    public string $dariTanggal   = '';
    public string $sampaiTanggal = '';

    public bool $canExportReport = false;

    protected $paginationTheme = 'tailwind';

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

    public function updatingDariTanggal(): void
    {
        $this->resetPage();
    }

    public function updatingSampaiTanggal(): void
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

        $this->dispatch('open-url', url: route('laporan.barang-keluar.export.excel', [
            'dariTanggal'   => $this->dariTanggal,
            'sampaiTanggal' => $this->sampaiTanggal,
        ]));
    }

    public function exportPdf(): void
    {
        if (! $this->canExportReport) {
            $this->dispatch('export-denied', message: 'Fitur export tidak tersedia untuk paket langganan Anda. Silakan upgrade ke paket Pro atau Business.');
            return;
        }

        $this->dispatch('open-url', url: route('laporan.barang-keluar.export.pdf', [
            'dariTanggal'   => $this->dariTanggal,
            'sampaiTanggal' => $this->sampaiTanggal,
        ]));
    }

    public function render()
    {
        $tokoId = Auth::user()->effective_toko_id;

        $stockOuts = StockOut::with(['barang', 'user'])
            ->where('toko_id', $tokoId)
            ->whereBetween('tgl_keluar', [$this->dariTanggal, $this->sampaiTanggal])
            ->orderBy('tgl_keluar', 'desc')
            ->paginate(5);

        $totalItem      = $stockOuts->sum('jumlah');
        $totalTransaksi = $stockOuts->count();

        $perAlasan = $stockOuts->getCollection()
            ->groupBy('alasan')
            ->map(fn($items, $alasan) => [
                'alasan'    => ucfirst($alasan),
                'total'     => $items->sum('jumlah'),
                'transaksi' => $items->count(),
            ]);

        return view('livewire.laporan.laporan-barang-keluar', compact(
            'stockOuts',
            'totalItem',
            'totalTransaksi',
            'perAlasan'
        ));
    }
}
