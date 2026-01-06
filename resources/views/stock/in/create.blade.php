@extends('layouts.dashboard')

@section('title', 'Tambah Barang Masuk')
@section('page-title', 'Tambah Barang Masuk')

@section('content')
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="mb-6">
            <h2 class="text-xl font-semibold text-gray-900">Form Barang Masuk</h2>
            <p class="text-gray-500 text-sm mt-1">Catat barang masuk dengan batch baru</p>
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

        <form action="{{ route('stock.in.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="barang_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Pilih Barang <span class="text-red-500">*</span>
                    </label>
                    <select name="barang_id" id="barang_id" required
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="">-- Pilih Barang --</option>
                        @foreach ($barangs as $barang)
                            <option value="{{ $barang->id }}" {{ old('barang_id') == $barang->id ? 'selected' : '' }}>
                                {{ $barang->kode_barang }} - {{ $barang->nama_barang }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="jumlah" class="block text-sm font-medium text-gray-700 mb-2">
                        Jumlah <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="jumlah" id="jumlah" min="1" value="{{ old('jumlah', 1) }}"
                        required
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>

                <div>
                    <label for="tgl_masuk" class="block text-sm font-medium text-gray-700 mb-2">
                        Tanggal Masuk <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="tgl_masuk" id="tgl_masuk" value="{{ old('tgl_masuk', date('Y-m-d')) }}"
                        required
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>

                <div>
                    <label for="tgl_kadaluarsa" class="block text-sm font-medium text-gray-700 mb-2">
                        Tanggal Kadaluarsa
                    </label>
                    <input type="date" name="tgl_kadaluarsa" id="tgl_kadaluarsa" value="{{ old('tgl_kadaluarsa') }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    <p class="text-xs text-gray-500 mt-1">Kosongkan jika barang tidak memiliki kadaluwarsa</p>
                </div>

                <div>
                    <label for="supplier" class="block text-sm font-medium text-gray-700 mb-2">
                        Supplier
                    </label>
                    <input type="text" name="supplier" id="supplier" value="{{ old('supplier') }}"
                        placeholder="Nama supplier (opsional)"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>

                <div class="md:col-span-2">
                    <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">
                        Keterangan
                    </label>
                    <textarea name="keterangan" id="keterangan" rows="3" placeholder="Catatan tambahan (opsional)"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-green-500 focus:border-green-500">{{ old('keterangan') }}</textarea>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-4">
                <a href="{{ route('stock.in.index') }}"
                    class="px-6 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                    Batal
                </a>
                <button type="submit"
                    class="px-6 py-2.5 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition-colors">
                    <i class="fas fa-save mr-2"></i>Simpan
                </button>
            </div>
        </form>
    </div>
@endsection
