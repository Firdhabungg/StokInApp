<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class Kasir extends Component
{
    use WithPagination;
    protected $paginationTheme = 'tailwind';

    public $search = '';
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $user = auth()->user();
        $toko = $user->toko->load('activeSubscription.plan');

        $kasirs = User::where('toko_id', $user->toko_id)
            ->where('role', 'kasir')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', "%{$this->search}%")
                        ->orWhere('email', 'like', "%{$this->search}%");
                });
            })
            ->paginate(6);

        $canAddUser     = $toko?->canAddUser() ?? false;
        $remainingSlots = $toko?->remainingUserSlots() ?? 0;
        $maxKasir       = $toko?->getFeature('max_kasir', 1) ?? 1;

        return view('livewire.kasir', compact('kasirs', 'canAddUser', 'remainingSlots', 'maxKasir'));
    }
}
