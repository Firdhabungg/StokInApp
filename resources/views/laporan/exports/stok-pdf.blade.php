<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Stok</title>
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
        .status-tersedia {
            color: #16a34a;
            font-weight: bold;
        }
        .status-menipis {
            color: #ea580c;
            font-weight: bold;
        }
        .status-habis {
            color: #dc2626;
            font-weight: bold;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 9px;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $toko->name ?? 'Toko' }}</h1>
        <p>Laporan Stok Barang</p>
    </div>

    <div class="meta">
        <p>Dicetak pada: {{ now()->format('d F Y, H:i') }}</p>
    </div>

    <div class="summary">
        <span class="summary-item">
            Total Stok: <strong>{{ number_format($totalStok) }}</strong>
        </span>
        <span class="summary-item">
            Stok Menipis: <strong>{{ $stokMenipis }}</strong>
        </span>
        <span class="summary-item">
            Stok Habis: <strong>{{ $stokHabis }}</strong>
        </span>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Nama Barang</th>
                <th>Kategori</th>
                <th class="text-right">Stok</th>
                <th class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($barangs as $index => $barang)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $barang->kode_barang }}</td>
                <td>{{ $barang->nama_barang }}</td>
                <td>{{ $barang->kategori->nama_kategori ?? '-' }}</td>
                <td class="text-right">{{ number_format($barang->stok) }}</td>
                <td class="text-center status-{{ $barang->status }}">
                    {{ ucfirst($barang->status) }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Dokumen ini digenerate otomatis oleh sistem StokIn App</p>
    </div>
</body>
</html>
