@extends('layouts.dashboard')

@section('title', 'Data Barang')
@section('page-title', 'Kelola Barang')
@section('page-description', 'Monitoring data barang, stok, dan ketersediaan (readonly)')

@section('content')
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Data Barang</h2>
            </div>
            @if (auth()->user()->canManageToko())
                <a href="{{ route('barang.create') }}"
                    class="bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white px-5 py-2.5 rounded-xl font-medium transition-all duration-300 shadow-lg shadow-amber-500/25 hover:shadow-amber-500/40 flex items-center gap-2">
                    <i class="fas fa-plus"></i>
                    <span>Tambah Barang</span>
                </a>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm p-5 mb-6">
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <i class="fas fa-search text-amber-500 text-lg"></i>
            </div>
            <input type="text" id="customSearchInput" 
                class="w-full pl-12 pr-12 py-2 bg-white border-2 border-amber-200/50 rounded-xl text-gray-700 placeholder-gray-400 focus:outline-none focus:border-amber-400 focus:ring-4 focus:ring-amber-100 transition-all duration-300 text-base shadow-sm"
                placeholder="Cari barang berdasarkan kode, nama, atau status...">
        </div>
        <div class="flex items-center gap-4 mt-3 text-sm text-gray-500">
            <span class="flex items-center gap-1.5">
                <i class="fas fa-filter text-amber-500"></i>
                <span>Filter cepat:</span>
            </span>
            <button type="button" onclick="filterByStatus('tersedia')" class="filter-btn px-3 py-1 bg-green-100 text-green-600 rounded-full hover:bg-green-200 transition-colors font-medium">
                <i class="fas fa-check-circle mr-1"></i> Tersedia
            </button>
            <button type="button" onclick="filterByStatus('menipis')" class="filter-btn px-3 py-1 bg-orange-100 text-orange-600 rounded-full hover:bg-orange-200 transition-colors font-medium">
                <i class="fas fa-exclamation-triangle mr-1"></i> Menipis
            </button>
            <button type="button" onclick="filterByStatus('habis')" class="filter-btn px-3 py-1 bg-red-100 text-red-600 rounded-full hover:bg-red-200 transition-colors font-medium">
                <i class="fas fa-times-circle mr-1"></i> Habis
            </button>
            <button type="button" onclick="clearFilter()" class="filter-btn px-3 py-1 bg-gray-100 text-gray-600 rounded-full hover:bg-gray-200 transition-colors font-medium">
                <i class="fas fa-redo mr-1"></i> Reset
            </button>
        </div>
    </div>

    {{-- Table Card --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="overflow-x-auto">
            <table id="barangTable" class="w-full text-sm display">
                <thead>
                    <tr>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Stok</th>
                        <th>Harga Supplier</th>
                        <th>Harga Jual</th>
                        <th>Status</th>
                        @if (auth()->user()->canManageToko())
                            <th>Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($barangs as $barang)
                        <tr>
                            <td class="font-medium text-gray-900">{{ $barang->kode_barang }}</td>
                            <td>{{ $barang->nama_barang }}</td>
                            <td>{{ $barang->stok }}</td>
                            <td>Rp {{ number_format($barang->harga, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($barang->harga_jual, 0, ',', '.') }}</td>
                            <td>
                                <span
                                    class="px-3 py-1 
                                    @if ($barang->status == 'tersedia') bg-green-100 text-green-600
                                    @elseif($barang->status == 'menipis') bg-orange-100 text-orange-600
                                    @else bg-red-100 text-red-600 @endif
                                    text-xs font-medium rounded-full">{{ ucfirst($barang->status) }}</span>
                            </td>
                            @if (auth()->user()->canManageToko())
                                <td>
                                    <div class="flex gap-3">
                                        <a href="{{ route('barang.edit', $barang->id) }}"
                                            class="text-blue-600 hover:text-blue-800">
                                            <i class="fa-solid fa-pencil"></i>
                                        </a>

                                        <form id="delete-form-{{ $barang->id }}"
                                            action="{{ route('barang.destroy', $barang->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="text-red-600 hover:text-red-800"
                                                onclick="deleteBarang({{ $barang->id }}, '{{ $barang->nama_barang }}')">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            @endif
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
            table = new DataTable('#barangTable', {
                responsive: true,
                pageLength: 10,
                dom: 'lrtip', // Remove default search box
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

            // Add focus animation
            customSearch.addEventListener('focus', function() {
                this.parentElement.classList.add('ring-2', 'ring-amber-300', 'rounded-xl');
            });
            customSearch.addEventListener('blur', function() {
                this.parentElement.classList.remove('ring-2', 'ring-amber-300', 'rounded-xl');
            });
        });

        // Filter by status
        function filterByStatus(status) {
            document.getElementById('customSearchInput').value = status;
            table.search(status).draw();
            
            // Update active filter button
            document.querySelectorAll('.filter-btn').forEach(btn => {
                btn.classList.remove('ring-2', 'ring-offset-1');
            });
            event.target.closest('.filter-btn').classList.add('ring-2', 'ring-offset-1');
        }

        // Clear filter
        function clearFilter() {
            document.getElementById('customSearchInput').value = '';
            table.search('').draw();
            
            // Remove active state from all filter buttons
            document.querySelectorAll('.filter-btn').forEach(btn => {
                btn.classList.remove('ring-2', 'ring-offset-1');
            });
        }

        // SweetAlert untuk delete barang
        function deleteBarang(id, nama) {
            Swal.fire({
                title: "Yakin hapus barang?",
                text: `"${nama}" akan dihapus permanen!`,
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#bf0603",
                cancelButtonColor: "#38b000",
                confirmButtonText: "Yes, hapus!"
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`delete-form-${id}`).submit();
                }
            });
        }
    </script>
@endpush
