<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use App\Models\Sale;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;

#[Title('Penjualan')]
#[Layout('layouts.dashboard')]
class Penjualan extends Component
{
    use WithPagination;
    protected $paginationTheme = 'tailwind';

    public $query = '';
    public $tanggal = '';

    public function updatingQuery()
    {
        $this->resetPage();
    }
    public function updatingTanggal()
    {
        $this->resetPage();
    }

    public function search()
    {
        $this->resetPage();
    }

    public function resetFilter()
    {
        $this->query = '';
        $this->tanggal = '';
        $this->resetPage();
    }

    public function render()
    {
        $tokoId = Auth::user()->effective_toko_id;

        $query = Sale::with('user')
            ->withCount('items')
            ->where('toko_id', $tokoId)
            ->where('status', 'selesai');

        $sales = $query
            ->when($this->query, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('kode_transaksi', 'like', "%{$this->query}%")
                        ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%{$this->query}%"));
                });
            })
            ->when($this->tanggal, fn($q) => $q->whereDate('tanggal', $this->tanggal))
            ->orderBy('tanggal', 'desc')
            ->paginate(5);

        $summaryHariIni = Sale::where('toko_id', $tokoId)
            ->whereDate('tanggal', today())
            ->where('status', 'selesai')
            ->selectRaw('
            COALESCE(SUM(total), 0) as total_hari_ini,
            COUNT(*) as transaksi_hari_ini
        ')
            ->first();

        return view('penjualan.index', [
            'sales' => $sales,
            'totalHariIni' => $summaryHariIni->total_hari_ini ?? 0,
            'transaksiHariIni' => $summaryHariIni->transaksi_hari_ini ?? 0,
        ]);
    }
}
