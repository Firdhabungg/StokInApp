<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    @if (session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-4">
            {{ session('success') }}
        </div>
    @endif

    <form wire:submit="save" class="space-y-5">
        <div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kode Barang</label>
                <input type="text" wire:model="kode_barang" readonly
                    class="w-full px-4 py-2 border border-gray-200 rounded-xl text-gray-700 bg-gray-50 cursor-not-allowed opacity-60 focus:outline-none text-sm">
                <p class="mt-1 text-xs text-gray-500">
                    {{ $isEdit ? 'Tidak dapat diubah' : 'Kode otomatis' }}
                </p>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Barang <span
                    class="text-red-500">*</span></label>
            <input type="text" wire:model="nama_barang" placeholder="Indomie Goreng Seedap"
                class="w-full px-4 py-2 border border-gray-200 rounded-xl text-gray-700 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm @error('nama_barang') border-red-400 @enderror">
            @error('nama_barang')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Kategori <span
                    class="text-red-500">*</span></label>
            <select wire:model="kategori_id"
                class="w-full px-4 py-2 border border-gray-200 rounded-xl text-gray-700 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm @error('kategori_id') border-red-400 @enderror">
                <option value="">Pilih Kategori</option>
                @foreach ($kategoris as $kategori)
                    <option value="{{ $kategori->kategori_id }}">{{ $kategori->nama_kategori }}</option>
                @endforeach
            </select>
            @error('kategori_id')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Harga Beli <span
                        class="text-red-500">*</span></label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400 text-sm">Rp</span>
                    <input type="number" wire:model="harga" placeholder="0"
                        class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl text-gray-700 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm @error('harga') border-red-400 @enderror">
                </div>
                @error('harga')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Harga Jual <span
                        class="text-red-500">*</span></label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400 text-sm">Rp</span>
                    <input type="number" wire:model="harga_jual" placeholder="0"
                        class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl text-gray-700 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm @error('harga_jual') border-red-400 @enderror">
                </div>
                @error('harga_jual')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="flex justify-end gap-3 pt-2">
            <button type="submit"
                class="bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white px-6 py-2.5 rounded-xl font-medium transition-all duration-300 shadow-lg shadow-amber-500/25 text-sm flex items-center gap-2">
                <svg wire:loading wire:target="save" aria-hidden="true"
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
                {{ $isEdit ? 'Perbarui Barang' : 'Tambah Barang' }}
            </button>
            <a href="{{ route('barang.index') }}"
                class="px-6 py-2.5 rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-50 font-medium transition-all duration-300 text-sm">
                Batal
            </a>
        </div>
    </form>
</div>
