<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Barang;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $totalStok = Barang::sum('stok');
        $totalBarang = Barang::count();

        $stokMenipis = Barang::where('stok', '<=', 15)->count();
        $barangMenipis = Barang::where('stok', '<=', 50)
            ->orderBy('stok', 'asc')
            ->limit(5)
            ->get();

        // Mendekati kadaluwarsa (30 hari ke depan)
        $tanggalBatas = Carbon::now()->addDays(30);
        $mendekatiKadaluwarsa = Barang::whereNotNull('tgl_kadaluwarsa')
            ->where('tgl_kadaluwarsa', '<=', $tanggalBatas)
            ->where('tgl_kadaluwarsa', '>=', Carbon::now())
            ->count();

        $barangKadaluwarsa = Barang::whereNotNull('tgl_kadaluwarsa')
            ->where('tgl_kadaluwarsa', '<=', $tanggalBatas)
            ->where('tgl_kadaluwarsa', '>=', Carbon::now())
            ->orderBy('tgl_kadaluwarsa', 'asc')
            ->limit(5)
            ->get();

        return view('dashboard.index', compact(
            'totalStok',
            'totalBarang',
            'stokMenipis',
            'barangMenipis',
            'mendekatiKadaluwarsa',
            'barangKadaluwarsa'
        ));
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
}
