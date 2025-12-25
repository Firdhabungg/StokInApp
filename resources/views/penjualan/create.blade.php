@extends('layouts.dashboard')

@section('title', 'Transaksi Baru')
@section('page-title', 'Point of Sale')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Left: Product Selection --}}
        <div class="lg:col-span-2 space-y-4">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Pilih Barang</h2>
                
                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-4">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="flex gap-3 mb-4">
                    <button type="button" onclick="openProductModal()" 
                        class="flex-1 border border-gray-300 rounded-lg px-4 py-2.5 text-left text-gray-500 hover:border-amber-500 hover:bg-amber-50 transition-colors">
                        <i class="fas fa-search mr-2"></i>Cari dan Pilih Barang...
                    </button>
                    <input type="number" id="jumlahInput" min="1" value="1" placeholder="Qty"
                        class="w-24 border border-gray-300 rounded-lg px-3 py-2.5 text-center">
                    <button type="button" id="addSelectedBtn" onclick="addSelectedItem()" disabled
                        class="bg-green-600 hover:bg-green-700 disabled:bg-gray-300 disabled:cursor-not-allowed text-white px-6 py-2.5 rounded-lg font-medium">
                        <i class="fas fa-plus mr-1"></i> Tambah
                    </button>
                </div>

                {{-- Selected Product Preview --}}
                <div id="selectedProductPreview" class="hidden bg-amber-50 border border-amber-200 rounded-lg p-3 mb-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-semibold text-gray-900" id="previewNama"></p>
                            <p class="text-sm text-gray-500"><span id="previewKode"></span> | Stok: <span id="previewStok"></span> | Harga: <span id="previewHarga"></span></p>
                        </div>
                        <button type="button" onclick="clearSelection()" class="text-gray-400 hover:text-red-500">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>

                {{-- Item Table --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 text-gray-700">
                            <tr>
                                <th class="px-4 py-3 text-left">Barang</th>
                                <th class="px-4 py-3 text-center">Qty</th>
                                <th class="px-4 py-3 text-right">Harga</th>
                                <th class="px-4 py-3 text-right">Subtotal</th>
                                <th class="px-4 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="itemsBody">
                            <tr id="emptyRow">
                                <td colspan="5" class="px-4 py-8 text-center text-gray-400">
                                    <i class="fas fa-shopping-cart text-3xl mb-2"></i>
                                    <p>Belum ada item</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Right: Summary & Checkout --}}
        <div class="space-y-4">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Ringkasan</h2>
                
                <div class="space-y-3 mb-6">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Kode Transaksi</span>
                        <span class="font-mono font-semibold">{{ $kodeTransaksi }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Tanggal</span>
                        <span>{{ now()->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Jumlah Item</span>
                        <span id="totalItems">0</span>
                    </div>
                    <hr>
                    <div class="flex justify-between text-lg">
                        <span class="font-semibold">Total</span>
                        <span id="grandTotal" class="font-bold text-green-600">Rp 0</span>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm text-gray-600 mb-1">Keterangan (opsional)</label>
                    <textarea id="keterangan" rows="2" 
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm"
                        placeholder="Catatan transaksi..."></textarea>
                </div>

                <form id="checkoutForm" method="POST" action="{{ route('penjualan.store') }}">
                    @csrf
                    <input type="hidden" name="keterangan" id="formKeterangan">
                    <div id="formItems"></div>
                    
                    <button type="submit" id="checkoutBtn" disabled
                        class="w-full bg-green-600 hover:bg-green-700 disabled:bg-gray-300 disabled:cursor-not-allowed text-white py-3 rounded-lg font-semibold transition-colors">
                        <i class="fas fa-check mr-2"></i>Simpan Transaksi
                    </button>
                </form>

                <a href="{{ route('penjualan.index') }}" 
                    class="block text-center mt-3 text-gray-500 hover:text-gray-700">
                    Batal
                </a>
            </div>
        </div>
    </div>

    {{-- Product Selection Modal --}}
    <div id="productModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/50" onclick="closeProductModal()"></div>
        <div class="absolute inset-4 md:inset-10 lg:inset-20 bg-white rounded-2xl shadow-2xl flex flex-col overflow-hidden">
            <div class="flex items-center justify-between p-4 border-b">
                <h3 class="text-lg font-bold text-gray-900">Pilih Barang</h3>
                <button onclick="closeProductModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="flex-1 p-4 overflow-auto">
                <table id="productTable" class="w-full text-sm display">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Nama Barang</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($barangs as $barang)
                            <tr>
                                <td class="font-mono">{{ $barang->kode_barang }}</td>
                                <td class="font-medium">{{ $barang->nama_barang }}</td>
                                <td>Rp {{ number_format($barang->harga_jual, 0, ',', '.') }}</td>
                                <td>
                                    <span class="px-2 py-1 rounded-full text-xs font-medium {{ $barang->stok > 10 ? 'bg-green-100 text-green-700' : ($barang->stok > 0 ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-700') }}">
                                        {{ $barang->stok }}
                                    </span>
                                </td>
                                <td>
                                    <button type="button" 
                                        class="btn-select-product text-amber-600 hover:text-amber-700 font-medium {{ $barang->stok < 1 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                        data-id="{{ $barang->id }}"
                                        data-kode="{{ $barang->kode_barang }}"
                                        data-nama="{{ $barang->nama_barang }}"
                                        data-harga="{{ $barang->harga_jual }}"
                                        data-stok="{{ $barang->stok }}"
                                        {{ $barang->stok < 1 ? 'disabled' : '' }}>
                                        <i class="fas fa-plus-circle mr-1"></i>Pilih
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    let items = [];
    let selectedProduct = null;
    let productDataTable = null;

    function openProductModal() {
        document.getElementById('productModal').classList.remove('hidden');
        if (!productDataTable) {
            productDataTable = new DataTable('#productTable', {
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                    infoEmpty: "Tidak ada data",
                    zeroRecords: "Tidak ada barang ditemukan",
                    paginate: { first: "Pertama", last: "Terakhir", next: "Selanjutnya", previous: "Sebelumnya" }
                },
                pageLength: 10,
                order: [[1, 'asc']]
            });
        }
        
        // Bind click events to select buttons using event delegation
        document.getElementById('productTable').addEventListener('click', function(e) {
            const btn = e.target.closest('.btn-select-product');
            if (btn && !btn.disabled) {
                const id = parseInt(btn.dataset.id);
                const kode = btn.dataset.kode;
                const nama = btn.dataset.nama;
                const harga = parseInt(btn.dataset.harga);
                const stok = parseInt(btn.dataset.stok);
                selectProduct(id, kode, nama, harga, stok);
            }
        });
    }

    function closeProductModal() {
        document.getElementById('productModal').classList.add('hidden');
    }

    function selectProduct(id, kode, nama, harga, stok) {
        if (stok < 1) return;
        
        selectedProduct = { id, kode, nama, harga, stok };
        
        document.getElementById('previewNama').textContent = nama;
        document.getElementById('previewKode').textContent = kode;
        document.getElementById('previewStok').textContent = stok;
        document.getElementById('previewHarga').textContent = 'Rp ' + formatNumber(harga);
        document.getElementById('selectedProductPreview').classList.remove('hidden');
        document.getElementById('addSelectedBtn').disabled = false;
        
        closeProductModal();
    }

    function clearSelection() {
        selectedProduct = null;
        document.getElementById('selectedProductPreview').classList.add('hidden');
        document.getElementById('addSelectedBtn').disabled = true;
    }

    function addSelectedItem() {
        if (!selectedProduct) return;
        
        const jumlah = parseInt(document.getElementById('jumlahInput').value) || 1;
        
        if (jumlah > selectedProduct.stok) {
            Swal.fire('Stok Tidak Cukup', `Stok tersedia: ${selectedProduct.stok}`, 'warning');
            return;
        }

        const existingIndex = items.findIndex(i => i.barang_id == selectedProduct.id);
        if (existingIndex >= 0) {
            const newQty = items[existingIndex].jumlah + jumlah;
            if (newQty > selectedProduct.stok) {
                Swal.fire('Stok Tidak Cukup', `Stok tersedia: ${selectedProduct.stok}`, 'warning');
                return;
            }
            items[existingIndex].jumlah = newQty;
        } else {
            items.push({
                barang_id: selectedProduct.id,
                nama: selectedProduct.nama,
                kode: selectedProduct.kode,
                harga: selectedProduct.harga,
                jumlah: jumlah,
                stok: selectedProduct.stok
            });
        }

        renderItems();
        clearSelection();
        document.getElementById('jumlahInput').value = 1;
    }

    function removeItem(index) {
        items.splice(index, 1);
        renderItems();
    }

    function updateQty(index, newQty) {
        if (newQty < 1) return;
        if (newQty > items[index].stok) {
            Swal.fire('Stok Maksimal', `Stok tersedia: ${items[index].stok}`, 'warning');
            return;
        }
        items[index].jumlah = newQty;
        renderItems();
    }

    function renderItems() {
        const tbody = document.getElementById('itemsBody');
        const emptyRow = document.getElementById('emptyRow');
        
        if (items.length === 0) {
            emptyRow.style.display = '';
            document.getElementById('checkoutBtn').disabled = true;
        } else {
            emptyRow.style.display = 'none';
            document.getElementById('checkoutBtn').disabled = false;
        }

        tbody.querySelectorAll('tr:not(#emptyRow)').forEach(row => row.remove());

        let grandTotal = 0;
        let totalQty = 0;

        items.forEach((item, index) => {
            const subtotal = item.harga * item.jumlah;
            grandTotal += subtotal;
            totalQty += item.jumlah;

            const row = document.createElement('tr');
            row.className = 'border-b';
            row.innerHTML = `
                <td class="px-4 py-3">
                    <p class="font-medium">${item.nama}</p>
                    <p class="text-xs text-gray-500">${item.kode}</p>
                </td>
                <td class="px-4 py-3 text-center">
                    <div class="flex items-center justify-center gap-1">
                        <button type="button" onclick="updateQty(${index}, ${item.jumlah - 1})" 
                            class="w-6 h-6 bg-gray-200 rounded text-sm">-</button>
                        <span class="w-8 text-center">${item.jumlah}</span>
                        <button type="button" onclick="updateQty(${index}, ${item.jumlah + 1})"
                            class="w-6 h-6 bg-gray-200 rounded text-sm">+</button>
                    </div>
                </td>
                <td class="px-4 py-3 text-right">Rp ${formatNumber(item.harga)}</td>
                <td class="px-4 py-3 text-right font-semibold">Rp ${formatNumber(subtotal)}</td>
                <td class="px-4 py-3 text-center">
                    <button type="button" onclick="removeItem(${index})" 
                        class="text-red-500 hover:text-red-700">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            `;
            tbody.appendChild(row);
        });

        document.getElementById('grandTotal').textContent = `Rp ${formatNumber(grandTotal)}`;
        document.getElementById('totalItems').textContent = totalQty;
        updateFormInputs();
    }

    function updateFormInputs() {
        const formItems = document.getElementById('formItems');
        formItems.innerHTML = '';

        items.forEach((item, index) => {
            formItems.innerHTML += `
                <input type="hidden" name="items[${index}][barang_id]" value="${item.barang_id}">
                <input type="hidden" name="items[${index}][jumlah]" value="${item.jumlah}">
                <input type="hidden" name="items[${index}][harga]" value="${item.harga}">
            `;
        });

        document.getElementById('formKeterangan').value = document.getElementById('keterangan').value;
    }

    function formatNumber(num) {
        return new Intl.NumberFormat('id-ID').format(num);
    }

    document.getElementById('keterangan').addEventListener('input', updateFormInputs);
</script>
@endpush
