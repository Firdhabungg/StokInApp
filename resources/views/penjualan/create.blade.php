<div>

    @if ($errors->has('error'))
        <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-lg border border-red-200 flex items-start gap-2">
            <i class="fas fa-exclamation-circle mt-1"></i>
            <div>{{ $errors->first('error') }}</div>
        </div>
    @endif

    @if ($errors->has('items'))
        <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-lg border border-red-200 flex items-start gap-2">
            <i class="fas fa-exclamation-circle mt-1"></i>
            <div>{{ $errors->first('items') }}</div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-4">
            <div class="mb-3">
                <a href="{{ route('penjualan.index') }}" wire:navigate
                    class="bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white px-3 py-2.5 rounded-xl font-medium transition-all duration-300 shadow-lg shadow-green-500/25 flex items-center justify-center gap-2 text-sm w-full sm:w-auto">
                    <i class="fas fa-clock"></i>
                    <span>History Penjualan</span>
                </a>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-boxes text-green-600 mr-2"></i>Pilih Produk
                    </h2>
                </div>

                <div class="relative mb-3">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="fas fa-search text-green-500"></i>
                    </div>
                    <input wire:model.live.debounce.300ms="search" type="search"
                        class="w-full pl-10 pr-4 py-2.5 bg-white border-2 border-green-200/50 rounded-xl text-gray-700 placeholder-gray-400 focus:outline-none focus:border-green-400 focus:ring-4 focus:ring-green-100 transition-all duration-300 text-sm shadow-sm"
                        placeholder="Cari nama atau kode barang..." />
                </div>

                <div class="flex overflow-x-auto pb-2 mb-4 gap-2 no-scrollbar"
                    style="-ms-overflow-style: none; scrollbar-width: none;">
                    <button type="button" wire:click="$set('selectedKategori', '')"
                        class="whitespace-nowrap px-4 py-1.5 rounded-full text-sm font-medium transition-colors border {{ $selectedKategori === '' ? 'bg-green-500 text-white border-green-500 shadow-sm' : 'bg-white text-gray-600 border-gray-200 hover:bg-gray-50' }}">
                        Semua Produk
                    </button>
                    @foreach ($kategoris as $kategori)
                        <button type="button" wire:click="$set('selectedKategori', '{{ $kategori->kategori_id }}')"
                            class="whitespace-nowrap px-4 py-1.5 rounded-full text-sm font-medium transition-colors border {{ $selectedKategori == $kategori->kategori_id ? 'bg-green-500 text-white border-green-500 shadow-sm' : 'bg-white text-gray-600 border-gray-200 hover:bg-gray-50' }}">
                            {{ $kategori->nama_kategori }}
                        </button>
                    @endforeach
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-3 max-h-[60vh] overflow-y-auto pr-1"
                    id="product-grid">
                    @forelse($barangs as $barang)
                        @php
                            $qtyInCart = $this->getItemInCart($barang->id);
                            $isInCart = $qtyInCart !== null;
                        @endphp
                        <button type="button" wire:key="barang-{{ $barang->id }}"
                            wire:click.prevent="addItem({{ $barang->id }})" wire:loading.attr="disabled"
                            class="relative text-left p-4 rounded-xl border-2 transition-all duration-200 hover:shadow-md group
                                {{ $isInCart
                                    ? 'border-green-400 bg-green-50 shadow-sm'
                                    : ($barang->stok_tersedia < 1
                                        ? 'border-gray-200 bg-gray-50 opacity-60 cursor-not-allowed'
                                        : 'border-gray-200 bg-white hover:border-green-300 hover:bg-green-50/50') }}"
                            {{ $barang->stok_tersedia < 1 ? 'disabled' : '' }}>

                            {{-- Cart Badge --}}
                            @if ($isInCart)
                                <span
                                    class="absolute -top-2 -right-2 bg-green-600 text-white text-xs font-bold w-6 h-6 rounded-full flex items-center justify-center shadow-lg">
                                    {{ $qtyInCart }}
                                </span>
                            @endif

                            <div class="flex items-start justify-between">
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-gray-900 text-sm truncate">{{ $barang->nama_barang }}
                                    </p>
                                    <p class="text-xs text-gray-400 font-mono mt-0.5">{{ $barang->kode_barang }}</p>
                                </div>
                                <div class="ml-2 flex-shrink-0">
                                    @if ($barang->stok_tersedia < 1)
                                        <span
                                            class="px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-600">Habis</span>
                                    @elseif ($barang->stok_tersedia <= 10)
                                        <span
                                            class="px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">{{ $barang->stok_tersedia }}</span>
                                    @else
                                        <span
                                            class="px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">{{ $barang->stok_tersedia }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="mt-2 flex items-center justify-between">
                                <p class="font-bold text-green-600 text-sm">Rp
                                    {{ number_format($barang->harga_jual, 0, ',', '.') }}</p>
                                @if ($barang->stok_tersedia >= 1)
                                    <span
                                        class="text-green-500 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                        <i class="fas fa-plus-circle"></i>
                                    </span>
                                @endif
                            </div>
                        </button>
                    @empty
                        <div class="col-span-full py-12 text-center text-gray-400">
                            <i class="fas fa-search text-3xl mb-2 block"></i>
                            @if ($search)
                                <p>Tidak ada produk ditemukan untuk "<strong>{{ $search }}</strong>"</p>
                            @else
                                <p>Tidak ada produk dengan stok tersedia</p>
                            @endif
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Right: Cart & Checkout --}}
        <div class="space-y-4 sticky top-6 z-10 self-start">
            {{-- Cart Items --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-shopping-cart text-green-600 mr-2"></i>Keranjang
                    </h2>
                    @if (count($items) > 0)
                        <span class="bg-green-100 text-green-700 text-xs font-bold px-2.5 py-1 rounded-full">
                            {{ $this->totalItems }} item
                        </span>
                    @endif
                </div>

                @if (count($items) === 0)
                    <div class="py-8 text-center text-gray-400">
                        <i class="fas fa-shopping-cart text-3xl mb-2 block opacity-30"></i>
                        <p class="text-sm">Belum ada item</p>
                        <p class="text-xs mt-1">Klik produk di sebelah kiri untuk menambahkan</p>
                    </div>
                @else
                    <div class="space-y-3 max-h-[40vh] overflow-y-auto pr-1">
                        @foreach ($items as $index => $item)
                            <div wire:key="cart-item-{{ $item['barang_id'] }}"
                                class="bg-gray-50 rounded-lg p-3 border border-gray-100">
                                <div class="flex items-start justify-between mb-2">
                                    <div class="flex-1 min-w-0">
                                        <p class="font-semibold text-gray-900 text-sm truncate">{{ $item['nama'] }}</p>
                                        <p class="text-xs text-gray-400 font-mono">{{ $item['kode'] }}</p>
                                    </div>
                                    <button type="button" wire:click="removeItem({{ $index }})"
                                        class="text-gray-400 hover:text-red-500 transition-colors ml-2 flex-shrink-0">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-1">
                                        <button type="button"
                                            wire:click="updateQty({{ $index }}, {{ $item['jumlah'] - 1 }})"
                                            class="w-7 h-7 bg-white border border-gray-300 rounded-md text-gray-600 hover:bg-gray-100 flex items-center justify-center text-sm transition-colors">
                                            <i class="fas fa-minus text-xs"></i>
                                        </button>
                                        <span
                                            class="w-8 text-center font-semibold text-sm">{{ $item['jumlah'] }}</span>
                                        <button type="button"
                                            wire:click="updateQty({{ $index }}, {{ $item['jumlah'] + 1 }})"
                                            class="w-7 h-7 bg-white border border-gray-300 rounded-md text-gray-600 hover:bg-gray-100 flex items-center justify-center text-sm transition-colors">
                                            <i class="fas fa-plus text-xs"></i>
                                        </button>
                                        <span class="text-xs text-gray-400 ml-1">× Rp
                                            {{ number_format($item['harga'], 0, ',', '.') }}</span>
                                    </div>
                                    <p class="font-bold text-sm text-gray-900">
                                        Rp {{ number_format($item['harga'] * $item['jumlah'], 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Checkout Summary --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-receipt text-green-600 mr-2"></i>Ringkasan
                </h2>

                <div class="space-y-3 mb-4">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Kode Transaksi</span>
                        <span class="font-mono font-semibold text-gray-700">{{ $kodeTransaksi }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Tanggal</span>
                        <span class="text-gray-700">{{ now()->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Jumlah Item</span>
                        <span class="text-gray-700">{{ $this->totalItems }}</span>
                    </div>
                    <hr class="border-gray-200">
                    <div class="flex justify-between items-center">
                        <span class="font-semibold text-gray-900">Total</span>
                        <span class="font-bold text-xl text-green-600">Rp
                            {{ number_format($this->grandTotal, 0, ',', '.') }}</span>
                    </div>
                </div>

                {{-- Payment Method --}}
                <div class="mb-4">
                    <label for="metode_pembayaran" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Metode Pembayaran <span class="text-red-500">*</span>
                    </label>
                    <select wire:model.live="metode_pembayaran" id="metode_pembayaran"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('metode_pembayaran') border-red-500 @enderror">
                        <option value="">-- Pilih Metode --</option>
                        <option value="cash">Cash</option>
                        <option value="transfer">Transfer</option>
                        <option value="qris">QRIS</option>
                    </select>
                    @error('metode_pembayaran')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Cash: Uang Dibayar --}}
                @if ($metode_pembayaran === 'cash')
                    <div class="mb-4">
                        <label for="uang_dibayar" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Uang Dibayar <span class="text-red-500">*</span>
                        </label>
                        <input type="number" wire:model.live.debounce.300ms="uang_dibayar" id="uang_dibayar"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('uang_dibayar') border-red-500 @enderror"
                            placeholder="Masukkan uang diterima">
                        @error('uang_dibayar')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror

                        @if ($uang_dibayar && (int) $uang_dibayar >= $this->grandTotal && $this->grandTotal > 0)
                            <div class="mt-2 bg-green-50 border border-green-200 rounded-lg px-3 py-2">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-green-700">Kembalian</span>
                                    <span class="font-bold text-green-700">Rp
                                        {{ number_format($this->kembalian, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif

                {{-- Transfer/QRIS: Bukti Pembayaran --}}
                @if (in_array($metode_pembayaran, ['transfer', 'qris']))
                    <div class="mb-4">
                        <label for="bukti_pembayaran" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Bukti Pembayaran (opsional)
                        </label>
                        <input type="file" wire:model="bukti_pembayaran" id="bukti_pembayaran" accept="image/*"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <p class="text-xs text-gray-400 mt-1">Upload bukti pembayaran (JPG, PNG, max 2MB)</p>
                        @error('bukti_pembayaran')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror

                        {{-- Upload loading indicator --}}
                        <div wire:loading wire:target="bukti_pembayaran" class="mt-2 text-sm text-gray-500">
                            <i class="fas fa-spinner fa-spin mr-1"></i> Mengupload...
                        </div>
                    </div>
                @endif

                {{-- Keterangan --}}
                <div class="mb-4">
                    <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Keterangan (opsional)
                    </label>
                    <textarea wire:model="keterangan" id="keterangan" rows="2"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500"
                        placeholder="Catatan transaksi..."></textarea>
                </div>

                {{-- Submit --}}
                <button type="button" wire:click="save" wire:loading.attr="disabled"
                    {{ count($items) === 0 || !$metode_pembayaran ? 'disabled' : '' }}
                    class="w-full bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700
                        disabled:from-gray-300 disabled:to-gray-300 disabled:cursor-not-allowed
                        text-white py-3 rounded-xl font-semibold transition-all duration-300 shadow-lg shadow-green-500/25 disabled:shadow-none">
                    <span wire:loading.remove wire:target="save">
                        <i class="fas fa-check-circle mr-2"></i>Simpan Transaksi
                    </span>
                    <span wire:loading wire:target="save">
                        <i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...
                    </span>
                </button>

                <a href="{{ route('penjualan.index') }}"
                    class="block text-center mt-3 text-sm text-gray-500 hover:text-gray-700 transition-colors">
                    <i class="fas fa-arrow-left mr-1"></i> Batal
                </a>
            </div>
        </div>
    </div>
</div>
