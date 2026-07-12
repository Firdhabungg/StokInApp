<?php

namespace App\Livewire;

use App\Models\Barang;
use App\Models\StockBatch;
use App\Services\StockService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Tambah Barang Keluar')]
#[Layout('layouts.dashboard')]
class StockOutForm extends Component
{
    public $barang_id      = '';
    public $jumlah         = 1;
    public $tgl_keluar     = '';
    public $alasan          = '';
    public $keterangan     = '';
    public string $mode = 'fifo';
    public array $available_batches = [];
    public array $selected_batches = [];
    public int $total_stock = 0;

    public function mount(): void
    {
        $this->tgl_keluar = now()->format('Y-m-d');
    }

    public function updatedBarangId(): void
    {
        $this->available_batches = [];
        $this->selected_batches = [];
        $this->total_stock = 0;
        $this->jumlah = 1;

        if (!$this->barang_id) {
            return;
        }

        $tokoId = Auth::user()->effective_toko_id;

        $batches = StockBatch::where('barang_id', $this->barang_id)
            ->where('toko_id', $tokoId)
            ->where('jumlah_sisa', '>', 0)
            ->orderBy('tgl_masuk', 'asc')
            ->get(['id', 'batch_code', 'jumlah_sisa', 'tgl_masuk', 'tgl_kadaluarsa', 'status']);

        $this->available_batches = $batches->toArray();
        $this->total_stock = collect($this->available_batches)
            ->where('status', '!=', 'kadaluarsa')
            ->sum('jumlah_sisa');
    }

    public function setMode(string $mode)
    {
        $this->mode = $mode;
        $this->resetValidation();
    }

    public function rules(): array
    {
        return [
            'barang_id'     => 'required|exists:barangs,id',
            'tgl_keluar'    => 'required|date',
            'alasan'        => 'required|in:penjualan,rusak,kadaluarsa,retur,hilang,sample,lainnya',
            'keterangan'    => 'nullable|string|max:500',
        ];
        if ($this->mode === 'fifo') {
            $rules['jumlah'] = 'required|integer|min:1|max:' . max(1, $this->total_stock);
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'barang_id.required' => 'Barang wajib dipilih.',
            'barang_id.exists'   => 'Barang yang dipilih tidak valid.',
            'jumlah.required'    => 'Jumlah wajib diisi.',
            'jumlah.integer'     => 'Jumlah harus berupa angka.',
            'jumlah.min'         => 'Jumlah minimal adalah 1.',
            'jumlah.max'         => 'Jumlah melebihi stok yang tersedia (' . $this->total_stock . ' unit).',
            'tgl_keluar.required' => 'Tanggal keluar wajib diisi.',
            'tgl_keluar.date'     => 'Tanggal keluar tidak valid.',
            'alasan.required'    => 'Alasan wajib dipilih.',
            'alasan.in'          => 'Alasan yang dipilih tidak valid.',
            'keterangan.string'  => 'Keterangan harus berupa teks.',
        ];
    }

    public function save()
    {
        $this->validate();

        $tokoId = Auth::user()->effective_toko_id;

        // Custom validation based on mode
        if ($this->mode === 'fifo') {
            try {
                app(StockService::class)->processStockOut([
                    'barang_id'  => $this->barang_id,
                    'toko_id'    => $tokoId,
                    'user_id'    => Auth::id(),
                    'jumlah'     => $this->jumlah,
                    'tgl_keluar' => $this->tgl_keluar,
                    'alasan'     => $this->alasan,
                    'keterangan' => $this->keterangan,
                ]);

                session()->flash('success', 'Barang keluar berhasil dicatat dengan FIFO!');
                return $this->redirectRoute('stock.out.index', navigate: true);
            } catch (\Exception $e) {
                $this->addError('error', $e->getMessage());
                return;
            }
        } else {
            // Manual Mode
            // Filter out selected batches with empty/zero qty
            $filteredBatches = array_filter($this->selected_batches, function ($qty) {
                return !empty($qty) && $qty > 0;
            });

            if (empty($filteredBatches)) {
                $this->addError('selected_batches', 'Pilih setidaknya satu batch dengan jumlah lebih dari 0.');
                return;
            }

            $formattedBatches = [];
            foreach ($filteredBatches as $batchId => $qty) {
                $batch = StockBatch::where('id', $batchId)
                    ->where('barang_id', $this->barang_id)
                    ->where('toko_id', $tokoId)
                    ->first();

                if (!$batch) {
                    $this->addError("selected_batches.{$batchId}", 'Batch tidak valid.');
                    return;
                }

                if ($qty > $batch->jumlah_sisa) {
                    $this->addError("selected_batches.{$batchId}", "Jumlah melebihi sisa stok batch {$batch->batch_code} ({$batch->jumlah_sisa} unit).");
                    return;
                }

                $formattedBatches[] = [
                    'batch_id' => $batchId,
                    'jumlah' => $qty,
                ];
            }

            try {
                app(StockService::class)->processStockOutManual([
                    'barang_id'  => $this->barang_id,
                    'toko_id'    => $tokoId,
                    'user_id'    => Auth::id(),
                    'tgl_keluar' => $this->tgl_keluar,
                    'alasan'     => $this->alasan,
                    'keterangan' => $this->keterangan,
                    'batches'    => $formattedBatches,
                ]);

                session()->flash('success', 'Barang keluar berhasil dicatat (pilih batch manual)!');
                return $this->redirectRoute('stock.out.index', navigate: true);
            } catch (\Exception $e) {
                $this->addError('error', $e->getMessage());
                return;
            }
        }
    }

    public function render()
    {
        $tokoId = Auth::user()->effective_toko_id;

        $barangs = Barang::where('toko_id', $tokoId)
            ->whereHas('stockBatches', function ($query) {
                $query->where('jumlah_sisa', '>', 0)
                    ->where('status', '!=', 'kadaluarsa');
            })
            ->orderBy('nama_barang')
            ->get();

        return view('livewire.stock-out-form', compact('barangs'));
    }
}
