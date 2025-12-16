@extends('layouts.dashboard')

@section('title', 'Data Barang')
@section('page-title', 'Data Barang')

@section('content')
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-semibold text-gray-900">Daftar Barang</h2>
            <button class="bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                <i class="fas fa-plus mr-2"></i>Tambah Barang
            </button>
        </div>

        <div class="overflow-x-auto">
            <table id="barangTable" class="w-full text-sm text-left">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th class="px-6 py-3">Kode Barang</th>
                        <th class="px-6 py-3">Nama Barang</th>
                        <th class="px-6 py-3">Kategori</th>
                        <th class="px-6 py-3">Stok</th>
                        <th class="px-6 py-3">Harga</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <td class="px-6 py-6 font-medium text-gray-900">BRG001</td>
                        <td class="px-6 py-6">Aqua 600ml</td>
                        <td class="px-6 py-6">Minuman</td>
                        <td class="px-6 py-6">45</td>
                        <td class="px-6 py-6">Rp 3.000</td>
                        <td class="px-6 py-6">
                            <span
                                class="px-3 py-1 bg-orange-100 text-orange-600 text-xs font-medium rounded-full">Menipis</span>
                        </td>
                        <td class="px-6 py-6">
                            <div class="flex gap-2">
                                <button class="text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="text-red-600 hover:text-red-800">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <td class="px-6 py-6 font-medium text-gray-900">BRG002</td>
                        <td class="px-6 py-6">Teh Pucuk 350ml</td>
                        <td class="px-6 py-6">Minuman</td>
                        <td class="px-6 py-6">23</td>
                        <td class="px-6 py-6">Rp 4.500</td>
                        <td class="px-6 py-6">
                            <span class="px-3 py-1 bg-red-100 text-red-600 text-xs font-medium rounded-full">Kritis</span>
                        </td>
                        <td class="px-6 py-6">
                            <div class="flex gap-2">
                                <button class="text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="text-red-600 hover:text-red-800">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
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
                    search: 'Cari:',
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
    </script>
@endpush
