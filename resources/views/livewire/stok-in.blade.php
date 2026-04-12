<div>
    <div class="mb-2">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <h2 class="text-xl sm:text-2xl font-bold text-gray-900">Riwayat Barang Masuk</h2>
            <a href="{{ route('stock.in.create') }}"
                class="bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white p-2 sm:px-5 rounded-xl font-medium transition-all duration-300 shadow-lg shadow-green-500/25 hover:shadow-green-500/40 flex items-center justify-center gap-2 text-sm sm:text-base w-full sm:w-auto">
                <i class="fas fa-plus"></i>
                <span>Tambah Stok</span>
            </a>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm p-3 mb-3">
        <div class="flex flex-col sm:flex-row gap-3">
            {{-- Search --}}
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fas fa-search text-green-500 text-lg"></i>
                </div>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari barang, supplier..."
                    class="w-full pl-12 pr-4 py-2 bg-white border-2 border-green-200/50 rounded-xl text-gray-700 placeholder-gray-400 focus:outline-none focus:border-green-400 focus:ring-4 focus:ring-green-100 transition-all duration-300 text-sm shadow-sm">
            </div>

            {{-- Filter Tanggal --}}
            <div>
                <input type="date" wire:model.live="tanggal"
                    class="px-4 py-2 bg-white border-2 border-green-200/50 rounded-xl text-gray-700 focus:outline-none focus:border-green-400 focus:ring-4 focus:ring-green-100 transition-all duration-300 text-sm shadow-sm">
            </div>

            {{-- Reset --}}
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
                    <th class="px-6 py-3 font-semibold text-gray-700 text-center">Jumlah</th>
                    <th class="px-6 py-3 font-semibold text-gray-700">Batch</th>
                    <th class="px-6 py-3 font-semibold text-gray-700">Expired</th>
                    <th class="px-6 py-3 font-semibold text-gray-700">Supplier</th>
                    <th class="px-6 py-3 font-semibold text-gray-700">User</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($stockIns as $stockIn)
                    <tr class="bg-white border-b border-gray-100 hover:bg-amber-50 transition-colors duration-150">
                        <td class="px-6 py-4">{{ $stockIn->tgl_masuk->format('d M Y') }}</td>
                        <td class="px-6 py-4 font-medium text-gray-900">{{ $stockIn->barang->nama_barang }}</td>
                        <td class="px-6 py-4 text-center">
                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">
                                +{{ $stockIn->jumlah }}
                            </span>
                        </td>

                        <td class="px-6 py-4 font-mono text-xs text-gray-500">
                            {{ $stockIn->batch->batch_code ?? '-' }}
                        </td>

                        <td class="px-6 py-4">
                            {{ $stockIn->tgl_kadaluarsa ? $stockIn->tgl_kadaluarsa->format('d M Y') : '-' }}
                        </td>

                        <td class="px-6 py-4">
                            {{ $stockIn->supplier ?? '-' }}
                        </td>

                        <td class="px-6 py-4 text-gray-700">
                            {{ $stockIn->user->name }}
                        </td>

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

        <!-- PAGINATION -->
        <div class="p-4">
            {{ $stockIns->links() }}
        </div>
    </div>
</div>
