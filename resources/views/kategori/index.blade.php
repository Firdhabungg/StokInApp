@extends('layouts.dashboard')

@section('title', 'Kategori Barang')
@section('page-title', 'Kelola Kategori')
@section('page-description', 'Kelola kategori barang untuk toko Anda')

@section('content')
    {{-- Header Section --}}
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-xl sm:text-2xl font-bold text-gray-900">Kategori Barang</h2>
                <p class="text-gray-500 mt-1 text-sm sm:text-base">Kelola kategori untuk mengelompokkan barang</p>
            </div>
            <a href="{{ route('kategori.create') }}"
                class="bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white px-4 sm:px-5 py-2.5 rounded-xl font-medium transition-all duration-300 shadow-lg shadow-amber-500/25 hover:shadow-amber-500/40 flex items-center justify-center gap-2 text-sm sm:text-base w-full sm:w-auto">
                <i class="fas fa-plus"></i>
                <span>Tambah Kategori</span>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse ($kategoris as $kategori)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-shadow group">
                <div class="flex items-start justify-between">
                    <a href="{{ route('kategori.show', $kategori->kategori_id) }}" class="flex-1 cursor-pointer">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center group-hover:bg-amber-200 transition-colors">
                                <i class="fas fa-folder text-amber-600"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900 group-hover:text-amber-600 transition-colors">{{ $kategori->nama_kategori }}</h3>
                                <p class="text-sm text-gray-500">{{ $kategori->barangs_count }} barang</p>
                            </div>
                        </div>
                        @if ($kategori->deskripsi_kategori)
                            <p class="mt-3 text-sm text-gray-600">{{ $kategori->deskripsi_kategori }}</p>
                        @endif
                    </a>
                    <div class="flex items-center gap-1 ml-2">
                        <a href="{{ route('kategori.show', $kategori->kategori_id) }}"
                            class="text-gray-400 hover:text-amber-600 p-2" title="Lihat Barang">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('kategori.edit', $kategori->kategori_id) }}"
                            class="text-gray-400 hover:text-blue-600 p-2" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form id="delete-form-{{ $kategori->kategori_id }}"
                            action="{{ route('kategori.destroy', $kategori->kategori_id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="text-gray-400 hover:text-red-600 p-2" title="Hapus"
                                onclick="deleteKategori({{ $kategori->kategori_id }}, '{{ $kategori->nama_kategori }}', {{ $kategori->barangs_count }})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="bg-gray-50 rounded-xl p-8 text-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-folder-open text-gray-400 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-700 mb-2">Belum ada kategori</h3>
                    <p class="text-gray-500 mb-4">Buat kategori pertama Anda untuk mengelompokkan barang</p>
                    <a href="{{ route('kategori.create') }}"
                        class="inline-flex items-center gap-2 bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 rounded-lg transition-colors">
                        <i class="fas fa-plus"></i>
                        <span>Tambah Kategori</span>
                    </a>
                </div>
            </div>
        @endforelse
    </div>
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
