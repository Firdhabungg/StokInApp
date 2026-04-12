<div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Penjualan Hari Ini</p>
                    <p class="text-2xl font-bold text-green-600">Rp {{ number_format($totalHariIni, 0, ',', '.') }}</p>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <i class="fas fa-money-bill-wave text-green-600 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Transaksi Hari Ini</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $transaksiHariIni }}</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    <i class="fas fa-receipt text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Header --}}
    <div class="mb-3">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <h2 class="text-xl sm:text-2xl font-bold text-gray-900">Riwayat Transaksi</h2>
            <a href="{{ route('penjualan.create') }}"
                class="bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white px-3 py-2.5 rounded-xl font-medium transition-all duration-300 shadow-lg shadow-green-500/25 flex items-center justify-center gap-2 text-sm w-full sm:w-auto">
                <i class="fas fa-plus"></i>
                <span>Transaksi Baru</span>
            </a>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm p-3 mb-3">
        <div class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fas fa-search text-green-500"></i>
                </div>
                <input type="text" wire:model.live.debounce.300ms="search"
                    placeholder="Cari kode transaksi atau kasir..."
                    class="w-full pl-12 pr-4 py-2 bg-white border-2 border-green-200/50 rounded-xl text-gray-700 placeholder-gray-400 focus:outline-none focus:border-green-400 focus:ring-4 focus:ring-green-100 transition-all duration-300 text-sm shadow-sm">
            </div>

            <div>
                <input type="date" wire:model.live="tanggal"
                    class="px-4 py-2 bg-white border-2 border-green-200/50 rounded-xl text-gray-700 focus:outline-none focus:border-green-400 focus:ring-4 focus:ring-green-100 transition-all duration-300 text-sm shadow-sm">
            </div>
            @if ($search || $tanggal)
                <button wire:click="$set('search', ''); $set('tanggal', '')"
                    class="px-4 py-2 bg-gray-100 text-gray-600 hover:bg-gray-200 rounded-xl text-sm transition-colors">
                    <i class="fas fa-redo mr-1"></i> Reset
                </button>
            @endif
        </div>
    </div>

    {{-- Table --}}
    <div class="relative overflow-x-auto shadow-sm rounded-xl border border-gray-200">
        <table class="w-full text-sm text-left text-gray-600">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 font-semibold text-gray-700">Kode Transaksi</th>
                    <th class="px-6 py-3 font-semibold text-gray-700">Tanggal</th>
                    <th class="px-6 py-3 font-semibold text-gray-700 text-center">Jumlah Item</th>
                    <th class="px-6 py-3 font-semibold text-gray-700">Total</th>
                    <th class="px-6 py-3 font-semibold text-gray-700">Kasir</th>
                    <th class="px-6 py-3 font-semibold text-gray-700">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sales as $sale)
                    <tr class="bg-white border-b border-gray-100 hover:bg-green-50 transition-colors duration-150">
                        <td class="px-6 py-4 font-mono font-medium text-gray-900">{{ $sale->kode_transaksi }}
                        </td>
                        <td class="px-6 py-4">{{ $sale->tanggal->format('d M Y (H:i)') }}</td>
                        <td class="px-6 py-4 text-center">{{ $sale->items_count }} item</td>
                        <td class="px-6 py-4 font-semibold text-green-600">Rp
                            {{ number_format($sale->total, 0, ',', '.') }}</td>
                        <td class="px-6 py-4">{{ $sale->user->name }}</td>
                        <td class="px-6 py-4">
                            <a href="{{ route('penjualan.show', $sale->id) }}"
                                class="text-amber-600 hover:text-amber-800 text-sm font-medium">
                                <i class="fas fa-eye mr-1"></i>Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                            <i class="fas fa-receipt text-3xl mb-2 block"></i>
                            Tidak ada transaksi ditemukan
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4">
            {{ $sales->links() }}
        </div>
    </div>
</div>
