<div>
    @section('title', 'Laporan')
    @section('page-title', 'Kelola Laporan')
    <h1 class="text-center font-semibold text-red-500 text-2xl mb-4">Ditambahkan Grafik atau Chart</h1>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between gap-3">
                <div class="text-center">
                    <p class="text-sm text-gray-500">Total Barang</p>
                    <p class="text-xl font-bold text-gray-900">{{ number_format($totalBarang) }}</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-lg">
                    <i class="fa-solid fa-box-open text-blue-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between gap-3">
                <div class="text-center">
                    <p class="text-sm text-gray-500">Total Stok</p>
                    <p class="text-xl font-bold text-gray-900">{{ number_format($totalStok) }}</p>
                </div>
                <div class="bg-green-100 p-3 rounded-lg">
                    <i class="fas fa-cubes text-green-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between gap-3">
                <div class="text-center">
                    <p class="text-sm text-gray-500">Penjualan Bulan Ini</p>
                    <p class="text-xl font-bold text-gray-900">Rp {{ number_format($penjualanBulanIni / 1000) }}k</p>
                </div>
                <div class="bg-amber-100 p-3 rounded-lg">
                    <i class="fas fa-money-bill text-amber-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between gap-3">
                <div class="text-center">
                    <p class="text-sm text-gray-500">Barang Masuk</p>
                    <p class="text-xl font-bold text-gray-900">{{ $transaksiMasukBulanIni }}</p>
                </div>
                <div class="bg-teal-100 p-3 rounded-lg">
                    <i class="fa-solid fa-cart-plus text-teal-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between gap-3">
                <div class="text-center">
                    <p class="text-sm text-gray-500">Barang Keluar</p>
                    <p class="text-xl font-bold text-gray-900">{{ $transaksiKeluarBulanIni }}</p>
                </div>
                <div class="bg-red-100 p-3 rounded-lg">
                    <i class="fa-solid fa-cart-arrow-down text-red-600"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Report Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <a href="{{ route('laporan.stok') }}"
            class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow group">
            <div
                class="w-14 h-14 bg-blue-500 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                <i class="fas fa-warehouse text-white text-2xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-1">Laporan Stok</h3>
            <p class="text-sm text-gray-500">Lihat stok per barang dan kategori</p>
        </a>

        <a href="{{ route('laporan.penjualan') }}"
            class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow group">
            <div
                class="w-14 h-14 bg-green-500 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                <i class="fas fa-chart-line text-white text-2xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-1">Laporan Penjualan</h3>
            <p class="text-sm text-gray-500">Rekap penjualan harian & bulanan</p>
        </a>

        <a href="{{ route('laporan.barang-masuk') }}"
            class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow group">
            <div
                class="w-14 h-14 bg-teal-500 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                <i class="fas fa-truck-loading text-white text-2xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-1">Laporan Barang Masuk</h3>
            <p class="text-sm text-gray-500">Rekap barang masuk per periode</p>
        </a>

        <a href="{{ route('laporan.barang-keluar') }}"
            class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow group">
            <div
                class="w-14 h-14 bg-red-500 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                <i class="fas fa-dolly text-white text-2xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-1">Laporan Barang Keluar</h3>
            <p class="text-sm text-gray-500">Rekap barang keluar per alasan</p>
        </a>
    </div>
</div>
