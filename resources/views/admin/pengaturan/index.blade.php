@extends('admin.layouts.app')

@section('title', 'Pengaturan')
@section('header_title', 'Pengaturan Aplikasi')
@section('header_description', 'Pengaturan umum dan informasi dasar aplikasi')

@section('content')
    <div class="space-y-6">

        {{-- Alert sukses --}}
        @if (session('success'))
            <div id="successAlert"
                class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm transition-opacity duration-500">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
            </div>
            <script>
                setTimeout(() => {
                    const alert = document.getElementById('successAlert');
                    if (alert) {
                        alert.style.opacity = '0';
                        setTimeout(() => alert.remove(), 500);
                    }
                }, 3000);
            </script>
        @endif

        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.pengaturan.update') }}" method="POST" class="space-y-6">
            @csrf

            {{-- =====================
        IDENTITAS APLIKASI
        ===================== --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-bold text-gray-800">Identitas Aplikasi</h3>
                    <p class="text-sm text-gray-500">Informasi dasar yang ditampilkan pada sistem</p>
                </div>

                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-sm font-semibold text-gray-600">Nama Aplikasi</label>
                        <input type="text" name="nama_aplikasi" value="{{ old('nama_aplikasi', $settings['app_name']) }}"
                            class="mt-2 w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 focus:ring-2 focus:ring-amber-500 outline-none">
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-gray-600">Versi Aplikasi</label>
                        <input type="text" name="versi" value="{{ old('versi', $settings['app_version']) }}"
                            class="mt-2 w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 focus:ring-2 focus:ring-amber-500 outline-none">
                    </div>
                </div>
            </div>

            {{-- =====================
        INFORMASI ADMIN
        ===================== --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-bold text-gray-800">Informasi Administrator</h3>
                    <p class="text-sm text-gray-500">Kontak utama pengelola aplikasi</p>
                </div>

                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-sm font-semibold text-gray-600">Email Admin</label>
                        <input type="email" name="email_admin" value="{{ old('email_admin', $settings['admin_email']) }}"
                            class="mt-2 w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 focus:ring-2 focus:ring-amber-500 outline-none">
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-gray-600">Nomor Telepon</label>
                        <input type="text" name="telepon" value="{{ old('telepon', $settings['admin_phone']) }}"
                            placeholder="08xxxxxxxxxx"
                            class="mt-2 w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 focus:ring-2 focus:ring-amber-500 outline-none">
                    </div>

                    <div class="md:col-span-2">
                        <label class="text-sm font-semibold text-gray-600">Alamat</label>
                        <textarea name="alamat" rows="3"
                            class="mt-2 w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 focus:ring-2 focus:ring-amber-500 outline-none"
                            placeholder="Alamat kantor / pengelola aplikasi">{{ old('alamat', $settings['admin_address']) }}</textarea>
                    </div>
                </div>
            </div>

            {{-- =====================
        ACTION BUTTON
        ===================== --}}
            <div class="flex justify-end gap-3">
                <button type="reset"
                    class="px-5 py-2.5 rounded-xl border border-gray-200 text-gray-600 font-semibold hover:bg-gray-50">
                    Reset
                </button>
                <button type="submit"
                    class="px-6 py-2.5 rounded-xl bg-amber-500 text-white font-bold hover:bg-amber-600 transition">
                    Simpan Pengaturan
                </button>
            </div>
        </form>

    </div>
@endsection
