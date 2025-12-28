<?php

namespace App\Http\Controllers;
use App\Models\Toko;
use Illuminate\Http\Request;

use function PHPUnit\Framework\returnValue;

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
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
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