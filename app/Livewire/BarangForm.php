<?php

namespace App\Livewire;

use App\Models\Barang;
use App\Models\KategoriBarang;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class BarangForm extends Component
{
    public $nama_barang = '';
    public $kode_barang = '';
    public $kategori_id = '';
    public $harga = '';
    public $harga_jual = '';

    public $barangId = null;
    public $isEdit = false;


    protected function rules(): array
    {
        return [
            'nama_barang' => 'required|string|max:255',
            'kategori_id' => 'required|integer|exists:kategoris,kategori_id',
            'harga'       => 'required|numeric|min:0',
            'harga_jual'  => 'required|numeric|min:0',
        ];
    }

    protected $messages = [
        'nama_barang.required' => 'Nama barang wajib diisi',
        'kategori_id.required' => 'Kategori wajib dipilih',
        'harga.required'       => 'Harga beli wajib diisi',
        'harga_jual.required'  => 'Harga jual wajib diisi',
    ];

    public function mount($barangId = null): void
    {
        $tokoId = Auth::user()->effective_toko_id;

        if ($barangId) {
            // Mode edit - load data barang
            $barang = Barang::where('toko_id', $tokoId)->findOrFail($barangId);
            $this->barangId   = $barang->id;
            $this->isEdit     = true;
            $this->nama_barang = $barang->nama_barang;
            $this->kode_barang = $barang->kode_barang;
            $this->kategori_id = $barang->kategori_id;
            $this->harga       = $barang->harga;
            $this->harga_jual  = $barang->harga_jual;
        } else {
            // Mode create - auto-generate kode
            $this->kode_barang = $this->generateKodeBarang($tokoId);
        }
    }
    public function save(): void
    {
        $this->validate();

        $tokoId = Auth::user()->effective_toko_id;

        if ($this->isEdit) {
            $barang = Barang::where('toko_id', $tokoId)->findOrFail($this->barangId);
            $barang->update([
                'nama_barang' => $this->nama_barang,
                'kategori_id' => $this->kategori_id,
                'harga'       => $this->harga,
                'harga_jual'  => $this->harga_jual,
            ]);

            session()->flash('success', 'Barang berhasil diperbarui');
        } else {
            $kodeBarang = empty($this->kode_barang)
                ? $this->generateKodeBarang($tokoId)
                : $this->kode_barang;

            Barang::create([
                'toko_id'     => $tokoId,
                'nama_barang' => $this->nama_barang,
                'kode_barang' => $kodeBarang,
                'kategori_id' => $this->kategori_id,
                'harga'       => $this->harga,
                'harga_jual'  => $this->harga_jual,
                'stok'        => 0,
                'status'      => 'habis',
            ]);

            session()->flash('success', 'Barang berhasil ditambahkan');
        }

        $this->redirect(route('barang.index'), navigate: true);
    }

    private function generateKodeBarang(int $tokoId): string
    {
        $lastBarang = Barang::where('toko_id', $tokoId)
            ->where('kode_barang', 'like', 'BRG-%')
            ->orderBy('kode_barang', 'desc')
            ->first();

        $newNumber = $lastBarang
            ? (int) str_replace('BRG-', '', $lastBarang->kode_barang) + 1
            : 1;

        return 'BRG-' . str_pad($newNumber, 5, '0', STR_PAD_LEFT);
    }

    public function render()
    {
        $tokoId   = Auth::user()->effective_toko_id;
        $kategoris = KategoriBarang::where('toko_id', $tokoId)->get();

        return view('livewire.barang-form', compact('kategoris'));
    }
}
