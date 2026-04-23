<?php

namespace App\Livewire\Laporan;

use App\Models\Barang;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Laporan Stok')]
#[Layout('layouts.dashboard')]
class LaporanStok extends Component
{
    use WithPagination;

    public bool $canExportReport = false;

    protected $paginationTheme = 'tailwind';

    public function mount(): void
    {
        $toko = Auth::user()->toko;
        $this->canExportReport = $toko ? $toko->canExportReport() : false;
    }

    public function exportExcel(): void
    {
        if (! $this->canExportReport) {
            $this->dispatch('export-denied', message: 'Fitur export tidak tersedia untuk paket langganan Anda. Silakan upgrade ke paket Pro atau Business.');
            return;
        }
        $this->redirect(route('laporan.stok.export.excel'), navigate: false);
    }

    public function exportPdf(): void
    {
        if (! $this->canExportReport) {
            $this->dispatch('export-denied', message: 'Fitur export tidak tersedia untuk paket langganan Anda. Silakan upgrade ke paket Pro atau Business.');
            return;
        }

        $this->redirect(route('laporan.stok.export.pdf'), navigate: false);
    }

    public function render()
    {
        $tokoId = Auth::user()->effective_toko_id;

        $barangs = Barang::with('kategori')
            ->where('toko_id', $tokoId)
            ->orderBy('kategori_id')
            ->orderBy('nama_barang')
            ->paginate(5, ['*'], 'barang_page');

        $stokPerKategori = Barang::where('barangs.toko_id', $tokoId)
            ->join('kategoris', 'barangs.kategori_id', '=', 'kategoris.kategori_id')
            ->select(
                'kategoris.nama_kategori',
                DB::raw('SUM(barangs.stok) as total_stok'),
                DB::raw('COUNT(barangs.id) as jumlah_barang')
            )
            ->groupBy('kategoris.nama_kategori')
            ->paginate(6, ['*'], 'kategori_page');

        $totalStok   = $barangs->sum('stok');
        $stokMenipis = $barangs->where('status', 'menipis')->count();
        $stokHabis   = $barangs->where('status', 'habis')->count();

        return view('livewire.laporan.laporan-stok', compact(
            'barangs',
            'stokPerKategori',
            'totalStok',
            'stokMenipis',
            'stokHabis'
        ));
    }
}
