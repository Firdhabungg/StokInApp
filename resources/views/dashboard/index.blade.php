@extends('layouts.dashboard')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-description', 'Ringkasan dan overview sistem manajemen stok')

@section('content')

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-3 mb-3">
        @livewire('dashboard.transaksi-chart')
        @livewire('dashboard.barang-terlaris')
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-3">
        <div class="bg-red-500 rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="font-semibold text-white">Stok Menipis</h3>
                    <p class="text-sm text-gray-200">Perlu segera direstock</p>
                </div>
                <a href="{{ route('stock.in.create') }}"
                    class="text-green-600 hover:text-green-700 text-sm font-medium bg-green-100 px-3 py-1 rounded-lg">
                    + Restock
                </a>
            </div>
            <div class="space-y-2">
                @forelse ($barangMenipis as $barang)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div>
                            <p class="font-medium text-sm text-gray-900">{{ $barang->nama_barang }}</p>
                            <p class="text-xs text-gray-500">Stok: {{ $barang->stok }} item</p>
                        </div>
                        <span
                            class="px-3 py-1 {{ $barang->stok <= 5 ? 'bg-red-100 text-red-600' : 'bg-orange-100 text-orange-600' }} text-xs font-medium rounded-full">
                            {{ $barang->stok <= 5 ? 'Kritis' : 'Menipis' }}
                        </span>
                    </div>
                @empty
                    <div class="text-center py-4 text-gray-500">
                        <i class="fas fa-check-circle text-green-500 text-2xl mb-2"></i>
                        <p class="text-sm">Semua stok aman</p>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="bg-amber-500 rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="font-semibold text-white">Mendekati Kadaluwarsa</h3>
                    <p class="text-sm text-gray-200">Prioritas penjualan</p>
                </div>
                <a href="{{ route('stock.batch.index') }}" class="text-black hover:text-black text-sm font-medium bg-amber-200 px-3 py-1 rounded-lg">
                    Lihat Semua
                </a>
            </div>
            <div class="space-y-2">
                @forelse ($listBatchKadaluarsa as $batch)
                    @php
                        $daysLeft = (int) now()->diffInDays($batch->tgl_kadaluarsa, false);
                        $colorClass = $daysLeft <= 7 ? 'bg-red-100 text-red-600' : 'bg-orange-100 text-orange-600';
                    @endphp
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div>
                            <p class="font-medium text-sm text-gray-900">{{ $batch->barang->nama_barang }}</p>
                            <p class="text-xs text-gray-500">{{ $daysLeft }} hari lagi •
                                Sisa:{{ $batch->jumlah_sisa }}
                            </p>
                        </div>
                        <span class="px-3 {{ $colorClass }} text-xs rounded-xl">
                            {{ $batch->tgl_kadaluarsa->format('d M Y') }}
                        </span>
                    </div>
                @empty
                    <div class="text-center py-4 text-gray-500">
                        <i class="fas fa-check-circle text-green-500 text-2xl mb-2"></i>
                        <p class="text-sm">Tidak ada batch mendekati kadaluarsa</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
