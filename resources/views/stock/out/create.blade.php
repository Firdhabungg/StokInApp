@extends('layouts.dashboard')

@section('title', 'Tambah Barang Keluar')
@section('page-title', 'Tambah Barang Keluar')

@section('content')
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="mb-6">
            <h2 class="text-xl font-semibold text-gray-900">Form Barang Keluar</h2>
        </div>

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

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
                {{-- Pilih Barang --}}
                <div>
                    <label for="barang_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Pilih Barang <span class="text-red-500">*</span>
                    </label>
                    <select name="barang_id" id="barang_id" required
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
                    <div id="availableStock" class="bg-gray-50 border border-gray-200 rounded-lg px-4 py-2.5 text-gray-600">
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
                    <input type="date" name="tgl_keluar" id="tgl_keluar" value="{{ old('tgl_keluar', date('Y-m-d')) }}"
                        required
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-red-500 focus:border-red-500">
                </div>

                <div>
                    <label for="alasan" class="block text-sm font-medium text-gray-700 mb-2">
                        Alasan <span class="text-red-500">*</span>
                    </label>
                    <select name="alasan" id="alasan" required
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
                    {{-- <svg wire:loading wire:target="submit" aria-hidden="true" class="w-4 h-4 text-neutral-quaternary animate-spin fill-brand"
                        viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                            fill="currentColor" />
                        <path
                            d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                            fill="currentFill" />
                    </svg>
                    <span class="sr-only">Loading...</span> --}}
                    Simpan
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        let currentBatches = [];
        let currentMode = '{{ old('mode', 'fifo') }}';

        /* ------------------------------------------------------------------ */
        /*  INIT — jalankan saat DOM siap                                       */
        /* ------------------------------------------------------------------ */
        document.addEventListener('DOMContentLoaded', function() {
            // Terapkan mode dari old() jika ada (misalnya setelah validasi gagal)
            applyMode(currentMode);

            // Load stok jika barang sudah terpilih (old input)
            const barangId = document.getElementById('barang_id').value;
            if (barangId) onBarangChange(barangId);
        });

        /* ------------------------------------------------------------------ */
        /*  MODE TOGGLE                                                         */
        /* ------------------------------------------------------------------ */
        function setMode(mode) {
            currentMode = mode;
            document.getElementById('modeInput').value = mode;
            applyMode(mode);
        }

        function applyMode(mode) {
            const btnFifo = document.getElementById('btnFifo');
            const btnManual = document.getElementById('btnManual');
            const jumlahWrapper = document.getElementById('jumlahWrapper');
            const jumlahInput = document.getElementById('jumlah');
            const fifoPanel = document.getElementById('fifoPanel');
            const manualPanel = document.getElementById('manualPanel');

            if (mode === 'fifo') {
                // Tombol aktif
                btnFifo.classList.add('border-red-500', 'bg-red-50', 'text-red-700');
                btnFifo.classList.remove('border-gray-200', 'text-gray-500');
                btnManual.classList.remove('border-orange-400', 'bg-orange-50', 'text-orange-700');
                btnManual.classList.add('border-gray-200', 'text-gray-500');

                // Tampilkan field jumlah, wajibkan isian
                jumlahWrapper.classList.remove('hidden');
                jumlahInput.required = true;

                // Panel: tampilkan FIFO, sembunyikan Manual
                manualPanel.classList.add('hidden');
                if (currentBatches.length > 0) {
                    fifoPanel.classList.remove('hidden');
                }

                // Hapus semua input tersembunyi batch manual dari form
                clearManualBatchInputs();

            } else {
                // Tombol aktif
                btnManual.classList.add('border-orange-400', 'bg-orange-50', 'text-orange-700');
                btnManual.classList.remove('border-gray-200', 'text-gray-500');
                btnFifo.classList.remove('border-red-500', 'bg-red-50', 'text-red-700');
                btnFifo.classList.add('border-gray-200', 'text-gray-500');

                // Sembunyikan & tidak-wajibkan field jumlah
                jumlahWrapper.classList.add('hidden');
                jumlahInput.required = false;
                jumlahInput.value = '';

                // Panel: sembunyikan FIFO, tampilkan Manual
                fifoPanel.classList.add('hidden');
                manualPanel.classList.remove('hidden');

                if (currentBatches.length > 0) renderManualBatches();
            }
        }

        /* ------------------------------------------------------------------ */
        /*  FETCH STOK TERSEDIA                                                 */
        /* ------------------------------------------------------------------ */
        function onBarangChange(barangId) {
            const stockEl = document.getElementById('availableStock');

            if (!barangId) {
                stockEl.innerHTML = 'Pilih barang terlebih dahulu';
                document.getElementById('fifoPanel').classList.add('hidden');
                document.getElementById('manualPanel').classList.add('hidden');
                currentBatches = [];
                return;
            }

            stockEl.innerHTML = '<span class="text-gray-400 italic text-sm">Memuat...</span>';

            fetch(`/stock/out/available-stock/${barangId}`)
                .then(r => r.json())
                .then(data => {
                    currentBatches = data.batches;
                    stockEl.innerHTML =
                        `<span class="font-semibold text-green-600">${data.total_stock}</span> unit tersedia`;

                    if (currentMode === 'fifo') {
                        renderFifoBatches();
                    } else {
                        renderManualBatches();
                    }
                })
                .catch(() => {
                    stockEl.innerHTML = '<span class="text-red-500">Error loading data</span>';
                });
        }

        /* ------------------------------------------------------------------ */
        /*  RENDER FIFO PREVIEW                                                 */
        /* ------------------------------------------------------------------ */
        function renderFifoBatches() {
            const panel = document.getElementById('fifoPanel');
            const list = document.getElementById('batchList');

            if (!currentBatches.length) {
                panel.classList.add('hidden');
                return;
            }

            list.innerHTML = currentBatches.map(b => {
                const cls = b.status === 'aman' ?
                    'bg-green-100 text-green-700' :
                    b.status === 'hampir_kadaluarsa' ?
                    'bg-orange-100 text-orange-700' :
                    'bg-red-100 text-red-700';
                const ed = b.tgl_kadaluarsa ?
                    new Date(b.tgl_kadaluarsa).toLocaleDateString('id-ID') :
                    '-';
                return `
                <div class="flex flex-wrap justify-between items-center gap-2 text-sm">
                    <span class="font-mono font-medium">${b.batch_code}</span>
                    <span class="text-gray-600">Sisa: <strong>${b.jumlah_sisa}</strong></span>
                    <span class="text-gray-600">ED: ${ed}</span>
                    <span class="px-2 py-0.5 rounded text-xs font-medium ${cls}">${b.status}</span>
                </div>`;
            }).join('');

            panel.classList.remove('hidden');
        }

        /* ------------------------------------------------------------------ */
        /*  RENDER MANUAL BATCH CHECKBOXES                                      */
        /* ------------------------------------------------------------------ */
        function renderManualBatches() {
            const container = document.getElementById('batchCheckboxList');
            const noMsg = document.getElementById('noBatchMsg');

            if (!currentBatches.length) {
                container.innerHTML = '';
                noMsg.classList.remove('hidden');
                return;
            }

            noMsg.classList.add('hidden');

            container.innerHTML = currentBatches.map((b, i) => {
                const statusCls = b.status === 'aman' ?
                    'bg-green-100 text-green-700 border-green-200' :
                    b.status === 'hampir_kadaluarsa' ?
                    'bg-orange-100 text-orange-700 border-orange-200' :
                    'bg-red-100 text-red-700 border-red-200';

                const cardBorder = b.status === 'kadaluarsa' ?
                    'border-red-200 bg-red-50' :
                    'border-gray-200 bg-white';

                const ed = b.tgl_kadaluarsa ?
                    new Date(b.tgl_kadaluarsa).toLocaleDateString('id-ID') :
                    '-';

                const expiredWarning = b.status === 'kadaluarsa' ?
                    `<p class="text-xs text-red-600 mt-1 flex items-center gap-1">
                       <i class="fas fa-exclamation-triangle"></i>
                       Batch ini sudah kadaluarsa — pilih untuk dikeluarkan secara manual.
                   </p>` :
                    '';

                // PENTING: checkbox TIDAK memiliki name/value saat render.
                // name & value akan di-inject lewat JS saat dicentang,
                // dan dihapus saat tidak dicentang. Ini mencegah data batch
                // terkirim ke controller saat mode FIFO.
                return `
            <div class="border rounded-lg p-4 ${cardBorder} transition-all" id="batchCard_${i}">
                <div class="flex items-start gap-3">
                    <input type="checkbox"
                        id="batchCheck_${i}"
                        data-index="${i}"
                        data-batch-id="${b.id}"
                        data-max="${b.jumlah_sisa}"
                        class="mt-1 h-4 w-4 text-orange-500 rounded border-gray-300 cursor-pointer"
                        onchange="toggleBatchRow(${i})">

                    <label for="batchCheck_${i}" class="flex-1 cursor-pointer">
                        <div class="flex flex-wrap items-center justify-between gap-2">
                            <span class="font-mono font-semibold text-gray-800">${b.batch_code}</span>
                            <span class="text-xs px-2 py-0.5 rounded border font-medium ${statusCls}">${b.status}</span>
                        </div>
                        <div class="flex flex-wrap gap-4 text-sm text-gray-500 mt-1">
                            <span>Sisa stok: <strong class="text-gray-700">${b.jumlah_sisa}</strong> unit</span>
                            <span>ED: <strong class="text-gray-700">${ed}</strong></span>
                        </div>
                        ${expiredWarning}
                    </label>
                </div>

                <div id="qtyRow_${i}" class="hidden mt-3 ml-7">
                    <label class="block text-xs font-medium text-gray-600 mb-1">
                        Jumlah yang dikeluarkan dari batch ini:
                    </label>
                    <div class="flex items-center gap-2">
                        <input type="number"
                            id="batchQty_${i}"
                            min="1"
                            max="${b.jumlah_sisa}"
                            value="1"
                            class="w-32 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-400 focus:border-orange-400"
                            oninput="recalcTotal()">
                        <span class="text-xs text-gray-400">maks. ${b.jumlah_sisa}</span>
                    </div>
                </div>
            </div>`;
            }).join('');

            recalcTotal();
        }

        /* ------------------------------------------------------------------ */
        /*  TOGGLE QTY ROW — inject / hapus hidden inputs saat centang         */
        /* ------------------------------------------------------------------ */
        function toggleBatchRow(index) {
            const cb = document.getElementById(`batchCheck_${index}`);
            const qtyRow = document.getElementById(`qtyRow_${index}`);
            const qty = document.getElementById(`batchQty_${index}`);

            if (cb.checked) {
                qtyRow.classList.remove('hidden');
                qty.value = 1;
            } else {
                qtyRow.classList.add('hidden');
                qty.value = '';
            }

            recalcTotal();
        }

        /* ------------------------------------------------------------------ */
        /*  RECALC TOTAL                                                        */
        /* ------------------------------------------------------------------ */
        function recalcTotal() {
            let total = 0;
            currentBatches.forEach((_, i) => {
                const cb = document.getElementById(`batchCheck_${i}`);
                const qty = document.getElementById(`batchQty_${i}`);
                if (cb && cb.checked && qty) {
                    total += parseInt(qty.value) || 0;
                }
            });
            document.getElementById('manualTotalQty').textContent = total;
        }

        /* ------------------------------------------------------------------ */
        /*  HAPUS SEMUA HIDDEN INPUT BATCH MANUAL DARI FORM                     */
        /* ------------------------------------------------------------------ */
        function clearManualBatchInputs() {
            document.querySelectorAll('input[data-manual-batch]').forEach(el => el.remove());
        }

        /* ------------------------------------------------------------------ */
        /*  INJECT HIDDEN INPUTS SEBELUM SUBMIT (mode manual)                  */
        /* ------------------------------------------------------------------ */
        function injectManualBatchInputs() {
            clearManualBatchInputs();

            const form = document.getElementById('stockOutForm');
            let idx = 0;

            currentBatches.forEach((b, i) => {
                const cb = document.getElementById(`batchCheck_${i}`);
                const qty = document.getElementById(`batchQty_${i}`);
                if (!cb || !cb.checked) return;

                const hiddenId = document.createElement('input');
                hiddenId.type = 'hidden';
                hiddenId.name = `batches[${idx}][batch_id]`;
                hiddenId.value = b.id;
                hiddenId.setAttribute('data-manual-batch', '1');

                const hiddenQty = document.createElement('input');
                hiddenQty.type = 'hidden';
                hiddenQty.name = `batches[${idx}][jumlah]`;
                hiddenQty.value = parseInt(qty.value) || 1;
                hiddenQty.setAttribute('data-manual-batch', '1');

                form.appendChild(hiddenId);
                form.appendChild(hiddenQty);
                idx++;
            });

            return idx; // jumlah batch yang dipilih
        }

        /* ------------------------------------------------------------------ */
        /*  VALIDASI & SUBMIT                                                   */
        /* ------------------------------------------------------------------ */
        document.getElementById('stockOutForm').addEventListener('submit', function(e) {
            if (currentMode === 'manual') {
                // Cek minimal 1 batch dipilih
                const anyChecked = currentBatches.some((_, i) => {
                    const cb = document.getElementById(`batchCheck_${i}`);
                    return cb && cb.checked;
                });

                if (!anyChecked) {
                    e.preventDefault();
                    alert('Pilih minimal satu batch untuk dikeluarkan.');
                    return;
                }

                // Validasi qty tiap batch yang dicentang
                let valid = true;
                currentBatches.forEach((b, i) => {
                    const cb = document.getElementById(`batchCheck_${i}`);
                    const qty = document.getElementById(`batchQty_${i}`);
                    if (cb && cb.checked) {
                        const val = parseInt(qty.value) || 0;
                        if (val < 1 || val > b.jumlah_sisa) {
                            alert(`Jumlah batch ${b.batch_code} tidak valid (1 - ${b.jumlah_sisa}).`);
                            valid = false;
                        }
                    }
                });

                if (!valid) {
                    e.preventDefault();
                    return;
                }

                // Inject hidden inputs ke form sebelum submit
                injectManualBatchInputs();
            }
        });
    </script>
@endpush
