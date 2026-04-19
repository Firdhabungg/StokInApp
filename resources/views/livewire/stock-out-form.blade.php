<div>
    @if (session()->has('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="mb-6">
            <h2 class="text-xl font-semibold text-gray-900">Form Barang Keluar</h2>
        </div>

        <form action="{{ route('stock.out.store') }}" method="POST" id="stockOutForm">
            @csrf

            {{-- Mode Selector --}}
            <div class="mb-6 flex gap-3">
                <button type="button" id="btnFifo" onclick="setMode('fifo')"
                    class="flex items-center gap-2 px-4 py-2 rounded-lg border-2 border-red-500 bg-red-50 text-red-700 font-medium text-sm transition-all">
                    <i class="fas fa-sort-amount-down"></i> Otomatis (FIFO)
                </button>
                <button type="button" id="btnManual" onclick="setMode('manual')"
                    class="flex items-center gap-2 px-4 py-2 rounded-lg border-2 border-gray-200 text-gray-500 font-medium text-sm transition-all hover:border-orange-400 hover:text-orange-600">
                    <i class="fas fa-hand-pointer"></i> Pilih Batch Manual
                </button>
            </div>

            {{-- Hidden field: mode (fifo / manual) --}}
            <input type="hidden" name="mode" id="modeInput" value="{{ old('mode', 'fifo') }}">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="barang_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Pilih Barang <span class="text-red-500">*</span>
                    </label>
                    <select wire:model="barang_id" name="barang_id" id="barang_id"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-red-500 focus:border-red-500"
                        onchange="onBarangChange(this.value)">
                        <option value="">-- Pilih Barang --</option>
                        @foreach ($barangs as $barang)
                            <option value="{{ $barang->id }}" {{ old('barang_id') == $barang->id ? 'selected' : '' }}>
                                {{ $barang->kode_barang }} - {{ $barang->nama_barang }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Stok Tersedia</label>
                    <div id="availableStock"
                        class="bg-gray-50 border border-gray-200 rounded-lg px-4 py-2.5 text-gray-600">
                        Pilih barang terlebih dahulu
                    </div>
                </div>

                {{-- Jumlah: hanya tampil & required saat mode FIFO --}}
                <div id="jumlahWrapper">
                    <label for="jumlah" class="block text-sm font-medium text-gray-700 mb-2">
                        Jumlah Keluar <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="jumlah" id="jumlah" min="1" value="{{ old('jumlah', 1) }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-red-500 focus:border-red-500">
                </div>

                <div>
                    <label for="tgl_keluar" class="block text-sm font-medium text-gray-700 mb-2">
                        Tanggal Keluar <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="tgl_keluar" id="tgl_keluar"
                        value="{{ old('tgl_keluar', date('Y-m-d')) }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-red-500 focus:border-red-500">
                </div>

                <div>
                    <label for="alasan" class="block text-sm font-medium text-gray-700 mb-2">
                        Alasan <span class="text-red-500">*</span>
                    </label>
                    <select name="alasan" id="alasan"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-red-500 focus:border-red-500">
                        <option value="">-- Pilih Alasan --</option>
                        <option value="rusak" {{ old('alasan') == 'rusak' ? 'selected' : '' }}>Rusak</option>
                        <option value="kadaluarsa" {{ old('alasan') == 'kadaluarsa' ? 'selected' : '' }}>Kadaluarsa
                        </option>
                        <option value="retur" {{ old('alasan') == 'retur' ? 'selected' : '' }}>Retur ke Supplier
                        </option>
                        <option value="hilang" {{ old('alasan') == 'hilang' ? 'selected' : '' }}>Hilang/Selisih Stock
                        </option>
                        <option value="sample" {{ old('alasan') == 'sample' ? 'selected' : '' }}>Sample/Promosi
                        </option>
                        <option value="lainnya" {{ old('alasan') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">Keterangan</label>
                    <textarea name="keterangan" id="keterangan" rows="3" placeholder="Catatan tambahan (opsional)"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-red-500 focus:border-red-500">{{ old('keterangan') }}</textarea>
                </div>
            </div>

            {{-- Panel FIFO: preview batch yang akan diambil --}}
            <div id="fifoPanel" class="mt-6 hidden">
                <h3 class="text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-info-circle text-blue-500 mr-1"></i>
                    Batch yang akan diambil (FIFO):
                </h3>
                <div id="batchList" class="bg-gray-50 border border-gray-200 rounded-lg p-4 space-y-2"></div>
            </div>

            {{-- Panel Manual: pilih batch + qty per batch --}}
            <div id="manualPanel" class="mt-6 hidden">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-sm font-medium text-gray-700">
                        <i class="fas fa-hand-pointer text-orange-500 mr-1"></i>
                        Pilih Batch yang Akan Dikeluarkan:
                    </h3>
                    <span class="text-xs font-semibold px-3 py-1 rounded-full bg-orange-100 text-orange-700">
                        Total dipilih: <span id="manualTotalQty">0</span> unit
                    </span>
                </div>
                <div id="batchCheckboxList" class="space-y-3"></div>
                <p id="noBatchMsg" class="text-sm text-gray-400 hidden">Pilih barang terlebih dahulu.</p>
            </div>

            <div class="mt-6 flex items-center justify-end gap-4">
                <a href="{{ route('stock.out.index') }}"
                    class="px-6 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                    Batal
                </a>
                <button type="submit"
                    class="flex items-center gap-2 px-6 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition-colors">
                    <svg wire:loading wire:target="submit" aria-hidden="true"
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
