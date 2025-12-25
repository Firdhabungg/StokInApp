<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\StockOut;
use App\Models\StockBatch;
use App\Services\StockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StockOutController extends Controller
{
    protected StockService $stockService;

    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
    }

    /**
     * Display a listing of stock out records.
     */
    public function index()
    {
        $tokoId = Auth::user()->toko_id;

        $stockOuts = StockOut::with(['barang', 'batch', 'user'])
            ->where('toko_id', $tokoId)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('stock.out.index', compact('stockOuts'));
    }

    /**
     * Show the form for creating a new stock out.
     */
    public function create()
    {
        $tokoId = Auth::user()->toko_id;

        // Get barangs with available stock
        $barangs = Barang::where('toko_id', $tokoId)
            ->whereHas('stockBatches', function ($query) {
                $query->where('jumlah_sisa', '>', 0)
                      ->where('status', '!=', 'kadaluarsa');
            })
            ->orderBy('nama_barang')
            ->get();

        return view('stock.out.create', compact('barangs'));
    }

    /**
     * Get available stock for a specific barang (AJAX).
     */
    public function getAvailableStock(Request $request, $barangId)
    {
        $tokoId = Auth::user()->toko_id;

        $totalStock = StockBatch::where('barang_id', $barangId)
            ->where('toko_id', $tokoId)
            ->where('jumlah_sisa', '>', 0)
            ->where('status', '!=', 'kadaluarsa')
            ->sum('jumlah_sisa');

        $batches = StockBatch::where('barang_id', $barangId)
            ->where('toko_id', $tokoId)
            ->where('jumlah_sisa', '>', 0)
            ->orderBy('tgl_masuk', 'asc')
            ->get(['id', 'batch_code', 'jumlah_sisa', 'tgl_masuk', 'tgl_kadaluarsa', 'status']);

        return response()->json([
            'total_stock' => $totalStock,
            'batches' => $batches,
        ]);
    }

    /**
     * Store a newly created stock out record.
     */
    public function store(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barangs,id',
            'jumlah' => 'required|integer|min:1',
            'tgl_keluar' => 'required|date',
            'alasan' => 'required|in:penjualan,rusak,kadaluarsa,retur,lainnya',
            'keterangan' => 'nullable|string|max:500',
        ]);

        try {
            $this->stockService->processStockOut([
                'barang_id' => $request->barang_id,
                'toko_id' => Auth::user()->toko_id,
                'user_id' => Auth::id(),
                'jumlah' => $request->jumlah,
                'tgl_keluar' => $request->tgl_keluar,
                'alasan' => $request->alasan,
                'keterangan' => $request->keterangan,
            ]);

            return redirect()->route('stock.out.index')
                ->with('success', 'Barang keluar berhasil dicatat dengan FIFO!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    /**
     * Display the specified stock out record.
     */
    public function show(StockOut $stockOut)
    {
        $stockOut->load(['barang', 'batch', 'user']);

        return view('stock.out.show', compact('stockOut'));
    }
}
