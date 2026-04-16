<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Tambah Kasir')]
#[Layout('layouts.dashboard')]
class KasirForm extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';

    public function mount(): void
    {
        $toko = auth()->user()->toko;

        if (!$toko || !$toko->canAddUser()) {
            session()->flash('error', 'Batas maksimum kasir tercapai. Upgrade paket Anda.');
            $this->redirectRoute('kasir.index', navigate: true);
        }
    }

    protected function rules()
    {
        return [
            'name' => ['required', 'string', 'max:50'],
            'email' => ['required', 'string', 'email:rfc,dns', 'max:100', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
        ];
    }

    protected function messages(): array
    {
        return [
            'name.required' => 'Nama wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 8 karakter',
        ];
    }

    public function save(): void
    {
        $this->validate();

        $toko = auth()->user()->toko;

        if (!$toko || !$toko->canAddUser()) {
            $this->addError('name', 'Batas maksimum kasir sudah tercapai.');
            return;
        }

        User::create([
            'name'     => $this->name,
            'email'    => $this->email,
            'password' => Hash::make($this->password),
            'toko_id'  => auth()->user()->toko_id,
            'role'     => 'kasir',
        ]);

        session()->flash('success', 'Kasir berhasil ditambahkan.');
        $this->redirectRoute('kasir.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.kasir-form');
    }
}
