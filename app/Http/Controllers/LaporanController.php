<?php

namespace App\Http\Controllers;

use App\Exports\BarangKeluarExport;
use App\Exports\BarangMasukExport;
use App\Exports\PenjualanExport;
use App\Exports\StokExport;
use App\Models\Barang;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\StockIn;
use App\Models\StockOut;
use App\Models\StockBatch;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{
    /**
     * Check if user can export and abort if not.
     */
    private function checkExportPermission()
    {
        $toko = Auth::user()->toko;
        if (!$toko || !$toko->canExportReport()) {
            abort(403, 'Fitur export tidak tersedia untuk paket langganan Anda. Silakan upgrade ke paket Pro atau Business.');
        }
    }
    /**
     * Dashboard laporan.
     */
    public function index()
    {
        $tokoId = Auth::user()->effective_toko_id;

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
        $tokoId = Auth::user()->effective_toko_id;
        $toko = Auth::user()->toko;

        $barangs = Barang::with('kategori')
            ->where('toko_id', $tokoId)
            ->orderBy('kategori_id')
            ->orderBy('nama_barang')
            ->get();

        // Summary per kategori
        $stokPerKategori = Barang::where('barangs.toko_id', $tokoId)
            ->join('kategoris', 'barangs.kategori_id', '=', 'kategoris.kategori_id')
            ->select('kategoris.nama_kategori', DB::raw('SUM(barangs.stok) as total_stok'), DB::raw('COUNT(barangs.id) as jumlah_barang'))
            ->groupBy('kategoris.nama_kategori')
            ->get();


        $totalStok = $barangs->sum('stok');
        $stokMenipis = $barangs->where('status', 'menipis')->count();
        $stokHabis = $barangs->where('status', 'habis')->count();

        // Check subscription feature
        $canExportReport = $toko ? $toko->canExportReport() : false;

        return view('laporan.stok', compact('barangs', 'stokPerKategori', 'totalStok', 'stokMenipis', 'stokHabis', 'canExportReport'));
    }

    /**
     * Laporan penjualan.
     */
    public function penjualan(Request $request)
    {
        $tokoId = Auth::user()->effective_toko_id;
        $toko = Auth::user()->toko;
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

        // Check subscription feature
        $canExportReport = $toko ? $toko->canExportReport() : false;

        return view('laporan.penjualan', compact(
            'sales',
            'filter',
            'tanggal',
            'bulan',
            'labelPeriode',
            'totalPenjualan',
            'totalTransaksi',
            'rataRata',
            'topItems',
            'canExportReport'
        ));
    }

    /**
     * Laporan barang masuk.
     */
    public function barangMasuk(Request $request)
    {
        $tokoId = Auth::user()->effective_toko_id;
        $toko = Auth::user()->toko;
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

        // Check subscription feature
        $canExportReport = $toko ? $toko->canExportReport() : false;

        return view('laporan.barang-masuk', compact('stockIns', 'dari', 'sampai', 'totalItem', 'totalTransaksi', 'perBarang', 'canExportReport'));
    }

    /**
     * Laporan barang keluar.
     */
    public function barangKeluar(Request $request)
    {
        $tokoId = Auth::user()->effective_toko_id;
        $toko = Auth::user()->toko;
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

        // Check subscription feature
        $canExportReport = $toko ? $toko->canExportReport() : false;

        return view('laporan.barang-keluar', compact('stockOuts', 'dari', 'sampai', 'totalItem', 'totalTransaksi', 'perAlasan', 'canExportReport'));
    }

    // ========================================
    // EXPORT METHODS
    // ========================================

    /**
     * Export laporan stok ke Excel.
     */
    public function exportStokExcel()
    {
        $this->checkExportPermission();
        
        $filename = 'laporan-stok-' . now()->format('Y-m-d') . '.xlsx';
        return Excel::download(new StokExport, $filename);
    }

    /**
     * Export laporan stok ke PDF.
     */
    public function exportStokPdf()
    {
        $this->checkExportPermission();
        
        $tokoId = Auth::user()->effective_toko_id;
        $toko = Auth::user()->toko;

        $barangs = Barang::with('kategori')
            ->where('toko_id', $tokoId)
            ->orderBy('kategori_id')
            ->orderBy('nama_barang')
            ->get();

        $totalStok = $barangs->sum('stok');
        $stokMenipis = $barangs->where('status', 'menipis')->count();
        $stokHabis = $barangs->where('status', 'habis')->count();

        $pdf = Pdf::loadView('laporan.exports.stok-pdf', compact('barangs', 'toko', 'totalStok', 'stokMenipis', 'stokHabis'));
        $pdf->setPaper('a4', 'portrait');
        
        return $pdf->download('laporan-stok-' . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Export laporan penjualan ke Excel.
     */
    public function exportPenjualanExcel(Request $request)
    {
        $this->checkExportPermission();
        
        $filter = $request->get('filter', 'harian');
        $tanggal = $request->get('tanggal', now()->toDateString());
        $bulan = $request->get('bulan', now()->format('Y-m'));
        
        $filename = 'laporan-penjualan-' . ($filter == 'harian' ? $tanggal : $bulan) . '.xlsx';
        return Excel::download(new PenjualanExport($filter, $tanggal, $bulan), $filename);
    }

    /**
     * Export laporan penjualan ke PDF.
     */
    public function exportPenjualanPdf(Request $request)
    {
        $this->checkExportPermission();
        
        $tokoId = Auth::user()->effective_toko_id;
        $toko = Auth::user()->toko;
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

        $pdf = Pdf::loadView('laporan.exports.penjualan-pdf', compact('sales', 'toko', 'labelPeriode', 'totalPenjualan', 'totalTransaksi', 'rataRata'));
        $pdf->setPaper('a4', 'portrait');
        
        return $pdf->download('laporan-penjualan-' . ($filter == 'harian' ? $tanggal : $bulan) . '.pdf');
    }

    /**
     * Export laporan barang masuk ke Excel.
     */
    public function exportBarangMasukExcel(Request $request)
    {
        $this->checkExportPermission();
        
        $dari = $request->get('dari', now()->startOfMonth()->toDateString());
        $sampai = $request->get('sampai', now()->toDateString());
        
        $filename = 'laporan-barang-masuk-' . $dari . '-' . $sampai . '.xlsx';
        return Excel::download(new BarangMasukExport($dari, $sampai), $filename);
    }

    /**
     * Export laporan barang masuk ke PDF.
     */
    public function exportBarangMasukPdf(Request $request)
    {
        $this->checkExportPermission();
        
        $tokoId = Auth::user()->effective_toko_id;
        $toko = Auth::user()->toko;
        $dari = $request->get('dari', now()->startOfMonth()->toDateString());
        $sampai = $request->get('sampai', now()->toDateString());

        $stockIns = StockIn::with(['barang', 'user'])
            ->where('toko_id', $tokoId)
            ->whereBetween('tgl_masuk', [$dari, $sampai])
            ->orderBy('tgl_masuk', 'desc')
            ->get();

        $totalItem = $stockIns->sum('jumlah');
        $totalTransaksi = $stockIns->count();

        $pdf = Pdf::loadView('laporan.exports.barang-masuk-pdf', compact('stockIns', 'toko', 'dari', 'sampai', 'totalItem', 'totalTransaksi'));
        $pdf->setPaper('a4', 'portrait');
        
        return $pdf->download('laporan-barang-masuk-' . $dari . '-' . $sampai . '.pdf');
    }

    /**
     * Export laporan barang keluar ke Excel.
     */
    public function exportBarangKeluarExcel(Request $request)
    {
        $this->checkExportPermission();
        
        $dari = $request->get('dari', now()->startOfMonth()->toDateString());
        $sampai = $request->get('sampai', now()->toDateString());
        
        $filename = 'laporan-barang-keluar-' . $dari . '-' . $sampai . '.xlsx';
        return Excel::download(new BarangKeluarExport($dari, $sampai), $filename);
    }

    /**
     * Export laporan barang keluar ke PDF.
     */
    public function exportBarangKeluarPdf(Request $request)
    {
        $this->checkExportPermission();
        
        $tokoId = Auth::user()->effective_toko_id;
        $toko = Auth::user()->toko;
        $dari = $request->get('dari', now()->startOfMonth()->toDateString());
        $sampai = $request->get('sampai', now()->toDateString());

        $stockOuts = StockOut::with(['barang', 'user'])
            ->where('toko_id', $tokoId)
            ->whereBetween('tgl_keluar', [$dari, $sampai])
            ->orderBy('tgl_keluar', 'desc')
            ->get();

        $totalItem = $stockOuts->sum('jumlah');
        $totalTransaksi = $stockOuts->count();

        $pdf = Pdf::loadView('laporan.exports.barang-keluar-pdf', compact('stockOuts', 'toko', 'dari', 'sampai', 'totalItem', 'totalTransaksi'));
        $pdf->setPaper('a4', 'portrait');
        
        return $pdf->download('laporan-barang-keluar-' . $dari . '-' . $sampai . '.pdf');
    }
}
