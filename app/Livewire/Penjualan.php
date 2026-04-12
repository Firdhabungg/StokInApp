<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Sale;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;

class Penjualan extends Component
{
    use WithPagination;
    protected $paginationTheme = 'tailwind';

    public $search = '';
    public $tanggal = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function updatingTanggal()
    {
        $this->resetPage();
    }

    public function render()
    {
        $tokoId = Auth::user()->effective_toko_id;

        $sales = Sale::with('user')
            ->withCount('items') // ✅ fix N+1, ganti $sale->items->count()
            ->where('toko_id', $tokoId)
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('kode_transaksi', 'like', "%{$this->search}%")
                        ->orWhereHas('user', fn($q) => $q->where('name', 'like', "%{$this->search}%"));
                });
            })
            ->when($this->tanggal, fn($q) => $q->whereDate('tanggal', $this->tanggal))
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        $summaryHariIni = Sale::where('toko_id', $tokoId)
            ->whereDate('tanggal', today())
            ->where('status', 'selesai')
            ->selectRaw('SUM(total) as total_hari_ini, COUNT(*) as transaksi_hari_ini')
            ->first();

        return view('livewire.penjualan', [
            'sales'            => $sales,
            'totalHariIni'     => $summaryHariIni->total_hari_ini ?? 0,
            'transaksiHariIni' => $summaryHariIni->transaksi_hari_ini ?? 0,
        ]);
    }
}
