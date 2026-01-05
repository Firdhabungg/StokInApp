@extends('layouts.dashboard')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-description', 'Ringkasan dan overview sistem manajemen stok')

@section('content')
    <div>
        <h1 class="text-xl font-semibold">
            Hello {{ Auth::user()->role_label }} {{ Auth::user()->toko_name }} 👋
        </h1>

        <p class="text-sm text-gray-500 mb-4">
            @if (Auth::user()->isOwner())
                Pantau performa toko dan laporan hari ini
            @elseif(Auth::user()->isKasir())
                Siap melayani transaksi hari ini
            @else
                Kelola sistem dan data aplikasi
            @endif
        </p>
    </div>

    {{-- Alert jika ada batch hampir kadaluarsa --}}
    @if ($batchHampirKadaluarsa > 0)
        <div class="mt-6    mb-6 p-4 bg-orange-50 border-l-4 border-orange-500 rounded-r-lg flex items-start gap-3">
            <div class="w-8 h-8 bg-orange-500 rounded-full flex items-center justify-center flex-shrink-0">
                <i class="fas fa-exclamation text-white text-sm"></i>
            </div>
            <div>
                <h4 class="font-semibold text-orange-800">Perhatian!</h4>
                <p class="text-sm text-orange-700">Ada {{ $batchHampirKadaluarsa }} batch barang yang akan kadaluarsa dalam 7
                    hari ke depan.</p>
            </div>
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <!-- Total Stok Barang -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <span class="text-sm text-gray-500">Total Stok Barang</span>
                <i class="fas fa-boxes text-amber-500"></i>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ number_format($totalStok) }}</p>
            <p class="text-sm text-gray-400 mt-1">{{ $totalBarang }} jenis produk</p>
        </div>

        <!-- Transaksi Hari Ini -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <span class="text-sm text-gray-500">Transaksi Hari Ini</span>
                <i class="fas fa-exchange-alt text-blue-500"></i>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ $barangMasukHariIni + $barangKeluarHariIni }}</p>
            <p class="text-sm text-gray-400 mt-1">
                <span class="text-green-500">+{{ $barangMasukHariIni }} masuk</span> •
                <span class="text-red-500">-{{ $barangKeluarHariIni }} keluar</span>
            </p>
        </div>

        <!-- Stok Menipis -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <span class="text-sm text-gray-500">Stok Menipis</span>
                <i class="fas fa-exclamation-triangle text-orange-500"></i>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ $stokMenipis }}</p>
            <p class="text-sm text-orange-500 mt-1">Perlu restock segera</p>
        </div>

        <!-- Batch Hampir Kadaluarsa -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <span class="text-sm text-gray-500">Hampir Kadaluarsa</span>
                <i class="fas fa-clock text-red-500"></i>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ $batchHampirKadaluarsa }}</p>
            <p class="text-sm text-red-500 mt-1">Dalam 7 hari</p>
        </div>
    </div>

    <!-- Status Batch Summary -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-green-50 rounded-xl p-4 border border-green-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-green-600">Batch Aman</p>
                    <p class="text-2xl font-bold text-green-700">{{ $statusBatch['aman'] }}</p>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <i class="fas fa-check-circle text-green-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-orange-50 rounded-xl p-4 border border-orange-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-orange-600">Hampir Kadaluarsa</p>
                    <p class="text-2xl font-bold text-orange-700">{{ $statusBatch['hampir_kadaluarsa'] }}</p>
                </div>
                <div class="bg-orange-100 p-3 rounded-full">
                    <i class="fas fa-exclamation-triangle text-orange-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-red-50 rounded-xl p-4 border border-red-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-red-600">Kadaluarsa</p>
                    <p class="text-2xl font-bold text-red-700">{{ $statusBatch['kadaluarsa'] }}</p>
                </div>
                <div class="bg-red-100 p-3 rounded-full">
                    <i class="fas fa-times-circle text-red-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Cards -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Stok Menipis -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="font-semibold text-gray-900">Stok Menipis</h3>
                    <p class="text-sm text-gray-500">Perlu segera direstock</p>
                </div>
                <a href="{{ route('stock.in.create') }}" class="text-amber-600 hover:text-amber-700 text-sm font-medium">
                    + Restock
                </a>
            </div>
            <div class="space-y-3">
                @forelse ($barangMenipis as $barang)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div>
                            <p class="font-medium text-gray-900">{{ $barang->nama_barang }}</p>
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

        <!-- Mendekati Kadaluarsa -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="font-semibold text-gray-900">Mendekati Kadaluarsa</h3>
                    <p class="text-sm text-gray-500">Prioritas penjualan</p>
                </div>
                <a href="{{ route('stock.batch.index') }}" class="text-amber-600 hover:text-amber-700 text-sm font-medium">
                    Lihat Semua
                </a>
            </div>
            <div class="space-y-3">
                @forelse ($listBatchKadaluarsa as $batch)
                    @php
                        $daysLeft = now()->diffInDays($batch->tgl_kadaluarsa, false);
                        $colorClass = $daysLeft <= 7 ? 'bg-red-100 text-red-600' : 'bg-orange-100 text-orange-600';
                    @endphp
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div>
                            <p class="font-medium text-gray-900">{{ $batch->barang->nama_barang }}</p>
                            <p class="text-xs text-gray-500">{{ $daysLeft }} hari lagi • Sisa:
                                {{ $batch->jumlah_sisa }}</p>
                        </div>
                        <span class="px-3 py-1 {{ $colorClass }} text-xs font-medium rounded-full">
                            {{ $batch->tgl_kadaluarsa->format('d M') }}
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

        <!-- Transaksi Terbaru -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="font-semibold text-gray-900">Transaksi Terbaru</h3>
                    <p class="text-sm text-gray-500">5 transaksi terakhir</p>
                </div>
            </div>
            <div class="space-y-3">
                @forelse ($transaksiTerbaru as $trx)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-8 h-8 rounded-full flex items-center justify-center {{ $trx['type'] == 'masuk' ? 'bg-green-100' : 'bg-red-100' }}">
                                <i
                                    class="fas fa-arrow-{{ $trx['type'] == 'masuk' ? 'down' : 'up' }} {{ $trx['type'] == 'masuk' ? 'text-green-600' : 'text-red-600' }} text-xs"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900 text-sm">{{ $trx['barang'] }}</p>
                                <p class="text-xs text-gray-500">{{ $trx['tanggal']->format('d M, H:i') }}</p>
                            </div>
                        </div>
                        <span class="font-semibold {{ $trx['type'] == 'masuk' ? 'text-green-600' : 'text-red-600' }}">
                            {{ $trx['type'] == 'masuk' ? '+' : '-' }}{{ $trx['jumlah'] }}
                        </span>
                    </div>
                @empty
                    <div class="text-center py-4 text-gray-500">
                        <i class="fas fa-inbox text-gray-400 text-2xl mb-2"></i>
                        <p class="text-sm">Belum ada transaksi</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
        <a href="{{ route('stock.in.create') }}"
            class="flex items-center gap-4 p-4 bg-green-50 hover:bg-green-100 rounded-xl border border-green-100 transition-colors">
            <div class="w-12 h-12 bg-green-500 rounded-xl flex items-center justify-center">
                <i class="fas fa-arrow-down text-white"></i>
            </div>
            <div>
                <p class="font-semibold text-green-800">Tambah Barang Masuk</p>
                <p class="text-sm text-green-600">Catat stok baru</p>
            </div>
        </a>
        <a href="{{ route('stock.out.create') }}"
            class="flex items-center gap-4 p-4 bg-red-50 hover:bg-red-100 rounded-xl border border-red-100 transition-colors">
            <div class="w-12 h-12 bg-red-500 rounded-xl flex items-center justify-center">
                <i class="fas fa-arrow-up text-white"></i>
            </div>
            <div>
                <p class="font-semibold text-red-800">Catat Barang Keluar</p>
                <p class="text-sm text-red-600">Kurangi stok (FIFO)</p>
            </div>
        </a>
        <a href="{{ route('barang.create') }}"
            class="flex items-center gap-4 p-4 bg-amber-50 hover:bg-amber-100 rounded-xl border border-amber-100 transition-colors">
            <div class="w-12 h-12 bg-amber-500 rounded-xl flex items-center justify-center">
                <i class="fas fa-plus text-white"></i>
            </div>
            <div>
                <p class="font-semibold text-amber-800">Tambah Barang Baru</p>
                <p class="text-sm text-amber-600">Daftarkan produk</p>
            </div>
        </a>
    </div>
@endsection
