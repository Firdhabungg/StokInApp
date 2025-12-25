<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\StockIn;
use App\Models\StockOut;
use App\Models\StockBatch;
use App\Models\KategoriBarang;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    /**
     * Dashboard laporan.
     */
    public function index()
    {
        $tokoId = Auth::user()->toko_id;

        // Summary data
        $totalBarang = Barang::where('toko_id', $tokoId)->count();
        $totalStok = StockBatch::where('toko_id', $tokoId)->sum('jumlah_sisa');
        
        $penjualanBulanIni = Sale::where('toko_id', $tokoId)
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->where('status', 'selesai')
            ->sum('total');

        $transaksiMasukBulanIni = StockIn::where('toko_id', $tokoId)
            ->whereMonth('tgl_masuk', now()->month)
            ->count();

        $transaksiKeluarBulanIni = StockOut::where('toko_id', $tokoId)
            ->whereMonth('tgl_keluar', now()->month)
            ->count();

        return view('laporan.index', compact(
            'totalBarang',
            'totalStok',
            'penjualanBulanIni',
            'transaksiMasukBulanIni',
            'transaksiKeluarBulanIni'
        ));
    }

    /**
     * Laporan stok per barang dan kategori.
     */
    public function stok(Request $request)
    {
        $tokoId = Auth::user()->toko_id;

        $barangs = Barang::with('kategori')
            ->where('toko_id', $tokoId)
            ->orderBy('kategori_id')
            ->orderBy('nama_barang')
            ->get();

        // Summary per kategori
        $stokPerKategori = Barang::where('toko_id', $tokoId)
            ->join('kategoris', 'barangs.kategori_id', '=', 'kategoris.kategori_id')
            ->select('kategoris.nama_kategori', DB::raw('SUM(barangs.stok) as total_stok'), DB::raw('COUNT(barangs.id) as jumlah_barang'))
            ->groupBy('kategoris.nama_kategori')
            ->get();

        $totalStok = $barangs->sum('stok');
        $stokMenipis = $barangs->where('status', 'menipis')->count();
        $stokHabis = $barangs->where('status', 'habis')->count();

        return view('laporan.stok', compact('barangs', 'stokPerKategori', 'totalStok', 'stokMenipis', 'stokHabis'));
    }

    /**
     * Laporan penjualan.
     */
    public function penjualan(Request $request)
    {
        $tokoId = Auth::user()->toko_id;
        $filter = $request->get('filter', 'harian');
        $tanggal = $request->get('tanggal', now()->toDateString());
        $bulan = $request->get('bulan', now()->format('Y-m'));

        $query = Sale::with(['items.barang', 'user'])
            ->where('toko_id', $tokoId)
            ->where('status', 'selesai');

        if ($filter == 'harian') {
            $query->whereDate('tanggal', $tanggal);
            $labelPeriode = Carbon::parse($tanggal)->format('d F Y');
        } else {
            $query->whereYear('tanggal', substr($bulan, 0, 4))
                  ->whereMonth('tanggal', substr($bulan, 5, 2));
            $labelPeriode = Carbon::parse($bulan . '-01')->format('F Y');
        }

        $sales = $query->orderBy('tanggal', 'desc')->get();

        $totalPenjualan = $sales->sum('total');
        $totalTransaksi = $sales->count();
        $rataRata = $totalTransaksi > 0 ? $totalPenjualan / $totalTransaksi : 0;

        // Top selling items
        $topItems = SaleItem::whereHas('sale', function ($q) use ($tokoId, $filter, $tanggal, $bulan) {
            $q->where('toko_id', $tokoId)->where('status', 'selesai');
            if ($filter == 'harian') {
                $q->whereDate('tanggal', $tanggal);
            } else {
                $q->whereYear('tanggal', substr($bulan, 0, 4))
                  ->whereMonth('tanggal', substr($bulan, 5, 2));
            }
        })
        ->with('barang')
        ->select('barang_id', DB::raw('SUM(jumlah) as total_qty'), DB::raw('SUM(subtotal) as total_nilai'))
        ->groupBy('barang_id')
        ->orderByDesc('total_qty')
        ->limit(5)
        ->get();

        return view('laporan.penjualan', compact(
            'sales', 'filter', 'tanggal', 'bulan', 'labelPeriode',
            'totalPenjualan', 'totalTransaksi', 'rataRata', 'topItems'
        ));
    }

    /**
     * Laporan barang masuk.
     */
    public function barangMasuk(Request $request)
    {
        $tokoId = Auth::user()->toko_id;
        $dari = $request->get('dari', now()->startOfMonth()->toDateString());
        $sampai = $request->get('sampai', now()->toDateString());

        $stockIns = StockIn::with(['barang', 'user'])
            ->where('toko_id', $tokoId)
            ->whereBetween('tgl_masuk', [$dari, $sampai])
            ->orderBy('tgl_masuk', 'desc')
            ->get();

        $totalItem = $stockIns->sum('jumlah');
        $totalTransaksi = $stockIns->count();

        // Group by barang
        $perBarang = $stockIns->groupBy('barang_id')->map(function ($items) {
            return [
                'barang' => $items->first()->barang->nama_barang,
                'total' => $items->sum('jumlah'),
                'transaksi' => $items->count(),
            ];
        })->sortByDesc('total')->take(10);

        return view('laporan.barang-masuk', compact('stockIns', 'dari', 'sampai', 'totalItem', 'totalTransaksi', 'perBarang'));
    }

    /**
     * Laporan barang keluar.
     */
    public function barangKeluar(Request $request)
    {
        $tokoId = Auth::user()->toko_id;
        $dari = $request->get('dari', now()->startOfMonth()->toDateString());
        $sampai = $request->get('sampai', now()->toDateString());

        $stockOuts = StockOut::with(['barang', 'user'])
            ->where('toko_id', $tokoId)
            ->whereBetween('tgl_keluar', [$dari, $sampai])
            ->orderBy('tgl_keluar', 'desc')
            ->get();

        $totalItem = $stockOuts->sum('jumlah');
        $totalTransaksi = $stockOuts->count();

        // Group by alasan
        $perAlasan = $stockOuts->groupBy('alasan')->map(function ($items, $alasan) {
            return [
                'alasan' => ucfirst($alasan),
                'total' => $items->sum('jumlah'),
                'transaksi' => $items->count(),
            ];
        });

        return view('laporan.barang-keluar', compact('stockOuts', 'dari', 'sampai', 'totalItem', 'totalTransaksi', 'perAlasan'));
    }
}
