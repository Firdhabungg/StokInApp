<?php

namespace App\Livewire;

use App\Models\KategoriBarang;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class KategoriList extends Component
{
    use WithPagination;

    public string $search = '';
    public ?int $deleteId = null;
    public string $deleteNama = '';

    #[On('kategori-saved')]
    public function refresh(): void
    {
        $this->resetPage();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function editKategori(int $id): void
    {
        $this->dispatch('edit-kategori', id: $id);
    }

    public function confirmDelete(int $id): void
    {
        $tokoId = Auth::user()->effective_toko_id;
        $kategori = KategoriBarang::where('toko_id', $tokoId)->findOrFail($id);

        $this->deleteId   = $id;
        $this->deleteNama = $kategori->nama_kategori;
    }

    public function destroy(int $id): void
    {
        $tokoId = Auth::user()->effective_toko_id;
        $kategori = KategoriBarang::where('toko_id', $tokoId)->findOrFail($id);

        $kategori->delete();
        $this->resetPage();
        session()->flash('success', 'Kategori berhasil dihapus');
    }

    public function resetDelete(): void
    {
        $this->reset(['deleteId', 'deleteNama']);
    }

    #[Computed]
    public function kategoris()
    {
        $tokoId = Auth::user()->effective_toko_id;

        return KategoriBarang::where('toko_id', $tokoId)
            ->withCount('barangs')
            ->where('nama_kategori', 'like', "%{$this->search}%")
            ->orderBy('nama_kategori')
            ->paginate(9);
    }

    public function render()
    {
        return view('livewire.kategori-list');
    }
}
