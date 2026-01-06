<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Barang;
use App\Models\StockBatch;
use App\Models\StockIn;
use App\Models\StockOut;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display dashboard with real-time stock data.
     */
    public function index()
    {
        $tokoId = Auth::user()->toko_id;

        // Total stok dari semua batch yang tersedia
        $totalStok = StockBatch::where('toko_id', $tokoId)
            ->where('status', '!=', 'kadaluarsa')
            ->sum('jumlah_sisa');

        // Total jenis barang
        $totalBarang = Barang::where('toko_id', $tokoId)->count();

        // Stok menipis (barang dengan stok <= 10)
        $stokMenipis = Barang::where('toko_id', $tokoId)
            ->where('status', 'menipis')
            ->count();

        // Barang dengan stok menipis (untuk list)
        $barangMenipis = Barang::where('toko_id', $tokoId)
            ->where('stok', '<=', 15)
            ->where('stok', '>', 0)
            ->orderBy('stok', 'asc')
            ->limit(5)
            ->get();

        // Batch hampir kadaluarsa (7 hari ke depan) ATAU sudah kadaluarsa
        $tanggalBatas = Carbon::now()->addDays(7);
        $batchHampirKadaluarsa = StockBatch::where('toko_id', $tokoId)
            ->whereNotNull('tgl_kadaluarsa')
            ->where('tgl_kadaluarsa', '<=', $tanggalBatas)
            ->where('jumlah_sisa', '>', 0)
            ->count();

        // List batch hampir kadaluarsa DAN sudah kadaluarsa
        $listBatchKadaluarsa = StockBatch::with('barang')
            ->where('toko_id', $tokoId)
            ->whereNotNull('tgl_kadaluarsa')
            ->where('tgl_kadaluarsa', '<=', Carbon::now()->addDays(30))
            ->where('jumlah_sisa', '>', 0)
            ->orderBy('tgl_kadaluarsa', 'asc')
            ->limit(5)
            ->get();

        // Transaksi hari ini (barang masuk + keluar)
        $today = Carbon::today();
        $barangMasukHariIni = StockIn::where('toko_id', $tokoId)
            ->whereDate('tgl_masuk', $today)
            ->count();

        $barangKeluarHariIni = StockOut::where('toko_id', $tokoId)
            ->whereDate('tgl_keluar', $today)
            ->count();

        // 5 transaksi terbaru (gabungan masuk dan keluar)
        $transaksiMasuk = StockIn::with('barang')
            ->where('toko_id', $tokoId)
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get()
            ->map(function ($item) {
                return [
                    'type' => 'masuk',
                    'barang' => $item->barang->nama_barang,
                    'jumlah' => $item->jumlah,
                    'tanggal' => $item->created_at,
                    'supplier' => $item->supplier,
                ];
            });

        $transaksiKeluar = StockOut::with('barang')
            ->where('toko_id', $tokoId)
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get()
            ->map(function ($item) {
                return [
                    'type' => 'keluar',
                    'barang' => $item->barang->nama_barang,
                    'jumlah' => $item->jumlah,
                    'tanggal' => $item->created_at,
                    'alasan' => $item->alasan,
                ];
            });

        $transaksiTerbaru = $transaksiMasuk->concat($transaksiKeluar)
            ->sortByDesc('tanggal')
            ->take(5)
            ->values();

        // Status batch summary
        $statusBatch = [
            'aman' => StockBatch::where('toko_id', $tokoId)->where('status', 'aman')->where('jumlah_sisa', '>', 0)->count(),
            'hampir_kadaluarsa' => StockBatch::where('toko_id', $tokoId)->where('status', 'hampir_kadaluarsa')->where('jumlah_sisa', '>', 0)->count(),
            'kadaluarsa' => StockBatch::where('toko_id', $tokoId)->where('status', 'kadaluarsa')->where('jumlah_sisa', '>', 0)->count(),
        ];

        return view('dashboard.index', compact(
            'totalStok',
            'totalBarang',
            'stokMenipis',
            'barangMenipis',
            'batchHampirKadaluarsa',
            'listBatchKadaluarsa',
            'barangMasukHariIni',
            'barangKeluarHariIni',
            'transaksiTerbaru',
            'statusBatch'
        ));
    }
}
