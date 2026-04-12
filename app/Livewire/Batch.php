<?php

namespace App\Livewire;

use App\Models\Barang;
use App\Models\StockBatch;
use App\Services\StockService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Batch extends Component
{
    use WithPagination;
    protected $paginationTheme = 'tailwind';

    public $search = '';
    public $statusFilter = '';
    public $barangFilter = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function setStatus($status)
    {
        $this->statusFilter = $status;
        $this->resetPage();
    }
    public function updatingStatusFilter()
    {
        $this->resetPage();
    }
    public function updatingBarangFilter()
    {
        $this->resetPage();
    }
    public function clearFilter()
    {
        $this->reset(['search', 'statusFilter', 'barangFilter']);
    }
    public function render()
    {
        $tokoId = Auth::user()->effective_toko_id;

        // Update expiry status setiap render
        app(StockService::class)->updateBatchExpiryStatus();

        $batches = StockBatch::with('barang')
            ->where('toko_id', $tokoId)
            ->when($this->search, function ($query) {
                $query->whereHas('barang', function ($q) {
                    $q->where('nama_barang', 'like', '%' . $this->search . '%')
                        ->orWhere('kode_barang', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->when($this->barangFilter, function ($query) {
                $query->where('barang_id', $this->barangFilter);
            })
            ->orderBy('tgl_kadaluarsa', 'desc')
            ->paginate(5);

        $barangs = Barang::where('toko_id', $tokoId)
            ->orderBy('nama_barang')
            ->get();

        $statusCounts = StockBatch::where('toko_id', $tokoId)
            ->selectRaw("
                SUM(status = 'aman') as aman,
                SUM(status = 'hampir_kadaluarsa') as hampir_kadaluarsa,
                SUM(status = 'kadaluarsa') as kadaluarsa
            ")
            ->first();

        return view('livewire.batch', compact('batches', 'barangs', 'statusCounts'));
    }
}
