<?php

namespace App\Livewire;

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

    public function mount(): void
    {
        $this->tgl_keluar = now()->format('Y-m-d');
    }

    public function rules(): array
    {
        return [
            'barang_id'  => 'required|exists:barangs,id',
            'jumlah'     => 'required|integer|min:1',
            'tgl_keluar' => 'required|date',
            'alasan'      => 'nullable|string|max:255',
            'keterangan' => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'barang_id.required' => 'Barang wajib dipilih.',
            'barang_id.exists'   => 'Barang yang dipilih tidak valid.',
            'jumlah.required'    => 'Jumlah wajib diisi.',
            'jumlah.integer'     => 'Jumlah harus berupa angka.',
            'jumlah.min'         => 'Jumlah minimal adalah 1.',
            'tgl_keluar.required' => 'Tanggal keluar wajib diisi.',
            'tgl_keluar.date'     => 'Tanggal keluar tidak valid.',
        ];
    }

    public function render()
    {
        return view('livewire.stock-out-form');
    }
}
