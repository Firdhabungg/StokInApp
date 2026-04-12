<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\KategoriBarang;

class Kategori extends Component
{
    public function render()
    {
        $tokoId = Auth::user()->effective_toko_id;
        $kategoris = KategoriBarang::where('toko_id', $tokoId)
            ->withCount('barangs')
            ->orderBy('nama_kategori')
            ->get();

        return view('livewire.kategori', compact('kategoris'));
    }
}
