<div>
    @if (session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-4">
            {{ session('success') }}
        </div>
    @endif

    <form wire:submit="save" class="space-y-5">
        {{-- Kode Barang --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Kode Barang</label>
            <input type="text" wire:model="kode_barang" {{ $isEdit ? 'readonly' : '' }}
                class="w-full px-4 py-2 border border-gray-200 rounded-xl text-gray-700 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm {{ $isEdit ? 'cursor-not-allowed opacity-60' : '' }}">
        </div>

        {{-- Nama Barang --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Barang <span
                    class="text-red-500">*</span></label>
            <input type="text" wire:model="nama_barang" placeholder="Contoh: Indomie Goreng"
                class="w-full px-4 py-2 border border-gray-200 rounded-xl text-gray-700 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm @error('nama_barang') border-red-400 @enderror">
            @error('nama_barang')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Kategori --}}
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

        {{-- Harga Beli & Harga Jual --}}
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

        {{-- Actions --}}
        <div class="flex items-center gap-3 pt-2">
            <button type="submit"
                class="bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white px-6 py-2.5 rounded-xl font-medium transition-all duration-300 shadow-lg shadow-amber-500/25 text-sm flex items-center gap-2">
                <i class="fas fa-save"></i>
                {{ $isEdit ? 'Perbarui Barang' : 'Tambah Barang' }}
            </button>
            <a href="{{ route('barang.index') }}"
                class="px-6 py-2.5 rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-50 font-medium transition-all duration-300 text-sm">
                Batal
            </a>
        </div>
    </form>
</div>
