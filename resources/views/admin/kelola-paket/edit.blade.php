@extends('admin.layouts.app')

@section('header_title', 'Edit Paket')
@section('header_description', 'Ubah konfigurasi paket {{ $plan->name }}')

@section('content')
<div class="max-w-3xl">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center gap-4 mb-6">
            <a href="{{ route('admin.kelola-paket.index') }}" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h2 class="text-xl font-semibold text-gray-900">Edit Paket: {{ $plan->name }}</h2>
        </div>

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('admin.kelola-paket.update', $plan) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                {{-- Nama Paket --}}
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Paket</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $plan->name) }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                        required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Harga & Durasi --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Harga (Rp)</label>
                        <input type="number" id="price" name="price" value="{{ old('price', $plan->price) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                            min="0" required>
                        @error('price')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="duration_days" class="block text-sm font-medium text-gray-700 mb-2">Durasi (Hari)</label>
                        <input type="number" id="duration_days" name="duration_days" value="{{ old('duration_days', $plan->duration_days) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                            min="1" required>
                        @error('duration_days')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Max Users --}}
                <div>
                    <label for="max_users" class="block text-sm font-medium text-gray-700 mb-2">
                        Limit User <span class="text-gray-400 text-xs">(-1 untuk unlimited)</span>
                    </label>
                    <input type="number" id="max_users" name="max_users" 
                        value="{{ old('max_users', $plan->features['max_users'] ?? 1) }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                        min="-1" required>
                    @error('max_users')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Description --}}
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Singkat</label>
                    <input type="text" id="description" name="description" 
                        value="{{ old('description', $plan->features['description'] ?? '') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                        placeholder="Contoh: Fitur lengkap untuk bisnis Anda">
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Feature Toggles --}}
                <div class="border-t pt-6">
                    <p class="text-sm font-medium text-gray-700 mb-4">Fitur Tambahan</p>
                    <div class="space-y-4">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="hidden" name="export_report" value="0">
                            <input type="checkbox" name="export_report" value="1"
                                {{ old('export_report', $plan->features['export_report'] ?? false) ? 'checked' : '' }}
                                class="w-5 h-5 text-amber-500 border-gray-300 rounded focus:ring-amber-500">
                            <span class="text-gray-700">Export Laporan (Excel/PDF)</span>
                        </label>

                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="hidden" name="priority_support" value="0">
                            <input type="checkbox" name="priority_support" value="1"
                                {{ old('priority_support', $plan->features['priority_support'] ?? false) ? 'checked' : '' }}
                                class="w-5 h-5 text-amber-500 border-gray-300 rounded focus:ring-amber-500">
                            <span class="text-gray-700">Priority Support</span>
                        </label>

                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="hidden" name="analytics_dashboard" value="0">
                            <input type="checkbox" name="analytics_dashboard" value="1"
                                {{ old('analytics_dashboard', $plan->features['analytics_dashboard'] ?? false) ? 'checked' : '' }}
                                class="w-5 h-5 text-amber-500 border-gray-300 rounded focus:ring-amber-500">
                            <span class="text-gray-700">Analytics Dashboard</span>
                        </label>
                    </div>
                </div>

                {{-- Submit --}}
                <div class="flex justify-end gap-4 pt-6 border-t">
                    <a href="{{ route('admin.kelola-paket.index') }}"
                        class="px-6 py-3 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-colors font-medium">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-6 py-3 bg-amber-500 hover:bg-amber-600 text-white rounded-xl transition-colors font-medium">
                        <i class="fas fa-save mr-2"></i>Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
