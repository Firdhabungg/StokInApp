@extends('layouts.dashboard')

@section('title', 'Barang Masuk')
@section('page-title', 'Barang Masuk')
@section('page-description', 'Data dan riwayat barang masuk')

@section('content')
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-semibold text-gray-900">Riwayat Barang Masuk</h2>
            <a href="{{ route('stock.in.create') }}"
                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                <i class="fas fa-plus mr-2"></i>Tambah Barang Masuk
            </a>
        </div>

        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-x-auto">
            <table id="stockInTable" class="w-full text-sm display">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Barang</th>
                        <th>Jumlah</th>
                        <th>Kode Batch</th>
                        <th>Kadaluarsa</th>
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
        document.addEventListener('DOMContentLoaded', function() {
            let table = new DataTable('#stockInTable', {
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
