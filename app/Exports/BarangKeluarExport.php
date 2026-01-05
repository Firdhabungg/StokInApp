<?php

namespace App\Exports;

use App\Models\StockOut;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BarangKeluarExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
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
        
        return StockOut::with(['barang', 'user'])
            ->where('toko_id', $tokoId)
            ->whereBetween('tgl_keluar', [$this->dari, $this->sampai])
            ->orderBy('tgl_keluar', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Kode Barang',
            'Nama Barang',
            'Jumlah',
            'Alasan',
            'Keterangan',
            'Input By',
        ];
    }

    public function map($stockOut): array
    {
        return [
            Carbon::parse($stockOut->tgl_keluar)->format('d/m/Y'),
            $stockOut->barang->kode_barang ?? '-',
            $stockOut->barang->nama_barang ?? '-',
            $stockOut->jumlah,
            ucfirst($stockOut->alasan ?? '-'),
            $stockOut->keterangan ?? '-',
            $stockOut->user->name ?? '-',
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
        return 'Barang Keluar ' . Carbon::parse($this->dari)->format('d-m-Y') . ' s/d ' . Carbon::parse($this->sampai)->format('d-m-Y');
    }
}
