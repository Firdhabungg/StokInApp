@extends('layouts.dashboard')

@section('title', 'Laporan Penjualan')
@section('page-title', 'Laporan Penjualan')
@section('page-description', 'Ringkasan kondisi stok dan ketersediaan inventaris')

@section('content')
    <div>
        <div class="mb-6 flex flex-wrap gap-4 items-end">

            {{-- Toggle Harian / Bulanan --}}
            <div>
                <label class="block text-sm font-medium mb-1">Periode</label>
                <div class="flex rounded-lg overflow-hidden border">
                    <button wire:click="$set('filter', 'harian')"
                        class="px-4 py-2 text-sm {{ $filter === 'harian' ? 'bg-blue-600 text-white' : 'bg-white text-gray-700' }}">Harian</button>
                    <button wire:click="$set('filter', 'bulanan')"
                        class="px-4 py-2 text-sm {{ $filter === 'bulanan' ? 'bg-blue-600 text-white' : 'bg-white text-gray-700' }}">Bulanan</button>
                </div>
            </div>

            {{-- Input tanggal (harian) --}}
            @if ($filter === 'harian')
                <div>
                    <label class="block text-sm font-medium mb-1">Tanggal</label>
                    <input type="date" wire:model.live="tanggal" class="border rounded-lg px-3 py-2 text-sm" />
                </div>
            @else
                <div>
                    <label class="block text-sm font-medium mb-1">Bulan</label>
                    <input type="month" wire:model.live="bulan" class="border rounded-lg px-3 py-2 text-sm" />
                </div>
            @endif

            {{-- Export buttons --}}
            @if ($canExportReport)
                <div class="flex gap-2 ml-auto">
                    <button wire:click="exportExcel"
                        class="px-4 py-2 bg-green-600 text-white rounded-lg text-sm hover:bg-green-700">
                        Export Excel
                    </button>
                    <button wire:click="exportPdf"
                        class="px-4 py-2 bg-red-600 text-white rounded-lg text-sm hover:bg-red-700">
                        Export PDF
                    </button>
                </div>
            @else
                <div class="ml-auto">
                    <button disabled class="px-4 py-2 bg-gray-300 text-gray-500 rounded-lg text-sm cursor-not-allowed"
                        title="Upgrade ke Pro/Business untuk export">
                        🔒 Export
                    </button>
                </div>
            @endif
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
            <div class="bg-white rounded-xl shadow p-4">
                <p class="text-sm text-gray-500">Periode</p>
                <p class="text-lg font-semibold">{{ $labelPeriode }}</p>
            </div>
            <div class="bg-white rounded-xl shadow p-4">
                <p class="text-sm text-gray-500">Total Penjualan</p>
                <p class="text-lg font-semibold text-blue-600">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</p>
            </div>
            <div class="bg-white rounded-xl shadow p-4">
                <p class="text-sm text-gray-500">Total Transaksi</p>
                <p class="text-lg font-semibold">{{ $totalTransaksi }}</p>
            </div>
            <div class="bg-white rounded-xl shadow p-4">
                <p class="text-sm text-gray-500">Rata-rata per Transaksi</p>
                <p class="text-lg font-semibold">Rp {{ number_format($rataRata, 0, ',', '.') }}</p>
            </div>
        </div>

        @if ($topItems->count())
            <div class="bg-white rounded-xl shadow p-4 mb-6">
                <h3 class="font-semibold text-gray-700 mb-3">Top 5 Barang Terlaris</h3>
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-gray-500 border-b">
                            <th class="pb-2">#</th>
                            <th class="pb-2">Barang</th>
                            <th class="pb-2 text-right">Qty</th>
                            <th class="pb-2 text-right">Nilai</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($topItems as $i => $item)
                            <tr class="border-b last:border-0">
                                <td class="py-2 text-gray-400">{{ $i + 1 }}</td>
                                <td class="py-2">{{ $item->barang->nama_barang ?? '-' }}</td>
                                <td class="py-2 text-right">{{ number_format($item->total_qty) }}</td>
                                <td class="py-2 text-right">Rp {{ number_format($item->total_nilai, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <div class="bg-white rounded-xl shadow overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-500">
                    <tr>
                        <th class="px-4 py-3 text-left">No. Nota</th>
                        <th class="px-4 py-3 text-left">Tanggal</th>
                        <th class="px-4 py-3 text-left">Kasir</th>
                        <th class="px-4 py-3 text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse ($sales as $sale)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">{{ $sale->no_nota }}</td>
                            <td class="px-4 py-3">{{ \Carbon\Carbon::parse($sale->tanggal)->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-3">{{ $sale->user->name ?? '-' }}</td>
                            <td class="px-4 py-3 text-right font-medium">Rp {{ number_format($sale->total, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-gray-400">Tidak ada data penjualan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Loading overlay --}}
        <div wire:loading class="fixed inset-0 bg-black/20 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg px-6 py-3 shadow text-sm">Memuat data...</div>
        </div>

        <div x-data="{ show: false, message: '' }"
            x-on:export-denied.window="show = true; message = $event.detail.message; setTimeout(() => show = false, 4000)"
            x-show="show" x-transition
            class="fixed bottom-4 right-4 bg-red-600 text-white px-5 py-3 rounded-lg shadow-lg text-sm z-50">
            <span x-text="message"></span>
        </div>
    </div>

@endsection
