<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Kategori Barang')]
#[Layout('layouts.dashboard')]
class Kategori extends Component
{
    public function render()
    {
        return view('livewire.kategori');
    }
}
