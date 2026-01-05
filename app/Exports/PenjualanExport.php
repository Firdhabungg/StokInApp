<?php

namespace App\Exports;

use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PenjualanExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
{
    protected $filter;
    protected $tanggal;
    protected $bulan;

    public function __construct($filter = 'harian', $tanggal = null, $bulan = null)
    {
        $this->filter = $filter;
        $this->tanggal = $tanggal ?? now()->toDateString();
        $this->bulan = $bulan ?? now()->format('Y-m');
    }

    public function collection()
    {
        $tokoId = Auth::user()->toko_id;
        
        $query = Sale::with(['items.barang', 'user'])
            ->where('toko_id', $tokoId)
            ->where('status', 'selesai');

        if ($this->filter == 'harian') {
            $query->whereDate('tanggal', $this->tanggal);
        } else {
            $query->whereYear('tanggal', substr($this->bulan, 0, 4))
                ->whereMonth('tanggal', substr($this->bulan, 5, 2));
        }

        return $query->orderBy('tanggal', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'Kode Transaksi',
            'Tanggal',
            'Waktu',
            'Jumlah Item',
            'Total',
            'Metode Bayar',
            'Kasir',
        ];
    }

    public function map($sale): array
    {
        return [
            $sale->kode_transaksi,
            Carbon::parse($sale->tanggal)->format('d/m/Y'),
            $sale->created_at->format('H:i'),
            $sale->items->count(),
            $sale->total,
            ucfirst($sale->metode_pembayaran ?? 'tunai'),
            $sale->user->name,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function title(): string
    {
        if ($this->filter == 'harian') {
            return 'Penjualan ' . Carbon::parse($this->tanggal)->format('d-m-Y');
        }
        return 'Penjualan ' . Carbon::parse($this->bulan . '-01')->format('F Y');
    }
}
