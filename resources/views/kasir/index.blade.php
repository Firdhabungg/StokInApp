@extends('layouts.dashboard')

@section('title', 'Manajemen Kasir')
@section('page-title', 'Manajemen Kasir')
@section('page-description', 'Manajemen akun kasir untuk operasional penjualan')

@section('content')
{{-- Header Section --}}
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Daftar Kasir</h2>
            <p class="text-gray-500 mt-1">Manajemen akun kasir untuk operasional penjualan</p>
            @if($maxUsers != -1)
                <p class="text-sm text-amber-600 mt-1">
                    <i class="fas fa-users mr-1"></i>
                    Kuota pengguna: {{ $kasirs->count() + 1 }}/{{ $maxUsers }} 
                    ({{ $remainingSlots }} slot tersisa)
                </p>
            @endif
        </div>
        @if($canAddUser)
            <a href="{{ route('kasir.create') }}" 
                class="bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white px-5 py-2.5 rounded-xl font-medium transition-all duration-300 shadow-lg shadow-amber-500/25 hover:shadow-amber-500/40 flex items-center gap-2">
                <i class="fas fa-plus"></i>
                <span>Tambah Kasir</span>
            </a>
        @else
            <div class="text-right">
                <button disabled class="bg-gray-300 text-gray-500 px-4 py-2 rounded-lg font-medium cursor-not-allowed">
                    <i class="fas fa-lock mr-2"></i>Batas Tercapai
                </button>
                <a href="{{ route('subscription.index') }}" class="block text-sm text-amber-600 hover:underline mt-1">
                    Upgrade paket →
                </a>
            </div>
        @endif
    </div>
</div>

{{-- Search Card - Full Width --}}
<div class="bg-white rounded-2xl shadow-sm p-5 mb-6">
    <div class="relative">
        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
            <i class="fas fa-search text-amber-500 text-lg"></i>
        </div>
        <input type="text" id="customSearchInput" 
            class="w-full pl-12 pr-4 py-2 bg-white border-2 border-amber-200/50 rounded-xl text-gray-700 placeholder-gray-400 focus:outline-none focus:border-amber-400 focus:ring-4 focus:ring-amber-100 transition-all duration-300 text-base shadow-sm"
            placeholder="Cari kasir berdasarkan nama atau email...">
    </div>
</div>

{{-- Table Card --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-4">
            <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
        </div>
    @endif

    <div class="overflow-x-auto">
        <table id="kasirTable" class="w-full text-sm display">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($kasirs as $kasir)
                <tr>
                    <td class="font-medium text-gray-900">{{ $kasir->name }}</td>
                    <td class="text-gray-600">{{ $kasir->email }}</td>
                    <td>
                        <span class="px-2 py-1 bg-purple-100 text-purple-700 rounded-full text-xs font-medium">
                            Kasir
                        </span>
                    </td>
                    <td>
                        <form id="delete-form-{{ $kasir->id }}" action="{{ route('kasir.destroy', $kasir) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="text-red-600 hover:text-red-800" onclick="deleteKasir({{ $kasir->id }}, '{{ $kasir->name }}')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
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
        table = new DataTable('#kasirTable', {
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

    function deleteKasir(id, nama) {
        Swal.fire({
            title: "Yakin hapus kasir?",
            text: `"${nama}" akan dihapus dari sistem!`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#bf0603",
            cancelButtonColor: "#6b7280",
            confirmButtonText: "Ya, hapus!"
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`delete-form-${id}`).submit();
            }
        });
    }
</script>
@endpush
