<div>
    <div class="mb-3">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <h2 class="text-xl sm:text-2xl font-bold text-gray-900">Data Barang</h2>
            @if (auth()->user()->canManageToko())
                <a href="{{ route('barang.create') }}"
                    class="bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white p-2 sm:px-5 rounded-xl font-medium transition-all duration-300 shadow-lg shadow-amber-500/25 hover:shadow-amber-500/40 flex items-center justify-center gap-2 text-sm sm:text-base w-full sm:w-auto">
                    <i class="fas fa-plus"></i>
                    <span>Tambah Barang</span>
                </a>
            @endif
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm p-3 mb-3">
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <i class="fas fa-search text-amber-500 text-lg"></i>
            </div>
            <input type="text" wire:model.live="search" placeholder="Cari barang..."
                class="w-full pl-12 pr-12 py-2 bg-white border-2 border-amber-200/50 rounded-xl text-gray-700 placeholder-gray-400 focus:outline-none focus:border-amber-400 focus:ring-4 focus:ring-amber-100 transition-all duration-300 text-base shadow-sm">
        </div>

        <div class="flex gap-2 mt-3 text-sm">
            <button wire:click="setStatus('tersedia')"
                class="px-3 py-1 bg-green-100 text-green-600 hover:bg-green-200 transition-colors rounded-full"><i
                    class="fas fa-check-circle mr-1"></i>Tersedia</button>
            <button wire:click="setStatus('menipis')"
                class="px-3 py-1 bg-orange-100 text-orange-600 hover:bg-orange-200 transition-colors rounded-full"><i
                    class="fas fa-exclamation-triangle mr-1"></i>Menipis</button>
            <button wire:click="setStatus('habis')"
                class="px-3 py-1 bg-red-100 text-red-600 hover:bg-red-200 transition-colors rounded-full"><i
                    class="fas fa-times-circle mr-1"></i> </i>Habis</button>
            <button wire:click="clearFilter"
                class="px-3 py-1 bg-gray-100 text-gray-600 hover:bg-gray-200 transition-colors rounded-full"><i
                    class="fas fa-redo mr-1"></i></i>Reset</button>
        </div>

    </div>

    <div class="relative overflow-x-auto shadow-sm rounded-xl border border-gray-200">
        <table class="w-full text-sm text-left text-gray-600">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 font-semibold text-gray-700">Kode Barang</th>
                    <th class="px-6 py-3 font-semibold text-gray-700">Nama Barang</th>
                    <th class="px-6 py-3 font-semibold text-gray-700">Kategori</th>
                    <th class="px-6 py-3 font-semibold text-gray-700">Harga Jual</th>
                    <th class="px-6 py-3 font-semibold text-gray-700">Stok</th>
                    <th class="px-6 py-3 font-semibold text-gray-700">Status</th>
                    <th class="px-6 py-3 font-semibold text-gray-700">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($barangs as $barang)
                    <tr class="bg-white border-b border-gray-100 hover:bg-amber-50 transition-colors duration-150">
                        <td class="px-6 py-4 text-gray-500">{{ $barang->kode_barang }}</td>
                        <td class="px-6 py-4 font-medium text-gray-900">{{ $barang->nama_barang }}</td>
                        <td class="px-6 py-4">{{ $barang->kategori->nama_kategori ?? '-' }}</td>
                        <td class="px-6 py-4">Rp {{ number_format($barang->harga_jual, 0, ',', '.') }}</td>
                        <td class="px-6 py-4">{{ $barang->stok }}</td>
                        <td class="px-6 py-4">
                            @if ($barang->status === 'tersedia')
                                <span
                                    class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium">Tersedia</span>
                            @elseif($barang->status === 'menipis')
                                <span
                                    class="px-3 py-1 bg-orange-100 text-orange-700 rounded-full text-xs font-medium">Menipis</span>
                            @else
                                <span
                                    class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-medium">Habis</span>
                            @endif
                        </td>
                        @if (auth()->user()->canManageToko())
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('barang.edit', $barang->id) }}"
                                        class="text-amber-600 hover:text-amber-800 font-medium text-xs">Edit</a>
                                    <form action="{{ route('barang.destroy', $barang->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                            class="text-red-500 hover:text-red-700 font-medium text-xs"
                                            onclick="deleteBarang({{ $barang->id }}, '{{ $barang->nama_barang }}')">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-gray-400 my-6">
                            <i class="fas fa-box-open text-4xl my-3"></i>
                            <p>Belum ada barang di kategori ini</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4">
            {{ $barangs->links() }}
        </div>
    </div>

</div>
