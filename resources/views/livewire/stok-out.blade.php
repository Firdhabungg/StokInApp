<div>
    <div class="mb-2">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <h2 class="text-xl sm:text-2xl font-bold text-gray-900">Riwayat Barang Keluar</h2>
            <a href="{{ route('stock.out.create') }}"
                class="bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white sm:px-5 p-2 rounded-xl font-medium transition-all duration-300 shadow-lg shadow-red-500/25 hover:shadow-red-500/40 flex items-center justify-center gap-2 text-sm sm:text-base w-full sm:w-auto">
                <i class="fas fa-minus"></i>
                <span>Kurangi Stok</span>
            </a>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm p-3 mb-3">
        <div class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fas fa-search text-red-500 text-lg"></i>
                </div>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari barang keluar..."
                    class="w-full pl-12 pr-4 py-2 bg-white border-2 border-red-200/50 rounded-xl text-gray-700 placeholder-gray-400 focus:outline-none focus:border-red-400 focus:ring-4 focus:ring-red-100 transition-all duration-300 text-base shadow-sm">
            </div>

            <div>
                <input type="date" wire:model.live="tanggal"
                    class="px-4 py-2 bg-white border-2 border-red-200/50 rounded-xl text-gray-700 focus:outline-none focus:border-red-400 focus:ring-4 focus:ring-red-100 transition-all duration-300 text-sm shadow-sm">
            </div>

            @if ($search || $tanggal)
                <button wire:click="$set('search', ''); $set('tanggal', '')"
                    class="px-4 py-2 bg-gray-100 text-gray-600 hover:bg-gray-200 rounded-xl text-sm transition-colors whitespace-nowrap">
                    <i class="fas fa-redo mr-1"></i> Reset
                </button>
            @endif
        </div>
    </div>

    <div class="relative overflow-x-auto shadow-sm rounded-xl border border-gray-200">
        <table class="w-full text-sm text-left text-gray-600">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 font-semibold text-gray-700">Tanggal</th>
                    <th class="px-6 py-3 font-semibold text-gray-700">Barang</th>
                    <th class="px-6 py-3 font-semibold text-gray-700">Jumlah</th>
                    <th class="px-6 py-3 font-semibold text-gray-700">Dari Batch</th>
                    <th class="px-6 py-3 font-semibold text-gray-700">Alasan</th>
                    <th class="px-6 py-3 font-semibold text-gray-700">Diinput Oleh</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($stockOuts as $stockOut)
                    <tr class="bg-white border-b border-gray-100 hover:bg-amber-50 transition-colors duration-150"">
                        <td class="px-6 py-4">{{ $stockOut->tgl_keluar->format('d M Y') }}</td>
                        <td class="px-6 py-4 font-medium text-gray-900">{{ $stockOut->barang->nama_barang }}</td>
                        <td class="px-6 py-4">
                            <span class="bg-red-100 text-red-700 px-2 py-1 rounded-full text-xs font-medium">
                                -{{ $stockOut->jumlah }}
                            </span>
                        </td>
                        <td class="px-6 py-4 font-mono text-xs">{{ $stockOut->batch->batch_code ?? '-' }}</td>
                        <td class="px-6 py-4">
                            <span
                                class="px-2 py-1 rounded-full text-xs font-medium
                                @if ($stockOut->alasan == 'penjualan') bg-blue-100 text-blue-700
                                @elseif($stockOut->alasan == 'rusak') bg-orange-100 text-orange-700
                                @elseif($stockOut->alasan == 'kadaluarsa') bg-red-100 text-red-700
                                @elseif($stockOut->alasan == 'retur') bg-purple-100 text-purple-700
                                @else bg-gray-100 text-gray-700 @endif">
                                {{ ucfirst($stockOut->alasan) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">{{ $stockOut->user->name }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-6 text-gray-400">
                            <i class="fas fa-box-open text-3xl mb-2 block"></i>
                            Tidak ada data ditemukan
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4">
            {{ $stockOuts->links() }}
        </div>
    </div>

</div>
