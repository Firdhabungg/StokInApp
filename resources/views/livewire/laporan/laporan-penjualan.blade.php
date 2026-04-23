<div>
    <div class="flex justify-between items-center gap-2 mb-4">
        <a href="{{ route('laporan.index') }}" class="text-black bg-amber-400 hover:text-light rounded-xl px-3 py-1">
            <i class="fa-solid fa-circle-arrow-left text-sm mr-1"></i><span class="text-sm">Kembali</span>
        </a>

        <div class="flex items-center gap-2">
            @if ($canExportReport)
                <button wire:click="exportExcel"
                    class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition-colors text-sm">
                    <i class="fas fa-file-excel mr-2"></i> Export Excel
                </button>
                <button wire:click="exportPdf"
                    class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition-colors text-sm">
                    <i class="fas fa-file-pdf mr-2"></i> Export PDF
                </button>
            @else
                <button disabled
                    class="inline-flex items-center px-4 py-2 bg-gray-300 text-gray-500 rounded-lg font-medium cursor-not-allowed text-sm">
                    <i class="fas fa-lock mr-2"></i> Export (Pro)
                </button>
                <a href="{{ route('subscription.index') }}" class="text-sm text-amber-600 hover:underline">
                    Upgrade →
                </a>
            @endif
        </div>
    </div>

    {{-- Filter Periode --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-4 flex flex-wrap gap-4 items-end">
        {{-- Toggle Harian / Bulanan --}}
        <div>
            <label class="block text-sm font-medium mb-1 text-gray-600">Periode</label>
            <div class="flex rounded-lg overflow-hidden border">
                <button wire:click="$set('filter', 'harian')"
                    class="px-4 py-2 text-sm {{ $filter === 'harian' ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50' }}">
                    Harian
                </button>
                <button wire:click="$set('filter', 'bulanan')"
                    class="px-4 py-2 text-sm {{ $filter === 'bulanan' ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50' }}">
                    Bulanan
                </button>
            </div>
        </div>

        {{-- Input tanggal / bulan --}}
        @if ($filter === 'harian')
            <div>
                <label class="block text-sm font-medium mb-1 text-gray-600">Tanggal</label>
                <input type="date" wire:model.live="tanggal"
                    class="border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>
        @else
            <div>
                <label class="block text-sm font-medium mb-1 text-gray-600">Bulan</label>
                <input type="month" wire:model.live="bulan"
                    class="border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>
        @endif
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500">Periode</p>
            <p class="text-lg font-bold text-gray-800">{{ $labelPeriode }}</p>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500">Total Penjualan</p>
            <p class="text-lg font-bold text-blue-600">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500">Total Transaksi</p>
            <p class="text-lg font-bold text-gray-800">{{ $totalTransaksi }}</p>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500">Rata-rata per Transaksi</p>
            <p class="text-lg font-bold text-gray-800">Rp {{ number_format($rataRata, 0, ',', '.') }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        {{-- Top 5 Barang Terlaris --}}
        @if ($topItems->count())
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Top 5 Barang Terlaris</h3>
                <div class="space-y-2">
                    @foreach ($topItems as $i => $item)
                        <div class="flex items-center justify-between px-4 py-2 bg-gray-50 rounded-lg">
                            <div class="flex items-center gap-3">
                                <span class="text-xs font-bold text-gray-400">{{ $i + 1 }}</span>
                                <p class="text-sm font-medium text-gray-800">{{ $item->barang->nama_barang ?? '-' }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs font-semibold text-blue-600">{{ number_format($item->total_qty) }}
                                    pcs</p>
                                <p class="text-xs text-gray-500">Rp
                                    {{ number_format($item->total_nilai, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Tabel Penjualan --}}
        <div
            class="{{ $topItems->count() ? 'lg:col-span-2' : 'lg:col-span-3' }} bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Daftar Transaksi Penjualan</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-700">
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
                                <td class="px-4 py-3 font-medium">{{ $sale->no_nota }}</td>
                                <td class="px-4 py-3 text-gray-600">
                                    {{ \Carbon\Carbon::parse($sale->tanggal)->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-4 py-3 text-gray-600">{{ $sale->user->name ?? '-' }}</td>
                                <td class="px-4 py-3 text-right font-semibold">
                                    Rp {{ number_format($sale->total, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-8 text-center text-gray-400">
                                    Tidak ada data penjualan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Loading overlay --}}
    <div wire:loading class="fixed inset-0 bg-black/20 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg px-6 py-3 shadow text-sm">Memuat data...</div>
    </div>

    {{-- Notifikasi export denied --}}
    <div x-data="{ show: false, message: '' }"
        x-on:export-denied.window="show = true; message = $event.detail.message; setTimeout(() => show = false, 4000)"
        x-show="show" x-transition
        class="fixed bottom-4 right-4 bg-red-600 text-white px-5 py-3 rounded-lg shadow-lg text-sm z-50">
        <span x-text="message"></span>
    </div>
</div>
