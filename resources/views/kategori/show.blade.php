@extends('layouts.dashboard')

@section('title', 'Detail Kategori - ' . $kategori->nama_kategori)

@section('content')
    {{-- Header Section --}}
    <div class="mb-6">
        <div class="flex items-center gap-3 mb-4">
            <a href="{{ route('kategori.index') }}" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ $kategori->nama_kategori }}</h2>
                @if ($kategori->deskripsi_kategori)
                    <p class="text-gray-500 mt-1">{{ $kategori->deskripsi_kategori }}</p>
                @endif
            </div>
        </div>
        <div class="flex items-center justify-between">
            <span class="px-3 py-1 bg-amber-100 text-amber-700 rounded-full text-sm font-medium">
                {{ $kategori->barangs_count }} barang
            </span>
            <div class="flex gap-2">
                <a href="{{ route('kategori.edit', $kategori->kategori_id) }}"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition-colors">
                    <i class="fas fa-edit mr-2"></i>Edit Kategori
                </a>
                <a href="{{ route('barang.create') }}"
                    class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-lg text-sm font-medium transition-colors">
                    <i class="fas fa-plus mr-2"></i>Tambah Barang
                </a>
            </div>
        </div>
    </div>

    {{-- Barang List --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        @if ($barangs->count() > 0)
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Barang</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga Supplier</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga Jual</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stok</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($barangs as $barang)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-600">
                                {{ $barang->kode_barang }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <p class="font-medium text-gray-900">{{ $barang->nama_barang }}</p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                Rp {{ number_format($barang->harga, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                Rp {{ number_format($barang->harga_jual, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $barang->stok }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($barang->status == 'tersedia')
                                    <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-700 rounded-full">Tersedia</span>
                                @elseif ($barang->status == 'menipis')
                                    <span class="px-2 py-1 text-xs font-medium bg-orange-100 text-orange-700 rounded-full">Menipis</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-medium bg-red-100 text-red-700 rounded-full">Habis</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <a href="{{ route('barang.edit', $barang->id) }}" class="text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="p-8 text-center">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-box-open text-gray-400 text-2xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-700 mb-2">Belum ada barang</h3>
                <p class="text-gray-500 mb-4">Kategori ini belum memiliki barang</p>
                <a href="{{ route('barang.create') }}"
                    class="inline-flex items-center gap-2 bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-plus"></i>
                    <span>Tambah Barang</span>
                </a>
            </div>
        @endif
    </div>
@endsection
