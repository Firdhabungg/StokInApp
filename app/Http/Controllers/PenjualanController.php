<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Barang;
use App\Models\StockBatch;
use App\Services\StockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PenjualanController extends Controller
{
    protected StockService $stockService;

    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
    }

    /**
     * Display a listing of sales.
     */
    public function index(Request $request)
    {
        $tokoId = Auth::user()->toko_id;

        $query = Sale::with(['user', 'items'])
            ->where('toko_id', $tokoId)
            ->orderBy('created_at', 'desc');

        // Filter by date
        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        $sales = $query->paginate(15);

        // Summary
        $totalHariIni = Sale::where('toko_id', $tokoId)
            ->whereDate('tanggal', today())
            ->where('status', 'selesai')
            ->sum('total');

        $transaksiHariIni = Sale::where('toko_id', $tokoId)
            ->whereDate('tanggal', today())
            ->where('status', 'selesai')
            ->count();

        return view('penjualan.index', compact('sales', 'totalHariIni', 'transaksiHariIni'));
    }

    /**
     * Show the form for creating a new sale (POS).
     */
    public function create()
    {
        $tokoId = Auth::user()->toko_id;

        $barangs = Barang::where('toko_id', $tokoId)
            ->where('stok', '>', 0)
            ->orderBy('nama_barang')
            ->get();

        $kodeTransaksi = Sale::generateKodeTransaksi($tokoId);

        return view('penjualan.create', compact('barangs', 'kodeTransaksi'));
    }

    /**
     * Get barang info for AJAX.
     */
    public function getBarang($id)
    {
        $tokoId = Auth::user()->toko_id;

        $barang = Barang::where('id', $id)
            ->where('toko_id', $tokoId)
            ->first();

        if (!$barang) {
            return response()->json(['error' => 'Barang tidak ditemukan'], 404);
        }

        $stokTersedia = StockBatch::where('barang_id', $id)
            ->where('toko_id', $tokoId)
            ->where('jumlah_sisa', '>', 0)
            ->where('status', '!=', 'kadaluarsa')
            ->sum('jumlah_sisa');

        return response()->json([
            'id' => $barang->id,
            'nama' => $barang->nama_barang,
            'kode' => $barang->kode_barang,
            'harga' => $barang->harga,
            'stok' => $stokTersedia,
        ]);
    }

    /**
     * Store a newly created sale.
     */
    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.barang_id' => 'required|exists:barangs,id',
            'items.*.jumlah' => 'required|integer|min:1',
            'items.*.harga' => 'required|numeric|min:0',
            'uang_dibayar' => 'nullable|numeric|min:0',
            'metode_pembayaran' => 'required|in:cash,transfer,qris',
            'bukti_pembayaran' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'keterangan' => 'nullable|string|max:500',
        ]);

        $tokoId = Auth::user()->toko_id;

        $buktiPembayaranPath = null;
            if ($request->hasFile('bukti_pembayaran')) {
                $buktiPembayaranPath = $request->file('bukti_pembayaran')
                    ->store('bukti-pembayaran', 'public');
            }

        try {
            DB::beginTransaction();

            // Create sale
            $sale = Sale::create([
                'toko_id' => $tokoId,
                'user_id' => Auth::id(),
                'kode_transaksi' => Sale::generateKodeTransaksi($tokoId),
                'tanggal' => now()->toDateString(),
                'total' => 0,
                'status' => 'selesai',
                'keterangan' => $request->keterangan,
                'metode_pembayaran' => $request->metode_pembayaran,
                'bukti_pembayaran' => $buktiPembayaranPath,
            ]);

            $total = 0;

            foreach ($request->items as $item) {
                $subtotal = $item['jumlah'] * $item['harga'];

                // Create sale item
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'barang_id' => $item['barang_id'],
                    'jumlah' => $item['jumlah'],
                    'harga_satuan' => $item['harga'],
                    'subtotal' => $subtotal,
                ]);

                // Reduce stock using FIFO
                $this->stockService->processStockOut([
                    'barang_id' => $item['barang_id'],
                    'toko_id' => $tokoId,
                    'user_id' => Auth::id(),
                    'jumlah' => $item['jumlah'],
                    'tgl_keluar' => now()->toDateString(),
                    'alasan' => 'penjualan',
                    'keterangan' => 'Transaksi: ' . $sale->kode_transaksi,
                ]);

                $total += $subtotal;
            }

            $uangDibayar = $request->metode_pembayaran === 'cash'
                ? (int) $request->uang_dibayar
                : $total;
                
            if ($request->metode_pembayaran === 'cash' && $uangDibayar < $total) {
                throw new \Exception('Uang dibayar kurang dari total transaksi');
            }

            $kembalian = max(0, $uangDibayar - $total);
            
            $sale->update([
                'total' => $total,
                'uang_dibayar' => $uangDibayar,
                'kembalian' => $kembalian,
            ]);

            DB::commit();

            return redirect()->route('penjualan.show', $sale->id)
                ->with('success', 'Transaksi berhasil disimpan!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    /**
     * Display the specified sale (receipt/invoice).
     */
    public function show(Sale $penjualan)
    {
        $penjualan->load(['user', 'items.barang', 'toko']);

        return view('penjualan.show', compact('penjualan'));
    }
}
