<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Barang;
use Illuminate\Support\Facades\Auth;

class BarangTable extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $search = '';
    public $statusFilter = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function setStatus($status)
    {
        $this->statusFilter = $status;
        $this->resetPage();
    }
    public function clearFilter()
    {
        $this->reset(['search', 'statusFilter']);
    }

    public function render()
    {
        $tokoId = Auth::user()->effective_toko_id;

        $barangs = Barang::where('toko_id', $tokoId)
            ->with('kategori')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('kode_barang', 'like', "%{$this->search}%")
                        ->orWhere('nama_barang', 'like', "%{$this->search}%")
                        ->orWhereHas('kategori', fn($q) => $q->where('nama_kategori', 'like', "%{$this->search}%"));
                });
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->orderBy('nama_barang')
            ->paginate(6);
        return view('livewire.barang-table', compact('barangs'));
    }
}
