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

    {{-- TABLE CONTAINER --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">

    {{-- JUDUL TABEL --}}
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-semibold text-gray-900">
            Daftar Konfigurasi Paket
        </h2>

        <button
            class="bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
            <i class="fas fa-plus mr-2"></i>Buat Paket Baru
        </button>
    </div>

    <div class="overflow-x-auto">
        <table id="stokInTable" class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-700 border-b border-gray-200">
            <tr>
                <th class="px-6 py-4 text-left text-[12px] font-bold uppercase tracking-widest">
                    Nama Paket
                </th>
                <th class="px-6 py-4 text-left text-[12px] font-bold uppercase tracking-widest">
                    Harga / Bulan
                </th>
                <th class="px-6 py-4 text-left text-[12px] font-bold uppercase tracking-widest">
                    Limit Produk
                </th>
                <th class="px-6 py-4 text-left text-[12px] font-bold uppercase tracking-widest">
                    Limit Transaksi
                </th>
                <th class="px-6 py-4 text-center text-[12px] font-bold uppercase tracking-widest">
                    Status
                </th>
                <th class="px-6 py-4 text-right text-[12px] font-bold uppercase tracking-widest">
                    Aksi
                </th>
            </tr>
        </thead>


            <tbody class="divide-y divide-gray-100">

            <style>
                    .dataTables_filter { display: none; }

                    .dataTables_wrapper .dataTables_info {
                        padding-top: 20px;
                        color: #718096;
                        font-size: 14px;
                        font-style: italic;
                    }

                    .dataTables_wrapper .dataTables_paginate .paginate_button {
                        padding: 0.4rem 0.75rem;
                        margin-left: 0.25rem;
                        border-radius: 0.75rem;
                        border: 1px solid #e5e7eb;
                        background: white;
                        font-size: 0.75rem;
                        color: #6b7280 !important;
                    }

                    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
                        background: #f59e0b !important;
                        color: white !important;
                        border-color: #f59e0b;
                    }
                    </style>

                {{-- Starter --}}
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <p class="font-semibold text-gray-900">Starter Plan</p>
                        <p class="text-xs text-gray-400">Cocok untuk UMKM</p>
                    </td>
                    <td class="px-6 py-4 font-semibold text-gray-700">
                        Rp 0 <span class="text-xs text-gray-400">(Free)</span>
                    </td>
                    <td class="px-6 py-4 text-gray-600">Max 50 Produk</td>
                    <td class="px-6 py-4 text-gray-600">14 / Hari</td>
                    <td class="px-6 py-4 text-center">
                        <span class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-600 text-xs font-semibold">
                            Aktif
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right space-x-2">
                        <button class="text-gray-400 hover:text-amber-600">
                            <i class="fas fa-pen"></i>
                        </button>
                        <button class="text-gray-400 hover:text-rose-500">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>


                {{-- Pro --}}
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <p class="font-semibold text-gray-900">Pro Plan</p>
                        <p class="text-xs text-gray-400">Paling Banyak Dipilih</p>
                    
                    <td class="px-6 py-4 font-semibold text-gray-700">Rp 149.000</td>
                    <td class="px-6 py-4 font-semibold text-gray-600">Unlimited</td>
                    <td class="px-6 py-4 text-gray-600">5.000 / Bulan</td>
                    <td class="px-6 py-4 text-center">
                        <span class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-600 text-xs font-semibold">
                            Aktif
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right space-x-2">
                        <button class="text-gray-400 hover:text-amber-600">
                            <i class="fas fa-pen"></i>
                        </button>
                        <button class="text-gray-400 hover:text-rose-500">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>

                {{-- Enterprise --}}
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <p class="font-bold text-gray-900">Enterprise</p>
                        <p class="text-[11px] text-gray-400">Custom & multi-cabang</p>
                    </td>
                    <td class="px-6 py-4 font-semibold text-gray-700">Rp 499.000</td>
                    <td class="px-6 py-4 font-semibold text-gray-600">Unlimited</td>
                    <td class="px-6 py-4 font-semibold text-gray-600">Unlimited</td>
                    <td class="px-6 py-4 text-center">
                        <span
                            class="px-3 py-1 rounded-full bg-gray-100 text-gray-500 text-[11px] font-bold uppercase">
                            Draft
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right space-x-2">
                        <button class="text-gray-400 hover:text-amber-600">
                            <i class="fas fa-pen"></i>
                        </button>
                        <button class="text-gray-400 hover:text-rose-500">
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