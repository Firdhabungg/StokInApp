@extends('layouts.dashboard')

@section('title', 'Daftar Batch Stok')
@section('page-title', 'Daftar Batch Stok')
@section('page-description', 'Informasi stok barang per batch beserta masa berlaku')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
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

    {{-- Header Section --}}
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Semua Batch Stok</h2>
                <p class="text-gray-500 mt-1">Informasi stok barang per batch beserta masa berlaku</p>
            </div>
        </div>
    </div>

    {{-- Search Card - Full Width --}}
    <div class="bg-white rounded-2xl shadow-sm p-5 mb-6">
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <i class="fas fa-search text-amber-500 text-lg"></i>
            </div>
            <input type="text" id="customSearchInput" 
                class="w-full pl-12 pr-4 py-4 bg-white border-2 border-amber-200/50 rounded-xl text-gray-700 placeholder-gray-400 focus:outline-none focus:border-amber-400 focus:ring-4 focus:ring-amber-100 transition-all duration-300 text-base shadow-sm"
                placeholder="Cari batch berdasarkan kode, nama barang, atau status...">
        </div>
        <div class="flex items-center gap-4 mt-3 text-sm text-gray-500">
            <span class="flex items-center gap-1.5">
                <i class="fas fa-filter text-amber-500"></i>
                <span>Filter cepat:</span>
            </span>
            <button type="button" onclick="filterByStatus('aman')" class="filter-btn px-3 py-1 bg-green-100 text-green-600 rounded-full hover:bg-green-200 transition-colors font-medium">
                <i class="fas fa-check-circle mr-1"></i> Aman
            </button>
            <button type="button" onclick="filterByStatus('hampir')" class="filter-btn px-3 py-1 bg-orange-100 text-orange-600 rounded-full hover:bg-orange-200 transition-colors font-medium">
                <i class="fas fa-exclamation-triangle mr-1"></i> Hampir Kadaluarsa
            </button>
            <button type="button" onclick="filterByStatus('kadaluarsa')" class="filter-btn px-3 py-1 bg-red-100 text-red-600 rounded-full hover:bg-red-200 transition-colors font-medium">
                <i class="fas fa-times-circle mr-1"></i> Kadaluarsa
            </button>
            <button type="button" onclick="clearFilter()" class="filter-btn px-3 py-1 bg-gray-100 text-gray-600 rounded-full hover:bg-gray-200 transition-colors font-medium">
                <i class="fas fa-redo mr-1"></i> Reset
            </button>
        </div>
    </div>

    {{-- Table Card --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="overflow-x-auto">
           <table id="batchTable" class="w-full text-sm display">
                <thead>
                    <tr>
                        <th class="px-6 py-3">Kode Batch</th>
                        <th class="px-6 py-3">Barang</th>
                        <th class="px-6 py-3">Jumlah Awal</th>
                        <th class="px-6 py-3">Sisa</th>
                        <th class="px-6 py-3">Tgl Masuk</th>
                        <th class="px-6 py-3">Tgl Kadaluarsa</th>
                        <th class="px-6 py-3">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($batches as $batch)
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="px-6 py-4 font-mono text-xs">{{ $batch->batch_code }}</td>
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $batch->barang->nama_barang }}</td>
                            <td class="px-6 py-4">{{ $batch->jumlah_awal }}</td>
                            <td class="px-6 py-4">
                                <span class="font-semibold {{ $batch->jumlah_sisa > 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $batch->jumlah_sisa }}
                                </span>
                            </td>
                            <td class="px-6 py-4">{{ $batch->tgl_masuk->format('d/m/Y') }}</td>
                            <td class="px-6 py-4">{{ $batch->tgl_kadaluarsa ? $batch->tgl_kadaluarsa->format('d/m/Y') : '-' }}</td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-medium
                                    @if($batch->status == 'aman') bg-green-100 text-green-700
                                    @elseif($batch->status == 'hampir_kadaluarsa') bg-orange-100 text-orange-700
                                    @else bg-red-100 text-red-700
                                    @endif">
                                    {{ str_replace('_', ' ', ucfirst($batch->status)) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                <i class="fas fa-inbox text-4xl mb-2"></i>
                                <p>Belum ada data batch</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div> 
    </div>
@endsection

@push('scripts')
    <script> 
        let table;
        
        document.addEventListener('DOMContentLoaded', function() {
            // Only initialize if table has data rows
            if (document.querySelectorAll('#batchTable tbody tr').length > 0 && 
                !document.querySelector('#batchTable tbody tr td[colspan]')) {
                table = new DataTable('#batchTable', {
                    responsive: true,
                    pageLength: 10,
                    dom: 'lrtip',
                    columnDefs: [
                        { targets: '_all', defaultContent: '-' }
                    ],
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
                        infoFiltered: '(disaring dari _MAX_ total data)',
                        emptyTable: 'Belum ada data batch'
                    }
                });

                // Connect custom search input to DataTable
                const customSearch = document.getElementById('customSearchInput');
                customSearch.addEventListener('keyup', function() {
                    table.search(this.value).draw();
                });
            }
        });

        // Filter by status
        function filterByStatus(status) {
            if (table) {
                document.getElementById('customSearchInput').value = status;
                table.search(status).draw();
                
                // Update active filter button
                document.querySelectorAll('.filter-btn').forEach(btn => {
                    btn.classList.remove('ring-2', 'ring-offset-1');
                });
                event.target.closest('.filter-btn').classList.add('ring-2', 'ring-offset-1');
            }
        }

        // Clear filter
        function clearFilter() {
            if (table) {
                document.getElementById('customSearchInput').value = '';
                table.search('').draw();
                
                // Remove active state from all filter buttons
                document.querySelectorAll('.filter-btn').forEach(btn => {
                    btn.classList.remove('ring-2', 'ring-offset-1');
                });
            }
        }
    </script>
@endpush