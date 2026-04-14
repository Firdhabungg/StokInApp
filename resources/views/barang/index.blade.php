@extends('layouts.dashboard')

@section('title', 'Data Barang')
@section('page-title', 'Kelola Barang')
@section('page-description', 'Monitoring data barang, stok, dan ketersediaan (readonly)')

@section('content')
    <livewire:barang-table/>
@endsection

@push('scripts')
    <script>
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
