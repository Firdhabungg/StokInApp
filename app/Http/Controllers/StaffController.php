<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class StaffController extends Controller
{
    public function index()
    {
        $staff = User::where('toko_id', auth()->user()->toko_id)
                    ->where('role', 'kasir')
                    ->get();
        
        return view('staff.index', compact('staff'));
    }

    public function create()
    {
        return view('staff.create');
    }

    public function store(Request $request)
    {
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
            'role' => 'kasir', // Hanya bisa tambah kasir
        ]);

        return redirect()->route('staff.index')->with('success', 'Kasir berhasil ditambahkan.');
    }

    public function destroy(User $staff)
    {
        if ($staff->toko_id !== auth()->user()->toko_id || $staff->role !== 'kasir') {
            abort(403);
        }

        $staff->delete();
        return redirect()->route('staff.index')->with('success', 'Kasir berhasil dihapus.');
    }
}