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

    public function updatingDari(): void
    {
        $this->resetPage();
    }

    public function updatingSampai(): void
    {
        $this->resetPage();
    }

    public function clearFilter(): void
    {
        $this->dari   = now()->startOfMonth()->toDateString();
        $this->sampai = now()->toDateString();
        $this->resetPage();
    }

    public function isFiltered(): bool
    {
        return $this->dari !== now()->startOfMonth()->toDateString()
            || $this->sampai !== now()->toDateString();
    }

    public function exportExcel(): void
    {
        if (! $this->canExportReport) {
            $this->dispatch('export-denied', message: 'Fitur export tidak tersedia untuk paket langganan Anda. Silakan upgrade ke paket Pro atau Business.');
            return;
        }

        $this->dispatch('open-url', url: route('laporan.barang-keluar.export.excel', [
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

        $this->dispatch('open-url', url: route('laporan.barang-keluar.export.pdf', [
            'dari'   => $this->dari,
            'sampai' => $this->sampai,
        ]));
    }

    public function render()
    {
        $tokoId = Auth::user()->effective_toko_id;

        $stockOuts = StockOut::with(['barang', 'user'])
            ->where('toko_id', $tokoId)
            ->whereBetween('tgl_keluar', [$this->dari, $this->sampai])
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
