<?php

namespace App\Livewire\Laporan;

use App\Models\Barang;
use App\Models\Sale;
use App\Models\StockBatch;
use App\Models\StockIn;
use App\Models\StockOut;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Laporan')]
#[Layout('layouts.dashboard')]
class Laporans extends Component
{
    public int $totalBarang = 0;
    public int|float $totalStok = 0;
    public int|float $penjualanBulanIni = 0;
    public int $transaksiMasukBulanIni = 0;
    public int $transaksiKeluarBulanIni = 0;

    public function index()
    {
        $tokoId = Auth::user()->effective_toko_id;

        $this->totalBarang = Barang::where('toko_id', $tokoId)->count();

        $this->totalStok = StockBatch::where('toko_id', $tokoId)->sum('jumlah_sisa');

        $this->penjualanBulanIni = Sale::where('toko_id', $tokoId)
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->where('status', 'selesai')
            ->sum('total');

        $this->transaksiMasukBulanIni = StockIn::where('toko_id', $tokoId)
            ->whereMonth('tgl_masuk', now()->month)
            ->count();

        $this->transaksiKeluarBulanIni = StockOut::where('toko_id', $tokoId)
            ->whereMonth('tgl_keluar', now()->month)
            ->count();
    }
    public function render()
    {
        return view('livewire.laporan.laporans');
    }
}
