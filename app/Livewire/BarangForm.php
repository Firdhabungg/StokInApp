<?php

namespace App\Livewire;

use App\Models\Barang;
use App\Models\KategoriBarang;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Form Barang')]
#[Layout('layouts.dashboard')]
class BarangForm extends Component
{
    public $nama_barang = '';
    public $kode_barang = '';
    public $kategori_id = '';
    public $harga = '';
    public $harga_jual = '';
    public $barangId = null;
    public $isEdit = false;
    public $stok = 0;

    protected function rules(): array
    {
        return [
            'nama_barang' => 'required|string|min:3|max:50',
            'kode_barang' => $this->isEdit ? 'nullable' : 'required|string|max:50',
            'kategori_id' => 'required|integer|exists:kategoris,kategori_id',
            'harga'       => 'required|numeric|min:0',
            'harga_jual'  => 'required|numeric|min:0',
        ];
    }

    protected function messages(): array
    {
        return [
            'nama_barang.required' => 'Nama barang wajib diisi',
            'kategori_id.required' => 'Kategori wajib dipilih',
            'kategori_id.exists'   => 'Kategori tidak valid',
            'harga.required'       => 'Harga beli wajib diisi',
            'harga_jual.required'  => 'Harga jual wajib diisi',
        ];
    }

    public function mount($barangId = null): void
    {
        $tokoId = Auth::user()->effective_toko_id;

        if ($barangId) {
            $barang = Barang::where('toko_id', $tokoId)->findOrFail($barangId);
            $this->barangId   = $barang->id;
            $this->isEdit     = true;
            $this->nama_barang = $barang->nama_barang;
            $this->kode_barang = $barang->kode_barang;
            $this->kategori_id = $barang->kategori_id;
            $this->harga       = $barang->harga;
            $this->harga_jual  = $barang->harga_jual;
            $this->stok        = $barang->stok;
        } else {
            $this->kode_barang = $this->generateKodeBarang($tokoId);
        }
    }
    public function save(): void
    {
        $this->validate();

        $tokoId = Auth::user()->effective_toko_id;

        try {
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
                // Re-generate saat submit untuk hindari stale kode
                $kode = $this->generateKodeBarang($tokoId);

                Barang::create([
                    'toko_id'     => $tokoId,
                    'nama_barang' => $this->nama_barang,
                    'kode_barang' => $kode,
                    'kategori_id' => $this->kategori_id,
                    'harga'       => $this->harga,
                    'harga_jual'  => $this->harga_jual,
                    'stok'        => 0,
                    'status'      => 'habis',
                ]);
                session()->flash('success', 'Barang berhasil ditambahkan');
            }

            $this->redirectRoute('barang.index', navigate: true);
        } catch (UniqueConstraintViolationException $e) {
            $this->addError('kode_barang', 'Kode barang duplikat, silakan coba lagi.');
        }
    }

    private function generateKodeBarang(int $tokoId): string
    {
        do {
            $lastBarang = Barang::where('toko_id', $tokoId)
                ->where('kode_barang', 'like', 'BRG-%')
                ->orderByRaw('CAST(SUBSTRING(kode_barang, 5) AS UNSIGNED) DESC')
                ->first();

            $newNumber = $lastBarang
                ? (int) substr($lastBarang->kode_barang, 4) + 1
                : 1;

            $kode = 'BRG-' . str_pad($newNumber, 5, '0', STR_PAD_LEFT);

            $exists = Barang::where('toko_id', $tokoId)
                ->where('kode_barang', $kode)
                ->exists();
        } while ($exists);

        return $kode;
    }

    public function render()
    {
        $tokoId   = Auth::user()->effective_toko_id;
        $kategoris = KategoriBarang::where('toko_id', $tokoId)->get();

        return view('livewire.barang-form', compact('kategoris'));
    }
}
