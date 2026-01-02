<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class KasirController extends Controller
{
    public function index()
    {
        $toko = auth()->user()->toko;
        $kasirs = User::where('toko_id', auth()->user()->toko_id)
                    ->where('role', 'kasir')
                    ->get();
        
        $canAddUser = $toko ? $toko->canAddUser() : false;
        $remainingSlots = $toko ? $toko->remainingUserSlots() : 0;
        $maxUsers = $toko ? $toko->getFeature('max_users', 1) : 1;
        
        return view('kasir.index', compact('kasirs', 'canAddUser', 'remainingSlots', 'maxUsers'));
    }

    public function create()
    {
        $toko = auth()->user()->toko;
        
        // Cek apakah bisa tambah user
        if (!$toko || !$toko->canAddUser()) {
            return redirect()->route('kasir.index')
                ->with('error', 'Batas maksimum pengguna sudah tercapai. Upgrade paket untuk menambah kasir.');
        }
        
        return view('kasir.create');
    }

    public function store(Request $request)
    {
        $toko = auth()->user()->toko;
        
        // Double check limit
        if (!$toko || !$toko->canAddUser()) {
            return redirect()->route('kasir.index')
                ->with('error', 'Batas maksimum pengguna sudah tercapai. Upgrade paket untuk menambah kasir.');
        }
        
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', Password::min(8)],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'toko_id' => auth()->user()->toko_id,
            'role' => 'kasir',
        ]);

        return redirect()->route('kasir.index')->with('success', 'Kasir berhasil ditambahkan.');
    }

    public function destroy(User $kasir)
    {
        if ($kasir->toko_id !== auth()->user()->toko_id || $kasir->role !== 'kasir') {
            abort(403);
        }

        $kasir->delete();
        return redirect()->route('kasir.index')->with('success', 'Kasir berhasil dihapus.');
    }
}

