<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Barang Keluar</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
            color: #1a1a1a;
        }
        .header p {
            margin: 5px 0 0;
            color: #666;
            font-size: 12px;
        }
        .meta {
            margin-bottom: 15px;
            font-size: 10px;
            color: #666;
        }
        .summary {
            display: flex;
            margin-bottom: 15px;
        }
        .summary-item {
            display: inline-block;
            margin-right: 20px;
            padding: 8px 12px;
            background: #f5f5f5;
            border-radius: 4px;
        }
        .summary-item strong {
            display: block;
            font-size: 14px;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #333;
        }
        tr:nth-child(even) {
            background-color: #fafafa;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .text-red {
            color: #dc2626;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 9px;
            color: #999;
        }
        .total-row {
            font-weight: bold;
            background-color: #e5e7eb !important;
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: bold;
        }
        .badge-penjualan { background: #dcfce7; color: #16a34a; }
        .badge-rusak { background: #fee2e2; color: #dc2626; }
        .badge-kadaluarsa { background: #ffedd5; color: #ea580c; }
        .badge-lainnya { background: #f3f4f6; color: #4b5563; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $toko->name ?? 'Toko' }}</h1>
        <p>Laporan Barang Keluar</p>
        <p style="font-size: 11px;">Periode: {{ \Carbon\Carbon::parse($dari)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($sampai)->format('d/m/Y') }}</p>
    </div>

    <div class="meta">
        <p>Dicetak pada: {{ now()->format('d F Y, H:i') }}</p>
    </div>

    <div class="summary">
        <span class="summary-item">
            Total Item Keluar: <strong class="text-red">{{ number_format($totalItem) }} unit</strong>
        </span>
        <span class="summary-item">
            Total Transaksi: <strong>{{ $totalTransaksi }}</strong>
        </span>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Nama Barang</th>
                <th class="text-right">Jumlah</th>
                <th class="text-center">Alasan</th>
                <th>Keterangan</th>
                <th>Input By</th>
            </tr>
        </thead>
        <tbody>
            @forelse($stockOuts as $index => $stockOut)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($stockOut->tgl_keluar)->format('d/m/Y') }}</td>
                <td>{{ $stockOut->barang->nama_barang ?? '-' }}</td>
                <td class="text-right text-red">-{{ $stockOut->jumlah }}</td>
                <td class="text-center">
                    <span class="badge badge-{{ $stockOut->alasan ?? 'lainnya' }}">
                        {{ ucfirst($stockOut->alasan ?? 'lainnya') }}
                    </span>
                </td>
                <td>{{ $stockOut->keterangan ?? '-' }}</td>
                <td>{{ $stockOut->user->name ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">Tidak ada data</td>
            </tr>
            @endforelse
            @if($stockOuts->count() > 0)
            <tr class="total-row">
                <td colspan="3" class="text-right">TOTAL:</td>
                <td class="text-right text-red">-{{ number_format($totalItem) }}</td>
                <td colspan="3"></td>
            </tr>
            @endif
        </tbody>
    </table>

    <div class="footer">
        <p>Dokumen ini digenerate otomatis oleh sistem StokIn App</p>
    </div>
</body>
</html>
