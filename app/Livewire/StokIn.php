<?php

namespace App\Livewire;

use App\Models\StockIn;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class StokIn extends Component
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

        $stockIns = StockIn::with(['barang', 'batch', 'user'])
            ->where('toko_id', $tokoId)
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('supplier', 'like', '%' . $this->search . '%')
                        ->orWhereHas('barang', function ($q) {
                            $q->where('kode_barang', 'like', '%' . $this->search . '%')
                                ->orWhere('nama_barang', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->when($this->tanggal, function ($query) {
                $query->whereDate('created_at', $this->tanggal);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(7);

        return view('livewire.stok-in', compact('stockIns'));
    }
}
