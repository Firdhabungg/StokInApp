@extends('layouts.dashboard')

@section('title', 'Tambah Barang Keluar')
@section('page-title', 'Tambah Barang Keluar')

@section('content')
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="mb-6">
            <h2 class="text-xl font-semibold text-gray-900">Form Barang Keluar</h2>
            <p class="text-gray-500 text-sm mt-1">Sistem akan otomatis menggunakan metode FIFO (First In First Out)</p>
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

        <form action="{{ route('stock.out.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Pilih Barang --}}
                <div>
                    <label for="barang_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Pilih Barang <span class="text-red-500">*</span>
                    </label>
                    <select name="barang_id" id="barang_id" required
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-red-500 focus:border-red-500"
                        onchange="getAvailableStock(this.value)">
                        <option value="">-- Pilih Barang --</option>
                        @foreach ($barangs as $barang)
                            <option value="{{ $barang->id }}" {{ old('barang_id') == $barang->id ? 'selected' : '' }}>
                                {{ $barang->kode_barang }} - {{ $barang->nama_barang }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Stok Tersedia --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Stok Tersedia</label>
                    <div id="availableStock" class="bg-gray-50 border border-gray-200 rounded-lg px-4 py-2.5 text-gray-600">
                        Pilih barang terlebih dahulu
                    </div>
                </div>

                {{-- Jumlah --}}
                <div>
                    <label for="jumlah" class="block text-sm font-medium text-gray-700 mb-2">
                        Jumlah Keluar <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="jumlah" id="jumlah" min="1" value="{{ old('jumlah', 1) }}" required
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-red-500 focus:border-red-500">
                </div>

                {{-- Tanggal Keluar --}}
                <div>
                    <label for="tgl_keluar" class="block text-sm font-medium text-gray-700 mb-2">
                        Tanggal Keluar <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="tgl_keluar" id="tgl_keluar" value="{{ old('tgl_keluar', date('Y-m-d')) }}" required
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-red-500 focus:border-red-500">
                </div>

                {{-- Alasan --}}
                <div>
                    <label for="alasan" class="block text-sm font-medium text-gray-700 mb-2">
                        Alasan <span class="text-red-500">*</span>
                    </label>
                    <select name="alasan" id="alasan" required
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-red-500 focus:border-red-500">
                        <option value="penjualan" {{ old('alasan') == 'penjualan' ? 'selected' : '' }}>Penjualan</option>
                        <option value="rusak" {{ old('alasan') == 'rusak' ? 'selected' : '' }}>Rusak</option>
                        <option value="kadaluarsa" {{ old('alasan') == 'kadaluarsa' ? 'selected' : '' }}>Kadaluarsa</option>
                        <option value="retur" {{ old('alasan') == 'retur' ? 'selected' : '' }}>Retur</option>
                        <option value="lainnya" {{ old('alasan') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>

                {{-- Keterangan --}}
                <div class="md:col-span-2">
                    <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">
                        Keterangan
                    </label>
                    <textarea name="keterangan" id="keterangan" rows="3" 
                        placeholder="Catatan tambahan (opsional)"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-red-500 focus:border-red-500">{{ old('keterangan') }}</textarea>
                </div>
            </div>

            {{-- Batch Info --}}
            <div id="batchInfo" class="mt-6 hidden">
                <h3 class="text-sm font-medium text-gray-700 mb-2">Batch yang akan diambil (FIFO):</h3>
                <div id="batchList" class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                    <!-- Filled by JS -->
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-4">
                <a href="{{ route('stock.out.index') }}" 
                    class="px-6 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                    Batal
                </a>
                <button type="submit"
                    class="px-6 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition-colors">
                    <i class="fas fa-save mr-2"></i>Simpan Barang Keluar
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
    let currentBatches = [];

    function getAvailableStock(barangId) {
        if (!barangId) {
            document.getElementById('availableStock').innerHTML = 'Pilih barang terlebih dahulu';
            document.getElementById('batchInfo').classList.add('hidden');
            return;
        }

        fetch(`/stock/out/available-stock/${barangId}`)
            .then(response => response.json())
            .then(data => {
                currentBatches = data.batches;
                document.getElementById('availableStock').innerHTML = 
                    `<span class="font-semibold text-green-600">${data.total_stock}</span> unit tersedia`;
                
                if (data.batches.length > 0) {
                    let batchHtml = '<div class="space-y-2">';
                    data.batches.forEach((batch, index) => {
                        let statusClass = batch.status === 'aman' ? 'bg-green-100 text-green-700' :
                                         batch.status === 'hampir_kadaluarsa' ? 'bg-orange-100 text-orange-700' :
                                         'bg-red-100 text-red-700';
                        batchHtml += `
                            <div class="flex justify-between items-center text-sm">
                                <span class="font-mono">${batch.batch_code}</span>
                                <span>Sisa: ${batch.jumlah_sisa}</span>
                                <span>ED: ${batch.tgl_kadaluarsa || '-'}</span>
                                <span class="px-2 py-0.5 rounded text-xs ${statusClass}">${batch.status}</span>
                            </div>
                        `;
                    });
                    batchHtml += '</div>';
                    document.getElementById('batchList').innerHTML = batchHtml;
                    document.getElementById('batchInfo').classList.remove('hidden');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('availableStock').innerHTML = 'Error loading data';
            });
    }

    // Initialize if barang_id is pre-selected
    document.addEventListener('DOMContentLoaded', function() {
        const barangId = document.getElementById('barang_id').value;
        if (barangId) {
            getAvailableStock(barangId);
        }
    });
</script>
@endpush
