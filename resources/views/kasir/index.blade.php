@extends('layouts.dashboard')

@section('title', 'Manajemen Kasir')
@section('page-title', 'Manajemen Kasir')
@section('page-description', 'Manajemen akun kasir untuk operasional penjualan')

@section('content')
    @livewire('kasir')
@endsection

@push('scripts')
    <script>
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
