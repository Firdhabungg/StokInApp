@extends('layouts.dashboard')

@section('title', 'Data Barang')
@section('page-title', 'Data Barang')

@section('content')
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-semibold text-gray-900">Daftar Barang</h2>
            <a href="{{ route('barang.create') }}"
                class="bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                <i class="fas fa-plus mr-2"></i>Tambah Barang
            </a>
        </div>

        <div class="overflow-x-auto">
            <table id="barangTable" class="w-full text-sm text-center">
                <thead class="text-gray-700 bg-gray-50">
                    <tr>
                        <th class="px-6 py-3">Kode Barang</th>
                        <th class="px-6 py-3">Nama Barang</th>
                        <th class="px-6 py-3">Stok</th>
                        <th class="px-6 py-3">Harga</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($barangs as $barang)
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="px-6 py-6 font-medium text-gray-900">{{ $barang->kode_barang }}</td>
                            <td class="px-6 py-6">{{ $barang->nama_barang }}</td>
                            <td class="px-6 py-6">{{ $barang->stok }}</td>
                            <td class="px-6 py-6">Rp {{ number_format($barang->harga, 0, ',', '.') }}</td>
                            <td class="px-6 py-6">
                                <span
                                    class="px-3 py-1 
                                    @if ($barang->status == 'tersedia') bg-green-100 text-green-600
                                    @elseif($barang->status == 'menipis') bg-orange-100 text-orange-600
                                    @else bg-red-100 text-red-600 @endif
                                    text-xs font-medium rounded-full">{{ ucfirst($barang->status) }}</span>
                            </td>
                            <td class="px-6 py-6">
                                <div class="flex gap-2">
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
            let table = new DataTable('#barangTable', {
                responsive: true,
                pageLength: 10,
                language: {
                    search: '<i class="fa-solid fa-magnifying-glass"></i> ',
                    lengthMenu: 'Tampilkan _MENU_ data per halaman',
                    info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',
                    paginate: {
                        first: 'Pertama',
                        last: 'Terakhir',
                        next: 'Selanjutnya',
                        previous: 'Sebelumnya'
                    }
                }
            });
        });

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
