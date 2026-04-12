@extends('layouts.dashboard')

@section('title', 'Penjualan')
@section('page-title', 'Penjualan')
@section('page-description', 'Pencatatan dan pengelolaan transaksi penjualan')

@section('content')
    @livewire('penjualan')
@endsection

@push('scripts')
    <script>
        let table;

        document.addEventListener('DOMContentLoaded', function() {
            table = new DataTable('#penjualanTable', {
                responsive: true,
                paging: false,
                dom: 'lrt',
                language: {
                    zeroRecords: 'Tidak ada data yang ditemukan',
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
