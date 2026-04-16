<?php

namespace App\Livewire;

use App\Models\Barang;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Data Barang')]
#[Layout('layouts.dashboard')]
class BarangIndex extends Component
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

    public function updatingStatusFilter()
    {
        $this->resetPage();
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
            ->paginate(7);
        return view('livewire.barang-index', compact('barangs'))->layout('layouts.app', ['title' => 'Data Barang']);
    }

    public function triggerDelete(int $id, string $nama): void
    {
        $this->dispatch('show-delete-confirm', id: $id, nama: $nama);
    }
    
    #[On('confirm-delete')]
    public function delete(int $id): void
    {
        $tokoId = Auth::user()->effective_toko_id;
        Barang::where('toko_id', $tokoId)->findOrFail($id)->delete();
        session()->flash('success', 'Barang berhasil dihapus');
    }

}
