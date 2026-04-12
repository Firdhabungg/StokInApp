<div>
    <h1 class="mb-3 text-2xl font-bold text-gray-900">Daftar Batch Stok</h1>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-3">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Batch Aman</p>
                    <p class="text-2xl font-bold text-green-600">{{ $statusCounts['aman'] }}</p>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Hampir Kadaluarsa</p>
                    <p class="text-2xl font-bold text-orange-600">{{ $statusCounts['hampir_kadaluarsa'] }}</p>
                </div>
                <div class="bg-orange-100 p-3 rounded-full">
                    <i class="fas fa-exclamation-triangle text-orange-600 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Kadaluarsa</p>
                    <p class="text-2xl font-bold text-red-600">{{ $statusCounts['kadaluarsa'] }}</p>
                </div>
                <div class="bg-red-100 p-3 rounded-full">
                    <i class="fas fa-times-circle text-red-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm p-3 mb-3">
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <i class="fas fa-search text-amber-500 text-lg"></i>
            </div>
            <input type="text" wire:model.live="search" placeholder="Cari Batch"
                class="w-full pl-12 pr-4 py-2 bg-white border-2 border-amber-200/50 rounded-xl text-gray-700 placeholder-gray-400 focus:outline-none focus:border-amber-400 focus:ring-4 focus:ring-amber-100 transition-all duration-300 text-base shadow-sm">
        </div>
        <div class="flex items-center gap-4 mt-3 text-sm text-gray-500">
            <button type="button" wire:click="setStatus('aman')"
                class="filter-btn px-3 py-1 bg-green-100 text-green-600 rounded-full hover:bg-green-200 transition-colors font-medium">
                <i class="fas fa-check-circle mr-1"></i> Aman
            </button>
            <button type="button" wire:click="setStatus('hampir_kadaluarsa')"
                class="filter-btn px-3 py-1 bg-orange-100 text-orange-600 rounded-full hover:bg-orange-200 transition-colors font-medium">
                <i class="fas fa-exclamation-triangle mr-1"></i> Hampir Kadaluarsa
            </button>
            <button type="button" wire:click="setStatus('kadaluarsa')"
                class="filter-btn px-3 py-1 bg-red-100 text-red-600 rounded-full hover:bg-red-200 transition-colors font-medium">
                <i class="fas fa-times-circle mr-1"></i> Kadaluarsa
            </button>
            <button wire:click="clearFilter"
                class="filter-btn px-3 py-1 bg-gray-100 text-gray-600 rounded-full hover:bg-gray-200 transition-colors font-medium">
                <i class="fas fa-redo mr-1"></i> Reset
            </button>
        </div>
    </div>

    <div class="relative overflow-x-auto shadow-sm rounded-xl border border-gray-200">
        <table class="w-full text-sm text-left text-gray-600">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 font-semibold text-gray-700">Kode Batch</th>
                    <th class="px-6 py-3 font-semibold text-gray-700">Barang</th>
                    <th class="px-6 py-3 font-semibold text-gray-700">Jumlah Awal</th>
                    <th class="px-6 py-3 font-semibold text-gray-700">Sisa</th>
                    <th class="px-6 py-3 font-semibold text-gray-700">Tgl Masuk</th>
                    <th class="px-6 py-3 font-semibold text-gray-700">Tgl Kadaluarsa</th>
                    <th class="px-6 py-3 font-semibold text-gray-700">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($batches as $batch)
                    <tr class="bg-white border-b border-gray-100 hover:bg-green-50 transition-colors duration-150">
                        <td class="px-6 py-4 font-mono text-xs">{{ $batch->batch_code }}</td>
                        <td class="px-6 py-4 font-medium text-gray-900">{{ $batch->barang->nama_barang }}</td>
                        <td class="px-6 py-4 text-center">{{ $batch->jumlah_awal }}</td>
                        <td class="px-6 py-4 text-center">
                            <span
                                class="font-semibold {{ $batch->jumlah_sisa > 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $batch->jumlah_sisa }}
                            </span>
                        </td>
                        <td class="px-6 py-4">{{ $batch->tgl_masuk->format('d M Y') }}</td>
                        <td class="px-6 py-4">
                            {{ $batch->tgl_kadaluarsa ? $batch->tgl_kadaluarsa->format('d M Y') : '-' }}</td>
                        <td class="px-6 py-4">
                            <span
                                class="px-3 py-1 rounded-full text-xs font-medium
                                    @if ($batch->status == 'aman') bg-green-100 text-green-700
                                    @elseif($batch->status == 'hampir_kadaluarsa') bg-orange-100 text-orange-700
                                    @else bg-red-100 text-red-700 @endif">
                                {{ str_replace('_', ' ', ucfirst($batch->status)) }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                            <i class="fas fa-inbox text-4xl mb-2"></i>
                            <p>Batch tidak ditemukan</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4">
            {{ $batches->links() }}
        </div>
    </div>
</div>
