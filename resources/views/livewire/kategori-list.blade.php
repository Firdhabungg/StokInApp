<div wire:poll.keep-alive>
    <form wire:submit="search" class="bg-white rounded-2xl shadow-sm p-3 mb-3">
        <label for="query" class="block mb-2.5 text-sm font-medium text-heading sr-only ">Search</label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <i class="fas fa-search text-amber-500 text-lg"></i>
            </div>
            <input wire:model.live="query" type="search" id="query"
                class="w-full pl-12 pr-12 py-2 bg-white border-2 border-amber-200/50 rounded-xl text-gray-700 placeholder-gray-400 focus:outline-none focus:border-amber-400 focus:ring-4 focus:ring-amber-100 transition-all duration-300 text-base shadow-sm"
                placeholder="Cari kategori..." />
            <button wire:click="search"
                class="absolute end-1.5 bottom-1.5 text-white bg-amber-500 hover:bg-amber-600 box-border border border-transparent focus:ring-4 focus:ring-amber-100 shadow-xs font-medium leading-5 rounded text-xs px-3 py-1 focus:outline-none flex items-center gap-1">
                <svg wire:loading wire:target="search" aria-hidden="true"
                    class="w-4 h-4 text-neutral-quaternary animate-spin fill-brand" viewBox="0 0 100 101" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                        fill="currentColor" />
                    <path
                        d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                        fill="currentFill" />
                </svg>
                <span class="sr-only">Loading...</span>
                Search</button>
        </div>
    </form>

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
                            wire:click="triggerDelete({{ $kategori->kategori_id }}, '{{ $kategori->nama_kategori }}', {{ $kategori->barangs_count }})"
                            class="text-gray-400 hover:text-red-500 transition-colors">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>
                </div>

                @if ($kategori->deskripsi_kategori)
                    <p class="text-xs text-gray-500 mt-2">{{ $kategori->deskripsi_kategori }}</p>
                @endif
            </div>
        @empty
            <div class="col-span-3 text-center text-gray-400 py-10">
                <i class="fas fa-tags text-4xl mb-3"></i>
                <p>Kategori tidak ditemukan</p>
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $this->kategoris->links() }}
    </div>
</div>

@script
    <script>
        $wire.on('show-delete-confirm', ({
            id,
            nama,
            jumlahBarang
        }) => {
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
                    $wire.call('destroy', id);
                }
            });
        });
    </script>
@endscript
