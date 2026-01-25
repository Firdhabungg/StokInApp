@extends('layouts.dashboard')

@section('title', 'Barang Masuk')
@section('page-title', 'Barang Masuk')
@section('page-description', 'Data dan riwayat barang masuk')

@section('content')
    {{-- Header Section --}}
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-xl sm:text-2xl font-bold text-gray-900">Riwayat Barang Masuk</h2>
                <p class="text-gray-500 mt-1 text-sm sm:text-base">Data stok barang yang masuk ke gudang</p>
            </div>
            <a href="{{ route('stock.in.create') }}"
                class="bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white px-4 sm:px-5 py-2.5 rounded-xl font-medium transition-all duration-300 shadow-lg shadow-green-500/25 hover:shadow-green-500/40 flex items-center justify-center gap-2 text-sm sm:text-base w-full sm:w-auto">
                <i class="fas fa-plus"></i>
                <span>Tambah Barang Masuk</span>
            </a>
        </div>
    </div>


    {{-- Search Card - Full Width --}}
    <div class="bg-white rounded-2xl shadow-sm p-5 mb-6">
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <i class="fas fa-search text-green-500 text-lg"></i>
            </div>
            <input type="text" id="customSearchInput" 
                class="w-full pl-12 pr-4 py-2 bg-white border-2 border-green-200/50 rounded-xl text-gray-700 placeholder-gray-400 focus:outline-none focus:border-green-400 focus:ring-4 focus:ring-green-100 transition-all duration-300 text-base shadow-sm"
                placeholder="Cari barang masuk berdasarkan nama, batch, atau supplier...">
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="overflow-x-auto">
            <table id="stockInTable" class="w-full text-sm display">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Barang</th>
                        <th>Jumlah</th>
                        <th>Kode Batch</th>
                        <th>Kadaluwarsa</th>
                        <th>Supplier</th>
                        <th>Diinput Oleh</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($stockIns as $stockIn)
                        <tr>
                            <td>{{ $stockIn->tgl_masuk->format('d/m/Y') }}</td>
                            <td class="font-medium text-gray-900">{{ $stockIn->barang->nama_barang }}</td>
                            <td>
                                <span class="bg-green-100 text-green-700 px-2 py-1 rounded-full text-xs font-medium">
                                    +{{ $stockIn->jumlah }}
                                </span>
                            </td>
                            <td class="font-mono text-xs">{{ $stockIn->batch->batch_code ?? '-' }}</td>
                            <td>{{ $stockIn->tgl_kadaluarsa ? $stockIn->tgl_kadaluarsa->format('d/m/Y') : '-' }}</td>
                            <td>{{ $stockIn->supplier ?? '-' }}</td>
                            <td>{{ $stockIn->user->name }}</td>
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
            table = new DataTable('#stockInTable', {
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
