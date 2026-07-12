<?php

namespace App\Livewire;

use App\Models\Sale;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Detail Transaksi')]
#[Layout('layouts.dashboard')]
class PenjualanDetail extends Component
{
    public Sale $sale;

    public function mount(Sale $sale): void
    {
        // Ensure user can only view sales from their own toko
        $tokoId = Auth::user()->effective_toko_id;

        if ($sale->toko_id !== $tokoId) {
            abort(403, 'Anda tidak memiliki akses ke transaksi ini.');
        }

        $this->sale = $sale->load(['user', 'items.barang', 'toko']);
    }

    public function render()
    {
        return view('livewire.penjualan-detail');
    }
}
