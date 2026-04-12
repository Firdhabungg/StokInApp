@extends('layouts.dashboard')

@section('title', 'Kategori Barang')
@section('page-title', 'Kelola Kategori')
@section('page-description', 'Kelola kategori barang untuk toko Anda')

@section('content')
    <div class="mb-3">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Kategori Barang</h2>
            <a href="{{ route('kategori.create') }}"
                class="bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white px-4 sm:px-5 py-2.5 rounded-xl font-medium transition-all duration-300 shadow-lg shadow-amber-500/25 hover:shadow-amber-500/40 flex items-center justify-center gap-2 text-sm sm:text-base w-full sm:w-auto">
                <i class="fas fa-plus"></i>
                <span>Tambah Kategori</span>
            </a>
        </div>
    </div>

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
