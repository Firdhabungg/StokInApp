<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Toko;

class AdminPelangganController extends Controller
{
    public function index()
    {
        $tokos = Toko::withCount(['users', 'barangs'])->get();
        
        return view('admin.pelanggan.index', compact('tokos'));
    }

    public function show(Toko $toko)
    {
        $toko->load(['users', 'barangs']);
        
        return view('admin.pelanggan.show', compact('toko'));
    }
}
