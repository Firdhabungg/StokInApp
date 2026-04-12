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

    public function index()
    {
        return view('stock.out.index');
    }

    public function create()
    {
        $tokoId = Auth::user()->effective_toko_id;

        $barangs = Barang::where('toko_id', $tokoId)
            ->whereHas('stockBatches', function ($query) {
                $query->where('jumlah_sisa', '>', 0)
                    ->where('status', '!=', 'kadaluarsa');
            })
            ->orderBy('nama_barang')
            ->get();

        return view('stock.out.create', compact('barangs'));
    }

    public function getAvailableStock(Request $request, $barangId)
    {
        $tokoId = Auth::user()->effective_toko_id;

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


    public function store(Request $request)
    {
        $mode = $request->input('mode', 'fifo');

        // ----------------------------------------------------------------
        // FIFO mode — original validation
        // ----------------------------------------------------------------
        if ($mode === 'fifo') {
            $request->validate([
                'barang_id'  => 'required|exists:barangs,id',
                'jumlah'     => 'required|integer|min:1',
                'tgl_keluar' => 'required|date',
                'alasan'     => 'required|in:penjualan,rusak,kadaluarsa,retur,hilang,sample,lainnya',
                'keterangan' => 'nullable|string|max:500',
            ]);

            try {
                $this->stockService->processStockOut([
                    'barang_id'  => $request->barang_id,
                    'toko_id'    => Auth::user()->effective_toko_id,
                    'user_id'    => Auth::id(),
                    'jumlah'     => $request->jumlah,
                    'tgl_keluar' => $request->tgl_keluar,
                    'alasan'     => $request->alasan,
                    'keterangan' => $request->keterangan,
                ]);

                return redirect()->route('stock.out.index')
                    ->with('success', 'Barang keluar berhasil dicatat dengan FIFO!');
            } catch (\Exception $e) {
                return back()->withErrors(['error' => $e->getMessage()])->withInput();
            }
        }

        // ----------------------------------------------------------------
        // MANUAL mode — validate per-batch quantities
        // ----------------------------------------------------------------
        $request->validate([
            'barang_id'       => 'required|exists:barangs,id',
            'tgl_keluar'      => 'required|date',
            'alasan'          => 'required|in:penjualan,rusak,kadaluarsa,retur,hilang,sample,lainnya',
            'keterangan'      => 'nullable|string|max:500',
            'batches'         => 'required|array|min:1',
            'batches.*.batch_id' => 'required|exists:stock_batches,id',
            'batches.*.jumlah'   => 'required|integer|min:1',
        ]);

        $tokoId  = Auth::user()->effective_toko_id;
        $batches = $request->input('batches');

        // Verify each batch belongs to this toko & barang, and qty is available
        foreach ($batches as $idx => $item) {
            $batch = StockBatch::where('id', $item['batch_id'])
                ->where('barang_id', $request->barang_id)
                ->where('toko_id', $tokoId)
                ->first();

            if (!$batch) {
                return back()
                    ->withErrors(["batches.{$idx}.batch_id" => 'Batch tidak valid.'])
                    ->withInput();
            }

            if ($item['jumlah'] > $batch->jumlah_sisa) {
                return back()
                    ->withErrors(["batches.{$idx}.jumlah" => "Jumlah melebihi sisa stok batch {$batch->batch_code} ({$batch->jumlah_sisa} unit)."])
                    ->withInput();
            }
        }

        try {
            $this->stockService->processStockOutManual([
                'barang_id'  => $request->barang_id,
                'toko_id'    => $tokoId,
                'user_id'    => Auth::id(),
                'tgl_keluar' => $request->tgl_keluar,
                'alasan'     => $request->alasan,
                'keterangan' => $request->keterangan,
                'batches'    => $batches,  // [['batch_id' => x, 'jumlah' => y], ...]
            ]);

            return redirect()->route('stock.out.index')
                ->with('success', 'Barang keluar berhasil dicatat (pilih batch manual)!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function show(StockOut $stockOut)
    {
        $stockOut->load(['barang', 'batch', 'user']);

        return view('stock.out.show', compact('stockOut'));
    }
}
