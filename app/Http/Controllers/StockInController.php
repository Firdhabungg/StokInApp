<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\StockIn;
use App\Services\StockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StockInController extends Controller
{
    protected StockService $stockService;

    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
    }

    /**
     * Display a listing of stock in records.
     */
    public function index()
    {
        $tokoId = Auth::user()->toko_id;

        $stockIns = StockIn::with(['barang', 'batch', 'user'])
            ->where('toko_id', $tokoId)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('stock.in.index', compact('stockIns'));
    }

    /**
     * Show the form for creating a new stock in.
     */
    public function create()
    {
        $tokoId = Auth::user()->toko_id;

        $barangs = Barang::where('toko_id', $tokoId)
            ->orderBy('nama_barang')
            ->get();

        return view('stock.in.create', compact('barangs'));
    }

    /**
     * Store a newly created stock in record.
     */
    public function store(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barangs,id',
            'jumlah' => 'required|integer|min:1',
            'tgl_masuk' => 'required|date',
            'tgl_kadaluarsa' => 'nullable|date|after_or_equal:tgl_masuk',
            'supplier' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string|max:500',
        ]);

        try {
            // Ambil harga beli dari data barang
            $barang = Barang::findOrFail($request->barang_id);

            $this->stockService->processStockIn([
                'barang_id' => $request->barang_id,
                'toko_id' => Auth::user()->toko_id,
                'user_id' => Auth::id(),
                'jumlah' => $request->jumlah,
                'tgl_masuk' => $request->tgl_masuk,
                'tgl_kadaluarsa' => $request->tgl_kadaluarsa,
                'supplier' => $request->supplier,
                'harga_beli' => $barang->harga, // Ambil dari data barang
                'keterangan' => $request->keterangan,
            ]);

            return redirect()->route('stock.in.index')
                ->with('success', 'Barang masuk berhasil dicatat!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    /**
     * Display the specified stock in record.
     */
    public function show(StockIn $stockIn)
    {
        $stockIn->load(['barang', 'batch', 'user']);

        return view('stock.in.show', compact('stockIn'));
    }
}
