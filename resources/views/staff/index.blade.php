@extends('layouts.dashboard')

@section('title', 'Manajemen Kasir')
@section('page-title', 'Manajemen Kasir')
@section('page-description', 'Manajemen akun kasir untuk operasional penjualan')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold text-gray-900">Daftar Kasir</h2>
        <a href="{{ route('staff.create') }}" class="bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
            <i class="fas fa-plus mr-2"></i>Tambah Kasir
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto">
        <table id="staffTable" class="w-full text-sm display">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($staff as $member)
                <tr>
                    <td class="font-medium text-gray-900">{{ $member->name }}</td>
                    <td class="text-gray-600">{{ $member->email }}</td>
                    <td>
                        <span class="px-2 py-1 bg-purple-100 text-purple-700 rounded-full text-xs font-medium">
                            Kasir
                        </span>
                    </td>
                    <td>
                        <form id="delete-form-{{ $member->id }}" action="{{ route('staff.destroy', $member) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="text-red-600 hover:text-red-800" onclick="deleteStaff({{ $member->id }}, '{{ $member->name }}')">
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
    document.addEventListener('DOMContentLoaded', function() {
            let table = new DataTable('#staffTable', {
                responsive: true,
                pageLength: 10,
                language: {
                    search: '<i class="fa-solid fa-magnifying-glass"></i> ',
                    lengthMenu: 'Tampilkan _MENU_ data per halaman',
                    info: 'Menampilkan <b>_START_</b> sampai <b>_END_</b> dari <b>_TOTAL_</b> data',
                    paginate: {
                        first: '<<',
                        last: '>>',
                        next: '>',
                        previous: '<'
                    }
                }
            });
        });

    function deleteStaff(id, nama) {
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
