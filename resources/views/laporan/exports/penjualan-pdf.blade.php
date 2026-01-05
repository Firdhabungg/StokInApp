<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Penjualan</title>
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
        .text-green {
            color: #16a34a;
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
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $toko->name ?? 'Toko' }}</h1>
        <p>Laporan Penjualan - {{ $labelPeriode }}</p>
    </div>

    <div class="meta">
        <p>Dicetak pada: {{ now()->format('d F Y, H:i') }}</p>
    </div>

    <div class="summary">
        <span class="summary-item">
            Total Penjualan: <strong class="text-green">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</strong>
        </span>
        <span class="summary-item">
            Total Transaksi: <strong>{{ $totalTransaksi }}</strong>
        </span>
        <span class="summary-item">
            Rata-rata: <strong>Rp {{ number_format($rataRata, 0, ',', '.') }}</strong>
        </span>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Transaksi</th>
                <th>Tanggal</th>
                <th>Waktu</th>
                <th class="text-center">Item</th>
                <th class="text-right">Total</th>
                <th>Kasir</th>
            </tr>
        </thead>
        <tbody>
            @forelse($sales as $index => $sale)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $sale->kode_transaksi }}</td>
                <td>{{ \Carbon\Carbon::parse($sale->tanggal)->format('d/m/Y') }}</td>
                <td>{{ $sale->created_at->format('H:i') }}</td>
                <td class="text-center">{{ $sale->items->count() }}</td>
                <td class="text-right text-green">Rp {{ number_format($sale->total, 0, ',', '.') }}</td>
                <td>{{ $sale->user->name }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">Tidak ada data</td>
            </tr>
            @endforelse
            @if($sales->count() > 0)
            <tr class="total-row">
                <td colspan="5" class="text-right">TOTAL:</td>
                <td class="text-right text-green">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</td>
                <td></td>
            </tr>
            @endif
        </tbody>
    </table>

    <div class="footer">
        <p>Dokumen ini digenerate otomatis oleh sistem StokIn App</p>
    </div>
</body>
</html>
