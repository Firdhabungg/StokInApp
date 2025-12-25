@extends('admin.layouts.app')

@section('header_title', 'Pengaturan Aplikasi')
@section('header_description', 'Pengaturan umum dan informasi dasar aplikasi')

@section('content')
<div class="space-y-6">

    {{-- Alert sukses --}}
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('admin.pengaturan.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
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
                    <input type="text" name="nama_aplikasi"
                        value="StokIn"
                        class="mt-2 w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 focus:ring-2 focus:ring-amber-500 outline-none">
                </div>

                <div>
                    <label class="text-sm font-semibold text-gray-600">Versi Aplikasi</label>
                    <input type="text" name="versi"
                        value="1.0.0"
                        class="mt-2 w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 focus:ring-2 focus:ring-amber-500 outline-none">
                </div>

                <div class="md:col-span-2">
                    <label class="text-sm font-semibold text-gray-600">Logo Aplikasi</label>
                    <input type="file" name="logo"
                        class="mt-2 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4
                        file:rounded-xl file:border-0 file:bg-amber-100 file:text-amber-700 hover:file:bg-amber-200">
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
                    <input type="email" name="email_admin"
                        value="admin@stokin.id"
                        class="mt-2 w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 focus:ring-2 focus:ring-amber-500 outline-none">
                </div>

                <div>
                    <label class="text-sm font-semibold text-gray-600">Nomor Telepon</label>
                    <input type="text" name="telepon"
                        value="08xxxxxxxxxx"
                        class="mt-2 w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 focus:ring-2 focus:ring-amber-500 outline-none">
                </div>

                <div class="md:col-span-2">
                    <label class="text-sm font-semibold text-gray-600">Alamat</label>
                    <textarea name="alamat" rows="3"
                        class="mt-2 w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 focus:ring-2 focus:ring-amber-500 outline-none"
                        placeholder="Alamat kantor / pengelola aplikasi"></textarea>
                </div>
            </div>
        </div>

        {{-- =====================
        PENGATURAN SISTEM
        ===================== --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-bold text-gray-800">Pengaturan Sistem</h3>
                <p class="text-sm text-gray-500">Kontrol dasar operasional aplikasi</p>
            </div>

            <div class="p-6 space-y-4">
                <label class="flex items-center justify-between cursor-pointer">
                    <div>
                        <p class="font-semibold text-gray-700">Status Aplikasi</p>
                        <p class="text-sm text-gray-500">Aktifkan atau nonaktifkan aplikasi</p>
                    </div>
                    <input type="checkbox" name="status_aplikasi" checked
                        class="w-5 h-5 text-amber-500 rounded focus:ring-amber-500">
                </label>

                <label class="flex items-center justify-between cursor-pointer">
                    <div>
                        <p class="font-semibold text-gray-700">Mode Maintenance</p>
                        <p class="text-sm text-gray-500">Blokir akses pengguna sementara</p>
                    </div>
                    <input type="checkbox" name="maintenance"
                        class="w-5 h-5 text-amber-500 rounded focus:ring-amber-500">
                </label>
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
