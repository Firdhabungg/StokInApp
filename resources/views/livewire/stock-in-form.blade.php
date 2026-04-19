<div>
    @if (session()->has('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="mb-6">
            <h2 class="text-xl font-semibold text-gray-900">Form Barang Masuk</h2>
            <p class="text-gray-500 text-sm mt-1">Catat barang masuk dengan batch baru</p>
        </div>

        <form wire:submit="save">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="barang_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Pilih Barang <span class="text-red-500">*</span>
                    </label>
                    <select name="barang_id" id="barang_id" wire:model="barang_id"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="">-- Pilih Barang --</option>
                        @foreach ($barangs as $barang)
                            <option value="{{ $barang->id }}" {{ old('barang_id') == $barang->id ? 'selected' : '' }}>
                                {{ $barang->kode_barang }} - {{ $barang->nama_barang }}
                            </option>
                        @endforeach
                    </select>
                    @error('barang_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="jumlah" class="block text-sm font-medium text-gray-700 mb-2">
                        Jumlah <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="jumlah" id="jumlah" wire:model="jumlah"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    @error('jumlah')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="tgl_masuk" class="block text-sm font-medium text-gray-700 mb-2">
                        Tanggal Masuk <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="tgl_masuk" id="tgl_masuk" wire:model="tgl_masuk"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    @error('tgl_masuk')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="tgl_kadaluarsa" class="block text-sm font-medium text-gray-700 mb-2">
                        Tanggal Kadaluwarsa
                    </label>
                    <input type="date" name="tgl_kadaluarsa" id="tgl_kadaluarsa" wire:model="tgl_kadaluarsa"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    @error('tgl_kadaluarsa')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-500 mt-1">Kosongkan jika barang tidak memiliki kadaluwarsa</p>
                </div>

                <div>
                    <label for="supplier" class="block text-sm font-medium text-gray-700 mb-2">
                        Supplier
                    </label>
                    <input type="text" name="supplier" id="supplier" wire:model="supplier"
                        placeholder="Nama supplier (opsional)"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    @error('supplier')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">
                        Keterangan
                    </label>
                    <textarea name="keterangan" id="keterangan" rows="3" wire:model="keterangan"
                        placeholder="Catatan tambahan (opsional)"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-green-500 focus:border-green-500"></textarea>
                    @error('keterangan')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-4">
                <a href="{{ route('stock.in.index') }}"
                    class="px-6 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                    Batal
                </a>
                <button type="submit"
                    class="flex items-center gap-2 px-6 py-2.5 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition-colors">
                    <svg wire:loading wire:target="save" aria-hidden="true"
                        class="w-4 h-4 text-neutral-quaternary animate-spin fill-brand" viewBox="0 0 100 101"
                        fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                            fill="currentColor" />
                        <path
                            d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                            fill="currentFill" />
                    </svg>
                    <span class="sr-only">Loading...</span>
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>
