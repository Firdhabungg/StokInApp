<div>
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('kategori.index') }}" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-800">{{ $this->kategori->nama_kategori }}</h1>
                <p class="text-sm text-gray-500">
                    {{ $this->kategori->barangs_count }} barang
                    @if ($this->kategori->deskripsi_kategori)
                        · {{ $this->kategori->deskripsi_kategori }}
                    @endif
                </p>
            </div>
        </div>
    </div>

    {{-- Flash Message --}}
    @if (session()->has('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    {{-- Search --}}
    <div class="bg-white rounded-2xl shadow-sm p-3 mb-3">
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <i class="fas fa-search text-amber-500 text-lg"></i>
            </div>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari barang..."
                class="w-full pl-12 pr-12 py-2 bg-white border-2 border-amber-200/50 rounded-xl text-gray-700 placeholder-gray-400 focus:outline-none focus:border-amber-400 focus:ring-4 focus:ring-amber-100 transition-all duration-300 text-base shadow-sm" />
        </div>
    </div>

    {{-- Tabel Barang --}}
    <div class="relative overflow-x-auto shadow-sm rounded-xl border border-gray-200">
        <table class="w-full text-sm text-left text-gray-600">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 font-semibold text-gray-700">Nama Barang</th>
                    <th class="px-6 py-3 font-semibold text-gray-700">Kode</th>
                    <th class="px-6 py-3 font-semibold text-gray-700">Stok</th>
                    <th class="px-6 py-3 font-semibold text-gray-700">Harga</th>
                    <th class="px-6 py-3 font-semibold text-gray-700">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($this->barangs as $barang)
                    <tr class="bg-white border-b border-gray-100 hover:bg-green-50 transition-colors duration-150">
                        <td class="px-6 py-4 font-medium text-gray-800">
                            {{ $barang->nama_barang }}
                        </td>
                        <td class="px-6 py-4 text-gray-500">
                            {{ $barang->kode_barang }}
                        </td>
                        <td class="px-6 py-4 text-gray-500">
                            {{ $barang->stok }}
                        </td>
                        <td class="px-6 py-4 text-gray-500">
                            Rp {{ number_format($barang->harga, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4">
                            @if ($barang->status === 'tersedia')
                                <span class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded-full">
                                    Tersedia
                                </span>
                            @else
                                <span class="bg-red-100 text-red-700 text-xs px-2 py-1 rounded-full">
                                    Habis
                                </span>
                            @endif
                        </td>
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
            {{ $this->barangs->links() }}
        </div>
    </div>

</div>
