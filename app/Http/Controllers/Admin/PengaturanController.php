<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PengaturanController extends Controller
{
    public function index()
    {
        return view('admin.pengaturan.index');
    }

    public function update(Request $request)
    {
        // sementara dump dulu
        return redirect()
            ->route('admin.pengaturan.index')
            ->with('success', 'Pengaturan berhasil disimpan');
    }
}
