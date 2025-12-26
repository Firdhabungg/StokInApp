@extends('layouts.dashboard')

@section('title', 'Barang Keluar')
@section('page-title', 'Barang Keluar')
@section('page-description', 'Data dan riwayat barang keluar')

@section('content')
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-semibold text-gray-900">Riwayat Barang Keluar</h2>
            <a href="{{ route('stock.out.create') }}"
                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                <i class="fas fa-minus mr-2"></i>Tambah Barang Keluar
            </a>
        </div>

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
        document.addEventListener('DOMContentLoaded', function() {
            let table = new DataTable('#stockOutTable', {
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
    </script>
@endpush
