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
class Barangs extends Component
{
    use WithPagination;

    public $search = '';
    public $status = '';

    public function updatingSearch():void
    {
        $this->resetPage();
    }

    public function updatingStatus():void
    {
        $this->resetPage();
    }

    public function doSearch():void
     {
        $this->resetPage();
    }

    public function setStatus($s): void
    {
        $this->status = $s;
        $this->resetPage();
    }

    public function clearFilter()
    {
        $this->reset(['search', 'status']);
        $this->resetPage();
    }

    public function triggerDelete(int $id, string $nama): void
    {
        $this->dispatch('show-delete-confirm', id: $id, nama: $nama);
    }

    #[On('confirm-delete')]
    public function delete($id)
    {
        $barang = Barang::where('toko_id', Auth::user()->effective_toko_id)
                        ->findOrFail($id);
        $barang->delete();
        session()->flash('success', 'Barang berhasil dihapus.');
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
                    ->orWhereHas('kategori', fn($q) =>
                        $q->where('nama_kategori', 'like', "%{$this->search}%")
                    );
                });
            })
            ->when($this->status, fn($query) =>
                $query->where('status', $this->status)
            )
            ->orderBy('nama_barang')
            ->paginate(6);

        return view('livewire.barangs', compact('barangs'));
    }
}
