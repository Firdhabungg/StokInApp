<?php

namespace App\Livewire;

use App\Models\KategoriBarang;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Detail Kategori')]
#[Layout('layouts.dashboard')]
class KategoriDetail extends Component
{
    use WithPagination;

    public $kategoriId;
    public string $search = '';

    public function mount($kategoriId): void
    {
        $tokoId = Auth::user()->effective_toko_id;

        KategoriBarang::where('toko_id', $tokoId)
            ->findOrFail($kategoriId);

        $this->kategoriId = $kategoriId;
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    #[Computed]
    public function kategori()
    {
        $tokoId = Auth::user()->effective_toko_id;

        return KategoriBarang::where('toko_id', $tokoId)
            ->withCount('barangs')
            ->findOrFail($this->kategoriId);
    }
    #[Computed]
    public function barangs()
    {
        return $this->kategori
            ->barangs()
            ->where('nama_barang', 'like', '%' . $this->search . '%')
            ->orderBy('nama_barang')
            ->paginate(9);
    }

    public function render()
    {
        return view('livewire.kategori-detail');
    }
}
