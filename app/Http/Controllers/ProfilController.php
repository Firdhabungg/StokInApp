<?php

namespace App\Http\Controllers;

use App\Models\Toko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfilController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        return view('profil.index', compact('user'));
    }

    /**
     * Display profil for super admin.
     */
    public function adminIndex()
    {
        $user = auth()->user();
        return view('admin.profil.index', compact('user'));
    }

    /**
     * Update profil user (nama & email).
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . auth()->id(),
        ]);

        auth()->user()->update($validated);

        return response()->json([
            'message' => 'Profil berhasil diperbarui'
        ]);
    }

    /**
     * Update password user.
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, auth()->user()->password)) {
            return response()->json([
                'message' => 'Password lama tidak sesuai'
            ], 422);
        }

        auth()->user()->update([
            'password' => Hash::make($validated['password'])
        ]);

        return response()->json([
            'message' => 'Password berhasil diperbarui'
        ]);
    }

    /**
     * Update informasi toko (HANYA OWNER)
     */
    public function updateToko(Request $request)
    {
        if (!auth()->user()->isOwner()) {
            abort(403, 'Anda tidak memiliki akses.');
        }

        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'nullable|email|max:255',
            'phone'   => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        $toko = auth()->user()->toko;

        if (!$toko) {
            abort(404, 'Toko tidak ditemukan.');
        }

        $toko->update($validated);

        return response()->json([
            'message' => 'Informasi toko berhasil diperbarui'
        ]);
    }
}
