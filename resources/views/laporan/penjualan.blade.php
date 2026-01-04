@extends('layouts.dashboard')

@section('title', 'Laporan Penjualan')
@section('page-title', 'Laporan Penjualan')
@section('page-description', 'Ringkasan data penjualan dan performa transaksi')

@section('content')
    {{-- Filter --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
        <form method="GET" class="flex flex-wrap items-end gap-4">
            <div>
                <label class="block text-sm text-gray-600 mb-1">Filter</label>
                <select name="filter" onchange="toggleFilter(this.value)" class="border border-gray-300 rounded-lg px-4 py-2">
                    <option value="harian" {{ $filter == 'harian' ? 'selected' : '' }}>Harian</option>
                    <option value="bulanan" {{ $filter == 'bulanan' ? 'selected' : '' }}>Bulanan</option>
                </select>
            </div>
            <div id="filterHarian" class="{{ $filter == 'bulanan' ? 'hidden' : '' }}">
                <label class="block text-sm text-gray-600 mb-1">Tanggal</label>
                <input type="date" name="tanggal" value="{{ $tanggal }}" class="border border-gray-300 rounded-lg px-4 py-2">
            </div>
            <div id="filterBulanan" class="{{ $filter == 'harian' ? 'hidden' : '' }}">
                <label class="block text-sm text-gray-600 mb-1">Bulan</label>
                <input type="month" name="bulan" value="{{ $bulan }}" class="border border-gray-300 rounded-lg px-4 py-2">
            </div>
            <button type="submit" class="bg-amber-600 hover:bg-amber-700 text-white px-6 py-2 rounded-lg">
                <i class="fas fa-filter mr-1"></i> Filter
            </button>
        </form>
    </div>

    {{-- Summary --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500">Total Penjualan</p>
            <p class="text-2xl font-bold text-green-600">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</p>
            <p class="text-xs text-gray-400 mt-1">{{ $labelPeriode }}</p>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500">Jumlah Transaksi</p>
            <p class="text-2xl font-bold text-blue-600">{{ $totalTransaksi }}</p>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500">Rata-rata per Transaksi</p>
            <p class="text-2xl font-bold text-amber-600">Rp {{ number_format($rataRata, 0, ',', '.') }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Top Items --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Top 5 Barang Terjual</h3>
            <div class="space-y-3">
                @forelse ($topItems as $item)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div>
                            <p class="font-medium text-gray-900">{{ $item->barang->nama_barang }}</p>
                            <p class="text-xs text-gray-500">{{ $item->total_qty }} terjual</p>
                        </div>
                        <span class="font-semibold text-green-600">Rp {{ number_format($item->total_nilai / 1000) }}k</span>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-4">Tidak ada data</p>
                @endforelse
            </div>
        </div>

        {{-- Sales List --}}
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Daftar Transaksi</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-left">Kode</th>
                            <th class="px-4 py-3 text-left">Waktu</th>
                            <th class="px-4 py-3 text-center">Item</th>
                            <th class="px-4 py-3 text-right">Total</th>
                            <th class="px-4 py-3 text-center">Kasir</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($sales as $sale)
                            <tr class="border-b">
                                <td class="px-4 py-3 font-mono text-xs">{{ $sale->kode_transaksi }}</td>
                                <td class="px-4 py-3">{{ $sale->created_at->format('d/m H:i') }}</td>
                                <td class="px-4 py-3 text-center">{{ $sale->items->count() }}</td>
                                <td class="px-4 py-3 text-right font-semibold text-green-600">
                                    Rp {{ number_format($sale->total, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-3 text-center">{{ $sale->user->name }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                    Tidak ada transaksi pada periode ini
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

@push('scripts')
<script>
    function toggleFilter(value) {
        document.getElementById('filterHarian').classList.toggle('hidden', value !== 'harian');
        document.getElementById('filterBulanan').classList.toggle('hidden', value !== 'bulanan');
    }
</script>
@endpush
