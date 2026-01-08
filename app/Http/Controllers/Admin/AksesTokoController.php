<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Toko;
use Illuminate\Http\Request;

class AksesTokoController extends Controller
{
    /**
     * Mulai akses toko sebagai Super Admin
     */
    public function start(Toko $toko)
    {
        $user = auth()->user();

        // Hanya Super Admin yang bisa akses toko
        if (!$user->isSuperAdmin()) {
            abort(403, 'Unauthorized');
        }

        // Simpan ID asli Super Admin di session
        session([
            'original_admin_id' => $user->id,
            'akses_toko_id' => $toko->id,
            'akses_toko_name' => $toko->name,
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'Anda sekarang mengakses toko: ' . $toko->name);
    }

    /**
     * Berhenti akses toko dan kembali ke admin panel
     */
    public function stop()
    {
        // Hapus session akses toko
        session()->forget(['original_admin_id', 'akses_toko_id', 'akses_toko_name']);

        return redirect()->route('admin.toko.index')
            ->with('success', 'Anda sudah kembali ke mode Super Admin.');
    }
}
