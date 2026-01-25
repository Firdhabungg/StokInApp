<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class PengaturanController extends Controller
{
    public function index()
    {
        $settings = Setting::getAllSettings();
        return view('admin.pengaturan.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'nama_aplikasi' => 'required|string|max:255',
            'versi' => 'required|string|max:50',
            'email_admin' => 'required|email|max:255',
            'telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:500',
        ]);

        Setting::set('app_name', $validated['nama_aplikasi']);
        Setting::set('app_version', $validated['versi']);
        Setting::set('admin_email', $validated['email_admin']);
        Setting::set('admin_phone', $validated['telepon'] ?? '');
        Setting::set('admin_address', $validated['alamat'] ?? '');

        return redirect()
            ->route('admin.pengaturan.index')
            ->with('success', 'Pengaturan berhasil disimpan');
    }
}
