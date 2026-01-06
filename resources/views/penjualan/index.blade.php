@extends('layouts.dashboard')

@section('title', 'Penjualan')
@section('page-title', 'Penjualan')
@section('page-description', 'Pencatatan dan pengelolaan transaksi penjualan')

@section('content')
    {{-- Summary Cards --}}
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

    {{-- Header Section --}}
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Riwayat Transaksi</h2>
                <p class="text-gray-500 mt-1">Kelola semua transaksi penjualan toko Anda</p>
            </div>
            <a href="{{ route('penjualan.create') }}"
                class="bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white px-5 py-2.5 rounded-xl font-medium transition-all duration-300 shadow-lg shadow-green-500/25 hover:shadow-green-500/40 flex items-center gap-2">
                <i class="fas fa-plus"></i>
                <span>Transaksi Baru</span>
            </a>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm p-5 mb-6">
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <i class="fas fa-search text-green-500 text-lg"></i>
            </div>
            <input type="text" id="customSearchInput" 
                class="w-full pl-12 pr-4 py-2 bg-white border-2 border-green-200/50 rounded-xl text-gray-700 placeholder-gray-400 focus:outline-none focus:border-green-400 focus:ring-4 focus:ring-green-100 transition-all duration-300 text-base shadow-sm"
                placeholder="Cari transaksi berdasarkan kode, tanggal, atau kasir...">
        </div>
    </div>

    {{-- Table Card --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="overflow-x-auto">
            <table id="penjualanTable" class="w-full text-sm display">
                <thead>
                    <tr>
                        <th>Kode Transaksi</th>
                        <th>Tanggal</th>
                        <th>Jumlah Item</th>
                        <th>Total</th>
                        <th>Kasir</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sales as $sale)
                        <tr>
                            <td class="font-mono font-medium text-gray-900">{{ $sale->kode_transaksi }}</td>
                            <td>{{ $sale->tanggal->format('d/m/Y H:i') }}</td>
                            <td class="text-center">{{ $sale->items->count() }} item</td>
                            <td class="font-semibold text-green-600">Rp {{ number_format($sale->total, 0, ',', '.') }}</td>
                            <td>{{ $sale->user->name }}</td>
                            <td>
                                <a href="{{ route('penjualan.show', $sale->id) }}" 
                                    class="text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let table;
        
        document.addEventListener('DOMContentLoaded', function() {
            table = new DataTable('#penjualanTable', {
                responsive: true,
                pageLength: 10,
                dom: 'lrtip',
                language: {
                    lengthMenu: 'Tampilkan _MENU_ data per halaman',
                    info: 'Menampilkan <b>_START_</b> sampai <b>_END_</b> dari <b>_TOTAL_</b> data',
                    paginate: {
                        first: '<<',
                        last: '>>',
                        next: '>',
                        previous: '<'
                    },
                    zeroRecords: 'Tidak ada data yang ditemukan',
                    infoEmpty: 'Menampilkan 0 data',
                    infoFiltered: '(disaring dari _MAX_ total data)'
                }
            });

            // Connect custom search input to DataTable
            const customSearch = document.getElementById('customSearchInput');
            customSearch.addEventListener('keyup', function() {
                table.search(this.value).draw();
            });
        });
    </script>
@endpush
