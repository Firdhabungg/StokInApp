@extends('layouts.dashboard')

@section('title', 'Laporan Barang Masuk')
@section('page-title', 'Laporan Barang Masuk')

@section('content')
    {{-- Filter --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
        <form method="GET" class="flex flex-wrap items-end gap-4">
            <div>
                <label class="block text-sm text-gray-600 mb-1">Dari Tanggal</label>
                <input type="date" name="dari" value="{{ $dari }}" class="border border-gray-300 rounded-lg px-4 py-2">
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">Sampai Tanggal</label>
                <input type="date" name="sampai" value="{{ $sampai }}" class="border border-gray-300 rounded-lg px-4 py-2">
            </div>
            <button type="submit" class="bg-amber-600 hover:bg-amber-700 text-white px-6 py-2 rounded-lg">
                <i class="fas fa-filter mr-1"></i> Filter
            </button>
        </form>
    </div>

    {{-- Summary --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500">Total Item Masuk</p>
            <p class="text-2xl font-bold text-teal-600">{{ number_format($totalItem) }} unit</p>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500">Total Transaksi</p>
            <p class="text-2xl font-bold text-blue-600">{{ $totalTransaksi }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Top Barang --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Top 10 Barang Masuk</h3>
            <div class="space-y-2">
                @foreach ($perBarang as $item)
                    <div class="flex items-center justify-between p-2 bg-gray-50 rounded">
                        <span class="text-sm text-gray-700 truncate">{{ $item['barang'] }}</span>
                        <span class="font-semibold text-teal-600">{{ $item['total'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- List --}}
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Daftar Barang Masuk</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-left">Tanggal</th>
                            <th class="px-4 py-3 text-left">Barang</th>
                            <th class="px-4 py-3 text-right">Jumlah</th>
                            <th class="px-4 py-3 text-left">Supplier</th>
                            <th class="px-4 py-3 text-center">User</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($stockIns as $stockIn)
                            <tr class="border-b">
                                <td class="px-4 py-3">{{ $stockIn->tgl_masuk->format('d/m/Y') }}</td>
                                <td class="px-4 py-3 font-medium">{{ $stockIn->barang->nama_barang }}</td>
                                <td class="px-4 py-3 text-right font-semibold text-teal-600">+{{ $stockIn->jumlah }}</td>
                                <td class="px-4 py-3 text-gray-600">{{ $stockIn->supplier ?? '-' }}</td>
                                <td class="px-4 py-3 text-center">{{ $stockIn->user->name }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                    Tidak ada data pada periode ini
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <a href="{{ route('laporan.index') }}" class="text-amber-600 hover:text-amber-700">
            <i class="fas fa-arrow-left mr-1"></i> Kembali ke Laporan
        </a>
    </div>
@endsection
