<?php

namespace App\Livewire;

use App\Models\Barang;
use App\Services\StockService;
use Exception;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Tambah Barang Masuk')]
#[Layout('layouts.dashboard')]
class StockInForm extends Component
{
    public string $barang_id      = '';
    public int    $jumlah         = 1;
    public string $tgl_masuk      = '';
    public string $tgl_kadaluarsa = '';
    public string $supplier       = '';
    public string $keterangan     = '';

    public ?string $harga_beli_preview = null;
    public ?string $nama_barang_preview = null;

    public function mount(): void
    {
        $this->tgl_masuk = now()->format('Y-m-d');
    }

    public function updatedBarangId(): void
    {
        if (!$this->barang_id) {
            $this->harga_beli_preview  = null;
            $this->nama_barang_preview = null;
            return;
        }

        $tokoId = Auth::user()->effective_toko_id;
        $barang = Barang::where('toko_id', $tokoId)
            ->find($this->barang_id);

        if ($barang) {
            $this->harga_beli_preview  = number_format($barang->harga, 0, ',', '.');
            $this->nama_barang_preview = $barang->nama_barang;
        }
    }

    protected function rules(): array
    {
        return [
            'barang_id'      => 'required|exists:barangs,id',
            'jumlah'         => 'required|integer|min:1',
            'tgl_masuk'      => 'required|date',
            'tgl_kadaluarsa' => 'nullable|date|after_or_equal:tgl_masuk',
            'supplier'       => 'nullable|string|max:50',
            'keterangan'     => 'nullable|string|max:255',
        ];
    }

    protected function messages(): array
    {
        return [
            'barang_id.required' => 'Pilih barang terlebih dahulu.',
            'barang_id.exists'   => 'Barang yang dipilih tidak valid.',
            'jumlah.required'    => 'Jumlah barang masuk harus diisi.',
            'jumlah.integer'     => 'Jumlah barang masuk harus berupa angka.',
            'jumlah.min'         => 'Jumlah barang masuk minimal 1.',
            'tgl_masuk.required' => 'Tanggal masuk harus diisi.',
            'tgl_masuk.date'     => 'Tanggal masuk tidak valid.',
            'tgl_kadaluarsa.date' => 'Tanggal kadaluarsa tidak valid.',
            'tgl_kadaluarsa.after_or_equal' => 'Tanggal kadaluarsa harus sama atau setelah tanggal masuk.',
            'supplier.string'    => 'Supplier harus berupa teks.',
            'supplier.max'       => 'Supplier maksimal 50 karakter.',
            'keterangan.string'  => 'Keterangan harus berupa teks.',
            'keterangan.max'     => 'Keterangan maksimal 255 karakter.',
        ];
    }

    public function save(): void
    {
        $this->validate();

        $tokoId = Auth::user()->effective_toko_id;
        $barang = Barang::where('toko_id', $tokoId)
            ->findOrFail($this->barang_id);

        try {
            app(StockService::class)->processStockIn([
                'barang_id'      => $this->barang_id,
                'toko_id'        => $tokoId,
                'user_id'        => Auth::id(),
                'jumlah'         => $this->jumlah,
                'tgl_masuk'      => $this->tgl_masuk,
                'tgl_kadaluarsa' => $this->tgl_kadaluarsa ?: null,
                'supplier'       => $this->supplier ?: null,
                'harga_beli'     => $barang->harga,
                'keterangan'     => $this->keterangan ?: null,
            ]);

            session()->flash('success', 'Barang masuk berhasil dicatat!');
            $this->redirectRoute('stock.in.index', navigate: true);
        } catch (Exception $e) {
            $this->addError('barang_id', $e->getMessage());
        }
    }

    public function render()
    {
        $tokoId  = Auth::user()->effective_toko_id;
        $barangs = Barang::where('toko_id', $tokoId)
            ->orderBy('nama_barang')
            ->get();

        return view('livewire.stock-in-form', compact('barangs'));
    }
}
