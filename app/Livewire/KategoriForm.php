<?php

namespace App\Livewire;

use App\Models\KategoriBarang;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class KategoriForm extends Component
{
    public ?int $editId = null;
    public string $nama_kategori = '';
    public string $deskripsi_kategori = '';

    protected function rules(): array
    {
        $tokoId = Auth::user()->effective_toko_id;
        $ignoreId = $this->editId ?? 'NULL';

        return [
            'nama_kategori' => [
                'required',
                'string',
                'max:50',
                "unique:kategoris,nama_kategori,{$ignoreId},kategori_id,toko_id,{$tokoId}",
            ],
            'deskripsi_kategori' => 'nullable|string|max:255',
        ];
    }

    protected function messages(): array
    {
        return [
            'nama_kategori.required' => 'Nama kategori wajib diisi.',
            'nama_kategori.max'      => 'Nama kategori maksimal 100 karakter.',
            'nama_kategori.unique'   => 'Nama kategori sudah digunakan.',
        ];
    }

    #[On('edit-kategori')]
    public function loadKategori(int $id): void
    {
        $tokoId = Auth::user()->effective_toko_id;
        $kategori = KategoriBarang::where('toko_id', $tokoId)->findOrFail($id);

        $this->editId             = $kategori->kategori_id;
        $this->nama_kategori      = $kategori->nama_kategori;
        $this->deskripsi_kategori = $kategori->deskripsi_kategori ?? '';
    }

    public function save(): void
    {
        $this->validate();

        $tokoId = Auth::user()->effective_toko_id;

        if ($this->editId) {
            $kategori = KategoriBarang::where('toko_id', $tokoId)->findOrFail($this->editId);
            $kategori->update([
                'nama_kategori'      => $this->nama_kategori,
                'deskripsi_kategori' => $this->deskripsi_kategori,
            ]);
            session()->flash('success', 'Kategori berhasil diperbarui');
        } else {
            KategoriBarang::create([
                'toko_id'            => $tokoId,
                'nama_kategori'      => $this->nama_kategori,
                'deskripsi_kategori' => $this->deskripsi_kategori,
            ]);
            session()->flash('success', 'Kategori berhasil ditambahkan');
        }

        $this->resetForm();
        $this->dispatch('kategori-created');
        $this->dispatch('close-modal');
    }

    public function resetForm(): void
    {
        $this->reset(['nama_kategori', 'deskripsi_kategori', 'editId']);
        $this->resetErrorBag();
    }

    public function render()
    {
        return view('livewire.kategori-form');
    }
}
