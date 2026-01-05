@extends('layouts.dashboard')

@section('title', 'Edit Kategori')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center gap-3 mb-6">
                <a href="{{ route('kategori.index') }}" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 class="font-semibold text-2xl text-gray-900">Edit Kategori</h1>
            </div>

            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('kategori.update', $kategori->kategori_id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-5">
                    <div>
                        <label for="nama_kategori" class="block text-sm font-medium text-gray-700 mb-1">
                            Nama Kategori <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama_kategori" id="nama_kategori" required
                            value="{{ old('nama_kategori', $kategori->nama_kategori) }}"
                            placeholder="Contoh: Makanan Ringan, Minuman, Sembako"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors">
                    </div>

                    <div>
                        <label for="deskripsi_kategori" class="block text-sm font-medium text-gray-700 mb-1">
                            Deskripsi (Opsional)
                        </label>
                        <textarea name="deskripsi_kategori" id="deskripsi_kategori" rows="3"
                            placeholder="Deskripsi singkat tentang kategori ini..."
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors">{{ old('deskripsi_kategori', $kategori->deskripsi_kategori) }}</textarea>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 mt-6 pt-6 border-t border-gray-100">
                    <a href="{{ route('kategori.index') }}"
                        class="px-4 py-2 text-gray-700 hover:text-gray-900 font-medium transition-colors">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-6 py-2.5 bg-amber-600 hover:bg-amber-700 text-white font-medium rounded-lg transition-colors">
                        <i class="fas fa-save mr-2"></i>
                        Update Kategori
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
