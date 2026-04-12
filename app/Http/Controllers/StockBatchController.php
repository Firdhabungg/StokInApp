<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\StockBatch;
use App\Services\StockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StockBatchController extends Controller
{
    protected StockService $stockService;

    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
    }

    /**
     * Display a listing of all batches.
     */
    public function index()
    {
        return view('stock.batch.index');
    }

    /**
     * Display batches for a specific barang.
     */
    public function showByBarang(Barang $barang)
    {
        $tokoId = Auth::user()->effective_toko_id;

        // Ensure barang belongs to user's toko
        if ($barang->toko_id !== $tokoId) {
            abort(403);
        }

        $batches = StockBatch::where('barang_id', $barang->id)
            ->where('toko_id', $tokoId)
            ->orderBy('tgl_masuk', 'asc')
            ->get();

        return view('stock.batch.show', compact('barang', 'batches'));
    }

    /**
     * Display batch details.
     */
    public function show(StockBatch $batch)
    {
        $batch->load(['barang', 'stockIn', 'stockOut']);

        return view('stock.batch.detail', compact('batch'));
    }
}
