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

    public function index()
    {
        return view('penjualan.index');
    }

    public function create()
    {
        $tokoId = Auth::user()->effective_toko_id;

        // ✅ Hanya tampilkan barang yang punya stok aktif di stock_batches
        $barangs = Barang::where('toko_id', $tokoId)
            ->whereHas('stockBatches', function ($q) use ($tokoId) {
                $q->where('toko_id', $tokoId)
                    ->where('jumlah_sisa', '>', 0)
                    ->where('status', '!=', 'kadaluarsa');
            })
            ->orderBy('nama_barang')
            ->get();

        $kodeTransaksi = Sale::generateKodeTransaksi($tokoId);

        return view('penjualan.create', compact('barangs', 'kodeTransaksi'));
    }

    public function getBarang($id)
    {
        $tokoId = Auth::user()->effective_toko_id;

        $barang = Barang::where('id', $id)
            ->where('toko_id', $tokoId)
            ->first();

        $stokTersedia = StockBatch::where('barang_id', $barang->id)
            ->where('toko_id', $tokoId)
            ->where('jumlah_sisa', '>', 0)
            ->where('status', '!=', 'kadaluarsa')
            ->sum('jumlah_sisa');

        return response()->json([
            'id'    => $barang->id, // ✅ bukan barang->barang_id
            'nama'  => $barang->nama_barang,
            'kode'  => $barang->kode_barang,
            'harga' => $barang->harga_jual,
            'stok'  => $stokTersedia,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'items'                    => 'required|array|min:1',
            'items.*.barang_id'        => 'required|exists:barangs,id',
            'items.*.jumlah'           => 'required|integer|min:1',
            'items.*.harga'            => 'required|numeric|min:0',
            'uang_dibayar'             => 'nullable|numeric|min:0',
            'metode_pembayaran'        => 'required|in:cash,transfer,qris',
            'bukti_pembayaran'         => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'keterangan'               => 'nullable|string|max:500',
        ]);

        $tokoId = Auth::user()->effective_toko_id;

        // ✅ Validasi stok semua item SEBELUM DB transaction
        foreach ($request->items as $item) {
            $stok = StockBatch::where('barang_id', $item['barang_id'])
                ->where('toko_id', $tokoId)
                ->where('jumlah_sisa', '>', 0)
                ->where('status', '!=', 'kadaluarsa')
                ->sum('jumlah_sisa');

            $barang = Barang::find($item['barang_id']);

            if ($stok < $item['jumlah']) {
                return back()->withErrors([
                    'error' => "Stok {$barang->nama_barang} tidak mencukupi. Tersedia: {$stok}, Diminta: {$item['jumlah']}"
                ])->withInput();
            }
        }

        $buktiPembayaranPath = null;
        if ($request->hasFile('bukti_pembayaran')) {
            $buktiPembayaranPath = $request->file('bukti_pembayaran')
                ->store('bukti-pembayaran', 'public');
        }

        try {
            DB::beginTransaction();

            $sale = Sale::create([
                'toko_id'           => $tokoId,
                'user_id'           => Auth::id(),
                'kode_transaksi'    => Sale::generateKodeTransaksi($tokoId),
                'tanggal'           => now(),
                'total'             => 0,
                'status'            => 'selesai',
                'keterangan'        => $request->keterangan,
                'metode_pembayaran' => $request->metode_pembayaran,
                'bukti_pembayaran'  => $buktiPembayaranPath,
            ]);

            $total = 0;

            foreach ($request->items as $item) {
                $subtotal = $item['jumlah'] * $item['harga'];

                SaleItem::create([
                    'sale_id'      => $sale->id,
                    'barang_id'    => $item['barang_id'],
                    'jumlah'       => $item['jumlah'],
                    'harga_satuan' => $item['harga'],
                    'subtotal'     => $subtotal,
                ]);

                $this->stockService->processStockOut([
                    'barang_id' => $item['barang_id'],
                    'toko_id'   => $tokoId,
                    'user_id'   => Auth::id(),
                    'jumlah'    => $item['jumlah'],
                    'tgl_keluar' => now()->toDateString(),
                    'alasan'    => 'penjualan',
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

            $sale->update([
                'total'        => $total,
                'uang_dibayar' => $uangDibayar,
                'kembalian'    => max(0, $uangDibayar - $total),
            ]);

            DB::commit();

            return redirect()->route('penjualan.show', $sale->id)
                ->with('success', 'Penjualan berhasil disimpan');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function show(Sale $penjualan)
    {
        $penjualan->load(['user', 'items.barang', 'toko']);

        return view('penjualan.show', compact('penjualan'));
    }
}
