@extends('layouts.dashboard')

@section('title', 'Edit Barang')

@section('content')
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h1 class="font-semibold text-2xl text-gray-900">Edit Barang</h1>
        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('barang.update', $barang->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="space-y-12">
                <div class="border-b border-gray-900/10 pb-12">
                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div class="sm:col-span-3">
                            <label for="nama_barang" class="block text-sm/6 font-medium text-gray-900">Nama Barang</label>
                            <div
                                class="mt-2 flex items-center rounded-md bg-white pl-3 outline-1 -outline-offset-1 outline-gray-300 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-amber-600">
                                <input id="nama_barang" type="text" name="nama_barang"
                                    value="{{ old('nama_barang', $barang->nama_barang) }}" required
                                    class="block min-w-0 grow bg-white py-1.5 pr-3 pl-1 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none sm:text-sm/6" />
                            </div>
                        </div>
                        <div class="sm:col-span-2">
                            <label for="kategori_id" class="block text-sm/6 font-medium text-gray-900">Kategori
                                Barang</label>
                            <div
                                class="mt-2 flex items-center rounded-md bg-white pl-3 outline-1 -outline-offset-1 outline-gray-300 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-amber-600">

                                <select name="kategori_id" id="kategori_id" required
                                    class="block min-w-0 grow bg-white py-2 pr-6 text-base text-gray-900 focus:outline-none sm:text-sm/6">
                                    <option value="">Pilih Kategori</option>
                                    @foreach ($kategoris as $kategori)
                                        <option value="{{ $kategori->kategori_id }}"
                                            {{ old('kategori_id', $barang->kategori_id) == $kategori->kategori_id ? 'selected' : '' }}>
                                            {{ $kategori->nama_kategori }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="sm:col-span-1">
                            <label for="kode_barang" class="block text-sm/6 font-medium text-gray-900">Kode Barang</label>
                            <div
                                class="mt-2 flex items-center rounded-md bg-gray-50 pl-3 outline-1 -outline-offset-1 outline-gray-300">
                                <input id="kode_barang" type="text" name="kode_barang"
                                    value="{{ old('kode_barang', $barang->kode_barang) }}" readonly
                                    class="block min-w-0 grow bg-gray-50 py-1.5 pr-3 pl-1 text-base text-gray-700 font-medium focus:outline-none sm:text-sm/6 cursor-not-allowed" />
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Tidak dapat diubah</p>
                        </div>
                        <div class="sm:col-span-2">
                            <label for="harga" class="block text-sm/6 font-medium text-gray-900">Harga Barang</label>
                            <div
                                class="mt-2 flex items-center rounded-md bg-white pl-3 outline-1 -outline-offset-1 outline-gray-300 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-amber-600">
                                <input id="harga" type="number" name="harga"
                                    value="{{ old('harga', $barang->harga) }}" required
                                    class="block min-w-0 grow bg-white py-1.5 pr-3 pl-1 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none sm:text-sm/6" />
                            </div>
                        </div>

                        <div class="sm:col-span-2">
                            <label for="kadaluwarsa" class="block text-sm/6 font-medium text-gray-900">Kadaluwarsa</label>
                            <div
                                class="mt-2 flex items-center rounded-md bg-white pl-3 outline-1 -outline-offset-1 outline-gray-300 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-amber-600">
                                <input id="kadaluwarsa" type="date" name="tgl_kadaluwarsa"
                                    value="{{ old('tgl_kadaluwarsa', $barang->tgl_kadaluwarsa ? $barang->tgl_kadaluwarsa->format('Y-m-d') : '') }}"
                                    class="block min-w-0 grow bg-white py-1.5 pr-3 pl-1 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none sm:text-sm/6" />
                            </div>
                        </div>
                        <div class="sm:col-span-1">
                            <label for="stok" class="block text-sm/6 font-medium text-gray-900">Stok</label>
                            <div
                                class="mt-2 flex items-center rounded-md bg-white pl-3 outline-1 -outline-offset-1 outline-gray-300 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-amber-600">
                                <input id="stok" type="number" name="stok" value="{{ old('stok', $barang->stok) }}"
                                    required
                                    class="block min-w-0 grow bg-white py-1.5 pr-3 pl-1 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none sm:text-sm/6" />
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="mt-6 flex items-center justify-end gap-x-6">
                <a href="{{ route('barang.index') }}"
                    class="rounded-md text-sm/6 text-white font-semibold px-3 py-2 bg-red-500 hover:bg-red-400">Cancel</a>
                <button type="submit"
                    class="rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-xs hover:bg-green-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600">Update</button>
            </div>
        </form>
    </div>

@endsection
