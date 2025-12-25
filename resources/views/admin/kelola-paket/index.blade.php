@extends('admin.layouts.app')

@section('header_title', 'Manajemen Paket')
@section('header_description', 'Atur harga dan fitur paket berlangganan StokIn')

@section('content')
<div class="space-y-6">

    {{-- SUMMARY CARD --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-gray-50 text-gray-400 rounded-xl">
                    <i class="fas fa-box-open text-lg"></i>
                </div>
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Entry Level</span>
            </div>
            <h3 class="text-xl font-bold text-gray-900">Starter Plan</h3>
            <p class="text-3xl font-extrabold text-gray-900 mt-2">
                42 <span class="text-sm font-medium text-gray-400">Toko Aktif</span>
            </p>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-amber-100 relative overflow-hidden">
            <span class="absolute top-0 right-0 bg-amber-500 text-white text-[10px] font-black px-4 py-1 rounded-bl-xl uppercase">
                Populer
            </span>
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-amber-50 text-amber-600 rounded-xl">
                    <i class="fas fa-bolt text-lg"></i>
                </div>
                <span class="text-[10px] font-bold text-amber-500 uppercase tracking-widest">Growth</span>
            </div>
            <h3 class="text-xl font-bold text-gray-900">Pro Plan</h3>
            <p class="text-3xl font-extrabold text-gray-900 mt-2">
                86 <span class="text-sm font-medium text-gray-400">Toko Aktif</span>
            </p>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-purple-50 text-purple-600 rounded-xl">
                    <i class="fas fa-building text-lg"></i>
                </div>
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">High Scale</span>
            </div>
            <h3 class="text-xl font-bold text-gray-900">Enterprise</h3>
            <p class="text-3xl font-extrabold text-gray-900 mt-2">
                12 <span class="text-sm font-medium text-gray-400">Toko Aktif</span>
            </p>
        </div>
    </div>

    {{-- TABLE HEADER --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white rounded-xl p-4 shadow-sm border border-gray-100">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-amber-50 rounded-lg">
                <i class="fas fa-sliders-h text-amber-600"></i>
            </div>
            <h2 class="font-bold text-gray-900">Daftar Konfigurasi Paket</h2>
        </div>

        <button class="flex items-center gap-2 px-5 py-2.5 bg-gray-900 text-white rounded-xl text-sm font-bold hover:bg-gray-800 transition-all shadow">
            <i class="fas fa-plus text-xs"></i>
            Buat Paket Baru
        </button>
    </div>

    {{-- TABLE --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm display" id="stokInTable">
                <thead>
                    <tr>
                        <th>Nama Paket</th>
                        <th>Harga / Bulan</th>
                        <th>Limit Produk</th>
                        <th>Limit Transaksi</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    {{-- Starter --}}
                    <tr class="hover:bg-gray-50">
                        <td class="px-8 py-4">
                            <span class="font-bold text-gray-900 block">Starter Plan</span>
                            <span class="text-[10px] text-gray-400">Cocok untuk UMKM</span>
                        </td>
                        <td class="px-6 py-4 font-bold text-gray-700">
                            Rp 0 <span class="text-[10px] text-gray-400">(Free)</span>
                        </td>
                        <td class="px-6 py-4 text-gray-600">Max 50 Produk</td>
                        <td class="px-6 py-4 text-gray-600">100 / Bulan</td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-600 text-[10px] font-black uppercase">
                                Aktif
                            </span>
                        </td>
                        <td class="px-8 py-4 text-right">
                            <button class="p-2 text-gray-400 hover:text-amber-500">
                                <i class="fas fa-pen"></i>
                            </button>
                            <button class="p-2 text-gray-400 hover:text-rose-500">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>

                    {{-- Pro --}}
                    <tr class="hover:bg-amber-50/40">
                        <td class="px-8 py-4">
                            <span class="font-bold text-gray-900 block">Pro Plan</span>
                            <span class="text-[10px] text-amber-500 font-bold">Paling Banyak Dipilih</span>
                        </td>
                        <td class="px-6 py-4 font-bold text-gray-700">Rp 149.000</td>
                        <td class="px-6 py-4 font-bold text-gray-600">Unlimited</td>
                        <td class="px-6 py-4 text-gray-600">5.000 / Bulan</td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-600 text-[10px] font-black uppercase">
                                Aktif
                            </span>
                        </td>
                        <td class="px-8 py-4 text-right">
                            <button class="p-2 text-gray-400 hover:text-amber-500">
                                <i class="fas fa-pen"></i>
                            </button>
                            <button class="p-2 text-gray-400 hover:text-rose-500">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>

                    {{-- Enterprise --}}
                    <tr class="hover:bg-gray-50">
                        <td class="px-8 py-4">
                            <span class="font-bold text-gray-900 block">Enterprise</span>
                            <span class="text-[10px] text-gray-400">Custom & multi-cabang</span>
                        </td>
                        <td class="px-6 py-4 font-bold text-gray-700">Rp 499.000</td>
                        <td class="px-6 py-4 font-bold text-gray-600">Unlimited</td>
                        <td class="px-6 py-4 font-bold text-gray-600">Unlimited</td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-3 py-1 rounded-full bg-gray-100 text-gray-400 text-[10px] font-black uppercase">
                                Draft
                            </span>
                        </td>
                        <td class="px-8 py-4 text-right">
                            <button class="p-2 text-gray-400 hover:text-amber-500">
                                <i class="fas fa-pen"></i>
                            </button>
                            <button class="p-2 text-gray-400 hover:text-rose-500">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    

</div>
@endsection