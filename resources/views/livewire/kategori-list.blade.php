<div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse ($this->kategoris as $kategori)
            <div class="bg-white rounded-xl p-4 shadow-sm group">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-tags text-amber-600"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold">{{ $kategori->nama_kategori }}</h3>
                            <p class="text-sm text-gray-500">{{ $kategori->barangs_count }} barang</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <a href="{{ route('kategori.show', $kategori->kategori_id) }}"
                            class="text-gray-400 hover:text-blue-500 transition-colors">
                            <i class="fa-solid fa-eye"></i>
                        </a>

                        <button wire:click="editKategori({{ $kategori->kategori_id }})"
                            class="text-gray-400 hover:text-amber-500 transition-colors">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>

                        <button
                            onclick="confirmDelete({{ $kategori->kategori_id }}, '{{ $kategori->nama_kategori }}', {{ $kategori->barangs_count }})"
                            class="text-gray-400 hover:text-red-500 transition-colors">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>
                </div>

                @if ($kategori->deskripsi_kategori)
                    <p class="text-sm text-gray-500 mt-2">{{ $kategori->deskripsi_kategori }}</p>
                @endif
            </div>
        @empty
            <div class="col-span-3 text-center text-gray-400 py-10">
                <i class="fas fa-tags text-4xl mb-3"></i>
                <p>Belum ada kategori</p>
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $this->kategoris->links() }}
    </div>
</div>

@push('scripts')
    <script>
        function confirmDelete(id, nama, jumlahBarang) {
            if (jumlahBarang > 0) {
                Swal.fire({
                    title: 'Tidak bisa dihapus!',
                    text: `Kategori "${nama}" masih memiliki ${jumlahBarang} barang. Pindahkan atau hapus barang terlebih dahulu.`,
                    icon: 'warning',
                    confirmButtonColor: '#f59e0b'
                });
                return;
            }

            Swal.fire({
                title: 'Yakin hapus kategori?',
                text: `"${nama}" akan dihapus permanen!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#bf0603',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.call('destroy', id)
                }
            });
        }
    </script>
@endpush
