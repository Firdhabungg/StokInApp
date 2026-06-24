<div>
    @section('title', 'Laporan Barang')
    @section('page-title', 'Laporan Barang Keluar')
    @section('page-description', 'Buat Laporan Barang Keluar secara praktis dan efisien')
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-3 mb-4">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <form wire:submit.prevent class="flex flex-wrap items-end gap-3">
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Dari Tanggal</label>
                    <input wire:model.live="dari" type="date" name="dari" value="{{ $dari }}"
                        class="border border-gray-300 rounded-lg px-4 py-2">
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Sampai Tanggal</label>
                    <input wire:model.live="sampai" type="date" name="sampai" value="{{ $sampai }}"
                        class="border border-gray-300 rounded-lg px-4 py-2">
                </div>
                @if ($this->isFiltered())
                    <button wire:click.prevent="clearFilter"
                        class="px-3 py-1 bg-gray-100 text-gray-600 hover:bg-gray-200 transition-colors rounded-full"><i
                            class="fas fa-redo mr-1"></i></i>Reset</button>
                @endif
            </form>

            @if ($canExportReport ?? false)
                <div class="flex gap-2">
                    <a href="{{ route('laporan.barang-keluar.export.excel', ['dari' => $dari, 'sampai' => $sampai]) }}"
                        class="inline-flex items-center px-4 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition-colors">
                        <i class="fas fa-file-excel mr-2"></i>
                        <p class="text-xs">Export Excel</p>
                    </a>
                    <a href="{{ route('laporan.barang-keluar.export.pdf', ['dari' => $dari, 'sampai' => $sampai]) }}"
                        class="inline-flex items-center px-4 py-3 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition-colors">
                        <i class="fas fa-file-pdf mr-2"></i>
                        <p class="text-xs">Export PDF</p>
                    </a>
                </div>
            @else
                <div class="flex items-center gap-2">
                    <button disabled
                        class="inline-flex items-center px-4 py-2 bg-gray-300 text-gray-500 rounded-lg font-medium cursor-not-allowed">
                        <i class="fas fa-lock mr-2"></i> Export (Pro)
                    </button>
                    <a href="{{ route('subscription.index') }}" class="text-sm text-amber-600 hover:underline">Upgrade
                        →</a>
                </div>
            @endif
        </div>
    </div>

    <div class="mb-2">
        <a href="{{ route('laporan.index') }}" class="text-black bg-amber-400 hover:text-light rounded-xl px-3 py-1">
            <i class="fa-solid fa-circle-arrow-left text-sm mr-1"></i><span class="text-sm">Kembali</span>
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mb-4">
        <div class="bg-white rounded-xl p-3 shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500">Total Item Keluar</p>
            <p class="text-2xl font-bold text-red-600">{{ number_format($totalItem) }} unit</p>
        </div>
        <div class="bg-white rounded-xl p-3 shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500">Total Transaksi</p>
            <p class="text-2xl font-bold text-blue-600">{{ $totalTransaksi }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-3">
        {{-- Per Alasan --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Rekap per Alasan</h3>
            <div class="space-y-3">
                @foreach ($perAlasan as $item)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div>
                            <p class="font-medium text-gray-900">{{ $item['alasan'] }}</p>
                            <p class="text-xs text-gray-500">{{ $item['transaksi'] }} transaksi</p>
                        </div>
                        <span class="font-semibold text-red-600">{{ $item['total'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Daftar Barang Keluar</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-left">Tanggal</th>
                            <th class="px-4 py-3 text-left">Barang</th>
                            <th class="px-4 py-3 text-right">Jumlah</th>
                            <th class="px-4 py-3 text-center">Alasan</th>
                            <th class="px-4 py-3 text-center">User</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($stockOuts as $stockOut)
                            <tr class="border-b">
                                <td class="px-4 py-3">{{ $stockOut->tgl_keluar->format('d/m/Y') }}</td>
                                <td class="px-4 py-3 font-medium">{{ $stockOut->barang->nama_barang }}</td>
                                <td class="px-4 py-3 text-right font-semibold text-red-600">-{{ $stockOut->jumlah }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span
                                        class="px-2 py-1 rounded-full text-xs font-medium
                                        @if ($stockOut->alasan == 'penjualan') bg-green-100 text-green-700
                                        @elseif($stockOut->alasan == 'rusak') bg-red-100 text-red-700
                                        @elseif($stockOut->alasan == 'kadaluarsa') bg-orange-100 text-orange-700
                                        @else bg-gray-100 text-gray-700 @endif">
                                        {{ ucfirst($stockOut->alasan) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">{{ $stockOut->user->name }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                    Tidak ada data pada periode ini
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $stockOuts->links() }}
            </div>
        </div>
    </div>
</div>
