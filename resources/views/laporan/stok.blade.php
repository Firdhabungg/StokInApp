@extends('layouts.dashboard')

@section('title', 'Laporan Stok')
@section('page-title', 'Laporan Stok')
@section('page-description', 'Ringkasan kondisi stok dan ketersediaan inventaris')

@section('content')
    {{-- Export Buttons --}}
    <div class="flex justify-end gap-2 mb-4">
        @if($canExportReport)
            <a href="{{ route('laporan.stok.export.excel') }}" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition-colors">
                <i class="fas fa-file-excel mr-2"></i> Export Excel
            </a>
            <a href="{{ route('laporan.stok.export.pdf') }}" class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition-colors">
                <i class="fas fa-file-pdf mr-2"></i> Export PDF
            </a>
        @else
            <div class="flex items-center gap-2">
                <button disabled class="inline-flex items-center px-4 py-2 bg-gray-300 text-gray-500 rounded-lg font-medium cursor-not-allowed">
                    <i class="fas fa-lock mr-2"></i> Export (Pro)
                </button>
                <a href="{{ route('subscription.index') }}" class="text-sm text-amber-600 hover:underline">
                    Upgrade →
                </a>
            </div>
        @endif
    </div>

    {{-- Summary --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500">Total Stok</p>
            <p class="text-2xl font-bold text-blue-600">{{ number_format($totalStok) }} unit</p>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500">Stok Menipis</p>
            <p class="text-2xl font-bold text-orange-600">{{ $stokMenipis }} barang</p>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500">Stok Habis</p>
            <p class="text-2xl font-bold text-red-600">{{ $stokHabis }} barang</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Stok per Kategori --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Stok per Kategori</h3>
            <div class="space-y-3">
                @foreach ($stokPerKategori as $kategori)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div>
                            <p class="font-medium text-gray-900">{{ $kategori->nama_kategori }}</p>
                            <p class="text-xs text-gray-500">{{ $kategori->jumlah_barang }} jenis barang</p>
                        </div>
                        <span class="font-semibold text-blue-600">{{ number_format($kategori->total_stok) }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Daftar Stok --}}
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Daftar Stok Barang</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-left">Barang</th>
                            <th class="px-4 py-3 text-left">Kategori</th>
                            <th class="px-4 py-3 text-right">Stok</th>
                            <th class="px-4 py-3 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($barangs as $barang)
                            <tr class="border-b">
                                <td class="px-4 py-3">
                                    <p class="font-medium">{{ $barang->nama_barang }}</p>
                                    <p class="text-xs text-gray-500">{{ $barang->kode_barang }}</p>
                                </td>
                                <td class="px-4 py-3 text-gray-600">{{ $barang->kategori->nama_kategori ?? '-' }}</td>
                                <td class="px-4 py-3 text-right font-semibold">{{ number_format($barang->stok) }}</td>
                                <td class="px-4 py-3 text-center">
                                    <span class="px-2 py-1 rounded-full text-xs font-medium
                                        @if($barang->status == 'tersedia') bg-green-100 text-green-700
                                        @elseif($barang->status == 'menipis') bg-orange-100 text-orange-700
                                        @else bg-red-100 text-red-700 @endif">
                                        {{ ucfirst($barang->status) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
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
