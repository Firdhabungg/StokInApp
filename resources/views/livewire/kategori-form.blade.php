<div>
    {{-- Flash Message --}}
    @if (session()->has('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <form wire:submit="save">
        <div class="mb-3">
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Nama Kategori <span class="text-red-500">*</span>
            </label>
            <input type="text" wire:model="nama_kategori"
                class="w-full border rounded-lg px-3 py-2 @error('nama_kategori') border-red-500 @enderror"
                placeholder="Contoh: Minuman" />
            @error('nama_kategori')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Deskripsi
            </label>
            <textarea wire:model="deskripsi_kategori"
                class="w-full border rounded-lg px-3 py-2 @error('deskripsi_kategori') border-red-500 @enderror" rows="3"
                placeholder="Opsional"></textarea>
            @error('deskripsi_kategori')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center gap-2">
            <button type="submit"
                class="bg-amber-500 hover:bg-amber-600 text-white font-semibold px-4 py-2 rounded-lg transition-colors">
                {{ $editId ? 'Perbarui Kategori' : 'Simpan Kategori' }}
            </button>

            @if ($editId)
                <button type="button" wire:click="resetForm"
                    class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold px-4 py-2 rounded-lg transition-colors">
                    Batal
                </button>
            @endif
        </div>
    </form>
</div>
