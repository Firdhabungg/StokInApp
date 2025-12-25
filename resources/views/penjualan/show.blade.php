@extends('layouts.dashboard')

@section('title', 'Detail Transaksi')
@section('page-title', 'Detail Transaksi')

@section('content')
    <div class="max-w-3xl mx-auto">
        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded mb-4 flex items-center gap-3">
                <i class="fas fa-check-circle text-green-500"></i>
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            {{-- Header --}}
            <div class="text-center border-b pb-4 mb-4">
                <h1 class="text-2xl font-bold text-gray-900">{{ $penjualan->toko->name }}</h1>
                <p class="text-gray-500">{{ $penjualan->toko->address }}</p>
                <p class="text-gray-500">Telp: {{ $penjualan->toko->phone }}</p>
            </div>

            {{-- Transaction Info --}}
            <div class="grid grid-cols-2 gap-4 mb-6 text-sm">
                <div>
                    <p class="text-gray-500">Kode Transaksi</p>
                    <p class="font-mono font-semibold">{{ $penjualan->kode_transaksi }}</p>
                </div>
                <div class="text-right">
                    <p class="text-gray-500">Tanggal</p>
                    <p class="font-semibold">{{ $penjualan->tanggal->format('d/m/Y H:i') }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Kasir</p>
                    <p class="font-semibold">{{ $penjualan->user->name }}</p>
                </div>
                <div class="text-right">
                    <p class="text-gray-500">Status</p>
                    <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
                        {{ ucfirst($penjualan->status) }}
                    </span>
                </div>
            </div>

            {{-- Items --}}
            <div class="border rounded-lg overflow-hidden mb-6">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left">Barang</th>
                            <th class="px-4 py-3 text-center">Qty</th>
                            <th class="px-4 py-3 text-right">Harga</th>
                            <th class="px-4 py-3 text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($penjualan->items as $item)
                            <tr class="border-t">
                                <td class="px-4 py-3">
                                    <p class="font-medium">{{ $item->barang->nama_barang }}</p>
                                    <p class="text-xs text-gray-500">{{ $item->barang->kode_barang }}</p>
                                </td>
                                <td class="px-4 py-3 text-center">{{ $item->jumlah }}</td>
                                <td class="px-4 py-3 text-right">Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                                <td class="px-4 py-3 text-right font-semibold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50 font-semibold">
                        <tr>
                            <td colspan="3" class="px-4 py-3 text-right">Total</td>
                            <td class="px-4 py-3 text-right text-lg text-green-600">
                                Rp {{ number_format($penjualan->total, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            @if ($penjualan->keterangan)
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <p class="text-sm text-gray-500">Keterangan:</p>
                    <p class="text-gray-700">{{ $penjualan->keterangan }}</p>
                </div>
            @endif

            {{-- Actions --}}
            <div class="flex gap-3 justify-center">
                <a href="{{ route('penjualan.index') }}" 
                    class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
                <button onclick="window.print()" 
                    class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                    <i class="fas fa-print mr-2"></i>Cetak
                </button>
                <a href="{{ route('penjualan.create') }}" 
                    class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg">
                    <i class="fas fa-plus mr-2"></i>Transaksi Baru
                </a>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<style>
    @media print {
        body * { visibility: hidden; }
        .max-w-3xl, .max-w-3xl * { visibility: visible; }
        .max-w-3xl { position: absolute; left: 0; top: 0; width: 100%; }
        .flex.gap-3 { display: none !important; }
    }
</style>
@endpush
