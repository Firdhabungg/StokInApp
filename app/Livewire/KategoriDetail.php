<?php

namespace App\Livewire;

use App\Models\Barang;
use App\Models\KategoriBarang as Kategori;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Persist;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Detail Kategori')]
#[Layout('layouts.dashboard')]
class KategoriDetail extends Component
{
    use WithPagination;

    public int $kategoriId;
    public string $search = '';

    public function mount(Kategori $kategori): void
    {
        $tokoId = Auth::user()->effective_toko_id;
        abort_if($kategori->toko_id !== $tokoId, 403);
        $this->kategoriId = $kategori->kategori_id;
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    #[Computed]
    #[Persist]
    public function kategori()
    {
        return Kategori::where('toko_id', Auth::user()->effective_toko_id)
            ->findOrFail($this->kategoriId);
    }

    #[Computed]
    public function barangs()
    {
        return Barang::where('kategori_id', $this->kategoriId)
            ->select('barang_id', 'nama_barang', 'harga_jual', 'stok', 'kategori_id')
            ->where('nama_barang', 'like', '%' . $this->search . '%')
            ->orderBy('nama_barang')
            ->paginate(9);
    }

    public function render()
    {
        return view('livewire.kategori-detail');
    }
}
