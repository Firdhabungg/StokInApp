@extends('layouts.dashboard')

@section('title', 'Kategori Barang')
@section('page-title', 'Kelola Kategori')
@section('page-description', 'Kelola kategori barang untuk toko Anda')

@section('content')

    @livewire('kategori')
@endsection

@push('scripts')
    <script>
        function deleteKategori(id, nama, jumlahBarang) {
            if (jumlahBarang > 0) {
                Swal.fire({
                    title: "Tidak bisa dihapus!",
                    text: `Kategori "${nama}" masih memiliki ${jumlahBarang} barang. Pindahkan atau hapus barang terlebih dahulu.`,
                    icon: "warning",
                    confirmButtonColor: "#f59e0b"
                });
                return;
            }

            Swal.fire({
                title: "Yakin hapus kategori?",
                text: `"${nama}" akan dihapus permanen!`,
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#bf0603",
                cancelButtonColor: "#6b7280",
                confirmButtonText: "Ya, hapus!",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`delete-form-${id}`).submit();
                }
            });
        }
    </script>
@endpush
