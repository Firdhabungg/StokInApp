<?php

namespace App\Http\Controllers;

use App\Exports\BarangKeluarExport;
use App\Exports\BarangMasukExport;
use App\Exports\PenjualanExport;
use App\Exports\StokExport;
use App\Models\Barang;
use App\Models\Sale;
use App\Models\StockIn;
use App\Models\StockOut;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class LaporanExportController extends Controller
{
    private function checkPermission(): void
    {
        $toko = Auth::user()->toko;
        if (! $toko || ! $toko->canExportReport()) {
            abort(403, 'Fitur export tidak tersedia untuk paket langganan Anda. Silakan upgrade ke paket Pro atau Business.');
        }
    }

    public function stokExcel()
    {
        $this->checkPermission();

        return Excel::download(
            new StokExport,
            'laporan-stok-' . now()->format('Y-m-d') . '.xlsx'
        );
    }

    public function stokPdf()
    {
        $this->checkPermission();

        $tokoId = Auth::user()->effective_toko_id;
        $toko   = Auth::user()->toko;

        $barangs = Barang::with('kategori')
            ->where('toko_id', $tokoId)
            ->orderBy('kategori_id')
            ->orderBy('nama_barang')
            ->get();

        $totalStok   = $barangs->sum('stok');
        $stokMenipis = $barangs->where('status', 'menipis')->count();
        $stokHabis   = $barangs->where('status', 'habis')->count();

        $pdf = Pdf::loadView('laporan.exports.stok-pdf', compact(
            'barangs',
            'toko',
            'totalStok',
            'stokMenipis',
            'stokHabis'
        ))->setPaper('a4', 'portrait');

        return $pdf->download('laporan-stok-' . now()->format('Y-m-d') . '.pdf');
    }

    public function penjualanExcel(Request $request)
    {
        $this->checkPermission();

        $filter  = $request->get('filter', 'harian');
        $tanggal = $request->get('tanggal', now()->toDateString());
        $bulan   = $request->get('bulan', now()->format('Y-m'));

        $suffix = $filter === 'harian' ? $tanggal : $bulan;

        return Excel::download(
            new PenjualanExport($filter, $tanggal, $bulan),
            'laporan-penjualan-' . $suffix . '.xlsx'
        );
    }

    public function penjualanPdf(Request $request)
    {
        $this->checkPermission();

        $tokoId  = Auth::user()->effective_toko_id;
        $toko    = Auth::user()->toko;
        $filter  = $request->get('filter', 'harian');
        $tanggal = $request->get('tanggal', now()->toDateString());
        $bulan   = $request->get('bulan', now()->format('Y-m'));

        $query = Sale::with(['items.barang', 'user'])
            ->where('toko_id', $tokoId)
            ->where('status', 'selesai');

        if ($filter === 'harian') {
            $query->whereDate('tanggal', $tanggal);
            $labelPeriode = Carbon::parse($tanggal)->format('d F Y');
        } else {
            $query->whereYear('tanggal', substr($bulan, 0, 4))
                ->whereMonth('tanggal', substr($bulan, 5, 2));
            $labelPeriode = Carbon::parse($bulan . '-01')->format('F Y');
        }

        $sales          = $query->orderBy('tanggal', 'desc')->get();
        $totalPenjualan = $sales->sum('total');
        $totalTransaksi = $sales->count();
        $rataRata       = $totalTransaksi > 0 ? $totalPenjualan / $totalTransaksi : 0;
        $suffix         = $filter === 'harian' ? $tanggal : $bulan;

        $pdf = Pdf::loadView('laporan.exports.penjualan-pdf', compact(
            'sales',
            'toko',
            'labelPeriode',
            'totalPenjualan',
            'totalTransaksi',
            'rataRata'
        ))->setPaper('a4', 'portrait');

        return $pdf->download('laporan-penjualan-' . $suffix . '.pdf');
    }

    public function barangMasukExcel(Request $request)
    {
        $this->checkPermission();

        $dari   = $request->get('dari', now()->startOfMonth()->toDateString());
        $sampai = $request->get('sampai', now()->toDateString());

        return Excel::download(
            new BarangMasukExport($dari, $sampai),
            'laporan-barang-masuk-' . $dari . '-' . $sampai . '.xlsx'
        );
    }

    public function barangMasukPdf(Request $request)
    {
        $this->checkPermission();

        $tokoId = Auth::user()->effective_toko_id;
        $toko   = Auth::user()->toko;
        $dari   = $request->get('dari', now()->startOfMonth()->toDateString());
        $sampai = $request->get('sampai', now()->toDateString());

        $stockIns       = StockIn::with(['barang', 'user'])
            ->where('toko_id', $tokoId)
            ->whereBetween('tgl_masuk', [$dari, $sampai])
            ->orderBy('tgl_masuk', 'desc')
            ->get();

        $totalItem      = $stockIns->sum('jumlah');
        $totalTransaksi = $stockIns->count();

        $pdf = Pdf::loadView('laporan.exports.barang-masuk-pdf', compact(
            'stockIns',
            'toko',
            'dari',
            'sampai',
            'totalItem',
            'totalTransaksi'
        ))->setPaper('a4', 'portrait');

        return $pdf->download('laporan-barang-masuk-' . $dari . '-' . $sampai . '.pdf');
    }

    public function barangKeluarExcel(Request $request)
    {
        $this->checkPermission();

        $dari   = $request->get('dari', now()->startOfMonth()->toDateString());
        $sampai = $request->get('sampai', now()->toDateString());

        return Excel::download(
            new BarangKeluarExport($dari, $sampai),
            'laporan-barang-keluar-' . $dari . '-' . $sampai . '.xlsx'
        );
    }

    public function barangKeluarPdf(Request $request)
    {
        $this->checkPermission();

        $tokoId = Auth::user()->effective_toko_id;
        $toko   = Auth::user()->toko;
        $dari   = $request->get('dari', now()->startOfMonth()->toDateString());
        $sampai = $request->get('sampai', now()->toDateString());

        $stockOuts      = StockOut::with(['barang', 'user'])
            ->where('toko_id', $tokoId)
            ->whereBetween('tgl_keluar', [$dari, $sampai])
            ->orderBy('tgl_keluar', 'desc')
            ->get();

        $totalItem      = $stockOuts->sum('jumlah');
        $totalTransaksi = $stockOuts->count();

        $pdf = Pdf::loadView('laporan.exports.barang-keluar-pdf', compact(
            'stockOuts',
            'toko',
            'dari',
            'sampai',
            'totalItem',
            'totalTransaksi'
        ))->setPaper('a4', 'portrait');

        return $pdf->download('laporan-barang-keluar-' . $dari . '-' . $sampai . '.pdf');
    }
}
