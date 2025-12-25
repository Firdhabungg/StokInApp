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
    public function index(Request $request)
    {
        $tokoId = Auth::user()->toko_id;

        // Update batch expiry status
        $this->stockService->updateBatchExpiryStatus();

        $query = StockBatch::with(['barang'])
            ->where('toko_id', $tokoId);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by barang
        if ($request->filled('barang_id')) {
            $query->where('barang_id', $request->barang_id);
        }

        $batches = $query->orderBy('tgl_kadaluarsa', 'asc')
            ->paginate(15);

        $barangs = Barang::where('toko_id', $tokoId)
            ->orderBy('nama_barang')
            ->get();

        // Count by status
        $statusCounts = [
            'aman' => StockBatch::where('toko_id', $tokoId)->where('status', 'aman')->count(),
            'hampir_kadaluarsa' => StockBatch::where('toko_id', $tokoId)->where('status', 'hampir_kadaluarsa')->count(),
            'kadaluarsa' => StockBatch::where('toko_id', $tokoId)->where('status', 'kadaluarsa')->count(),
        ];

        return view('stock.batch.index', compact('batches', 'barangs', 'statusCounts'));
    }

    /**
     * Display batches for a specific barang.
     */
    public function showByBarang(Barang $barang)
    {
        $tokoId = Auth::user()->toko_id;

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
