@extends('layouts.dashboard')

@section('title', 'Barang Keluar')
@section('page-title', 'Barang Keluar')
@section('page-description', 'Data dan riwayat barang keluar')

@section('content')
    {{-- Header Section --}}
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Riwayat Barang Keluar</h2>
                <p class="text-gray-500 mt-1">Data stok barang yang keluar dari gudang</p>
            </div>
            <a href="{{ route('stock.out.create') }}"
                class="bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white px-5 py-2.5 rounded-xl font-medium transition-all duration-300 shadow-lg shadow-red-500/25 hover:shadow-red-500/40 flex items-center gap-2">
                <i class="fas fa-minus"></i>
                <span>Tambah Barang Keluar</span>
            </a>
        </div>
    </div>

    {{-- Search Card - Full Width --}}
    <div class="bg-white rounded-2xl shadow-sm p-5 mb-6">
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <i class="fas fa-search text-red-500 text-lg"></i>
            </div>
            <input type="text" id="customSearchInput" 
                class="w-full pl-12 pr-4 py-4 bg-white border-2 border-red-200/50 rounded-xl text-gray-700 placeholder-gray-400 focus:outline-none focus:border-red-400 focus:ring-4 focus:ring-red-100 transition-all duration-300 text-base shadow-sm"
                placeholder="Cari barang keluar berdasarkan nama, batch, atau alasan...">
        </div>
    </div>

    {{-- Table Card --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-x-auto">
            <table id="stockOutTable" class="w-full text-sm display">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Barang</th>
                        <th>Jumlah</th>
                        <th>Dari Batch</th>
                        <th>Alasan</th>
                        <th>Diinput Oleh</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($stockOuts as $stockOut)
                        <tr>
                            <td>{{ $stockOut->tgl_keluar->format('d/m/Y') }}</td>
                            <td class="font-medium text-gray-900">{{ $stockOut->barang->nama_barang }}</td>
                            <td>
                                <span class="bg-red-100 text-red-700 px-2 py-1 rounded-full text-xs font-medium">
                                    -{{ $stockOut->jumlah }}
                                </span>
                            </td>
                            <td class="font-mono text-xs">{{ $stockOut->batch->batch_code ?? '-' }}</td>
                            <td>
                                <span class="px-2 py-1 rounded-full text-xs font-medium
                                    @if($stockOut->alasan == 'penjualan') bg-blue-100 text-blue-700
                                    @elseif($stockOut->alasan == 'rusak') bg-orange-100 text-orange-700
                                    @elseif($stockOut->alasan == 'kadaluarsa') bg-gray-100 text-gray-700
                                    @elseif($stockOut->alasan == 'retur') bg-purple-100 text-purple-700
                                    @else bg-gray-100 text-gray-700
                                    @endif">
                                    {{ ucfirst($stockOut->alasan) }}
                                </span>
                            </td>
                            <td>{{ $stockOut->user->name }}</td>
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
            table = new DataTable('#stockOutTable', {
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
