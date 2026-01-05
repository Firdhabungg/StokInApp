<?php

namespace App\Exports;

use App\Models\StockIn;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BarangMasukExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
{
    protected $dari;
    protected $sampai;

    public function __construct($dari = null, $sampai = null)
    {
        $this->dari = $dari ?? now()->startOfMonth()->toDateString();
        $this->sampai = $sampai ?? now()->toDateString();
    }

    public function collection()
    {
        $tokoId = Auth::user()->toko_id;
        
        return StockIn::with(['barang', 'user'])
            ->where('toko_id', $tokoId)
            ->whereBetween('tgl_masuk', [$this->dari, $this->sampai])
            ->orderBy('tgl_masuk', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Kode Barang',
            'Nama Barang',
            'Jumlah',
            'Harga Beli',
            'Supplier',
            'No. Batch',
            'Exp. Date',
            'Input By',
        ];
    }

    public function map($stockIn): array
    {
        return [
            Carbon::parse($stockIn->tgl_masuk)->format('d/m/Y'),
            $stockIn->barang->kode_barang ?? '-',
            $stockIn->barang->nama_barang ?? '-',
            $stockIn->jumlah,
            $stockIn->harga_beli ?? 0,
            $stockIn->supplier ?? '-',
            $stockIn->no_batch ?? '-',
            $stockIn->exp_date ? Carbon::parse($stockIn->exp_date)->format('d/m/Y') : '-',
            $stockIn->user->name ?? '-',
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
        return 'Barang Masuk ' . Carbon::parse($this->dari)->format('d-m-Y') . ' s/d ' . Carbon::parse($this->sampai)->format('d-m-Y');
    }
}
