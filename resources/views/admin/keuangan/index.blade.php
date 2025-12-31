@extends('admin.layouts.app')

@section('header_title', 'Tagihan & Faktur')
@section('header_description', 'Kelola tagihan, pembayaran, dan riwayat invoice pelanggan')

@section('content')
<div class="space-y-6">

    {{-- Ringkasan Billing --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <i class="fas fa-money-bill-wave text-lg"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-semibold uppercase">Total Pendapatan</p>
                    <p class="text-xl font-bold text-gray-900">Rp 12.450.000</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                    <i class="fas fa-clock text-lg"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-semibold uppercase">Menunggu Pembayaran</p>
                    <p class="text-xl font-bold text-gray-900">8 Invoice</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center">
                    <i class="fas fa-file-invoice-dollar text-lg"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-semibold uppercase">Invoice Jatuh Tempo</p>
                    <p class="text-xl font-bold text-gray-900">3 Invoice</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter + Table Invoice --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">

    {{-- HEADER + FILTER --}}
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-5">
        <div>
            <h2 class="text-lg font-bold text-gray-900">Daftar Invoice</h2>
            <p class="text-xs text-gray-500">Kelola semua tagihan pelanggan</p>
        </div>

        <div class="flex flex-wrap gap-3">
            <div class="relative">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input
                    type="text"
                    placeholder="Cari invoice / toko..."
                    class="pl-10 pr-4 py-2 text-sm bg-gray-50 border border-gray-200 rounded-lg focus:ring-amber-500 focus:border-amber-500"
                >
            </div>

            <select
                class="px-4 py-2 text-sm bg-gray-50 border border-gray-200 rounded-lg font-semibold text-gray-600 focus:ring-amber-500 focus:border-amber-500">
                <option value="">Semua Status</option>
                <option value="paid">Lunas</option>
                <option value="pending">Menunggu</option>
                <option value="overdue">Jatuh Tempo</option>
            </select>

            <button
                class="flex items-center gap-2 px-4 py-2 bg-amber-500 text-white rounded-lg text-sm font-semibold hover:bg-amber-600 transition">
                <i class="fas fa-plus"></i>
                Buat Invoice
            </button>
        </div>
    </div>


    {{-- Tabel Invoice --}}
    <div class="rounded-xl border-gray-100 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50 text-gray-700 border-b border-gray-200">
                <tr class="border-b border-gray-400 text-gray-600 text-[12px] font-bold uppercase tracking-widest">
                    <th class="px-4 py-4 text-center">Invoice</th>
                    <th class="px-4 py-4">Toko</th>
                    <th class="px-4 py-4">Tanggal</th>
                    <th class="px-4 py-4">Total</th>
                    <th class="px-4 py-4 text-center">Status</th>
                    <th class="px-4 py-4 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100">
                <tr class="hover:bg-gray-50/60 transition-colors">
                    <td class="px-4 py-4 font-semibold text-gray-900 text-center">
                        INV-0015
                    </td>

                    <td class="px-4 py-4">
                        <p class="font-semibold text-gray-800 text-sm">Coffee Shop Senja</p>
                    </td>

                    <td class="px-4 py-4 text-gray-500 text-sm">
                        12 Des 2025
                    </td>

                    <td class="px-4 py-4 font-semibold text-gray-800">
                        Rp 309.000
                    </td>

                    <td class="px-4 py-4 text-center">
                        <span class="px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-600 text-[11px] font-bold">
                            Lunas
                        </span>
                    </td>

                    <td class="px-4 py-4 text-center">
                        <div class="flex justify-center gap-2">
                            <button class="p-2 text-gray-400 hover:text-amber-500">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="p-2 text-gray-400 hover:text-blue-500">
                                <i class="fas fa-download"></i>
                            </button>
                        </div>
                    </td>
                </tr>

                <tr class="hover:bg-gray-50/60 transition-colors">
                    <td class="px-4 py-4 text-center font-semibold text-gray-900">
                        INV-001
                    </td>
                    <td class="px-4 py-4">
                        <p class="font-semibold text-gray-800 text-sm">Toko Senja Coffee</p>
                    </td>
                    <td class="px-4 py-4 text-sm text-gray-500">
                        12 Des 2025
                    </td>
                    <td class="px-4 py-4 font-semibold text-gray-800">
                        Rp 299.000
                    </td>
                    <td class="px-4 py-4 text-center">
                        <span class="px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-600 text-[11px] font-bold">
                            Lunas
                        </span>
                    </td>
                    <td class="px-4 py-4 text-center">
                        <div class="flex justify-center gap-2">
                            <button class="p-2 text-gray-400 hover:text-amber-500 transition">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="p-2 text-gray-400 hover:text-blue-500 transition">
                                <i class="fas fa-download"></i>
                            </button>
                        </div>
                    </td>
                </tr>

                <tr class="hover:bg-gray-50/60 transition-colors">
                    <td class="px-4 py-4 text-center font-semibold text-gray-900">
                        INV-002
                    </td>
                    <td class="px-4 py-4">
                        <p class="font-semibold text-gray-800 text-sm">Toko Pagi Cerah</p>
                    </td>
                    <td class="px-4 py-4 text-sm text-gray-500">
                        10 Des 2025
                    </td>
                    <td class="px-4 py-4 font-semibold text-gray-800">
                        Rp 149.000
                    </td>
                    <td class="px-4 py-4 text-center">
                        <span class="px-2 py-0.5 rounded-full bg-amber-50 text-amber-600 text-[11px] font-bold">
                            Menunggu
                        </span>
                    </td>
                    <td class="px-4 py-4 text-center">
                        <div class="flex justify-center gap-2">
                            <button class="p-2 text-gray-400 hover:text-amber-500 transition">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="p-2 text-gray-400 hover:text-blue-500 transition">
                                <i class="fas fa-download"></i>
                            </button>
                        </div>
                    </td>
                </tr>

                <tr class="hover:bg-gray-50/60 transition-colors">
                    <td class="px-4 py-4 text-center font-semibold text-gray-900">
                        INV-003
                    </td>
                    <td class="px-4 py-4">
                        <p class="font-semibold text-gray-800 text-sm">Toko Langit Biru</p>
                    </td>
                    <td class="px-4 py-4 text-sm text-gray-500">
                        05 Des 2025
                    </td>
                    <td class="px-4 py-4 font-semibold text-gray-800">
                        Rp 99.000
                    </td>
                    <td class="px-4 py-4 text-center">
                        <span class="px-2 py-0.5 rounded-full bg-rose-50 text-rose-600 text-[11px] font-bold">
                            Jatuh Tempo
                        </span>
                    </td>
                    <td class="px-4 py-4 text-center">
                        <div class="flex justify-center gap-2">
                            <button class="p-2 text-gray-400 hover:text-amber-500 transition">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="p-2 text-gray-400 hover:text-blue-500 transition">
                                <i class="fas fa-download"></i>
                            </button>
                        </div>
                    </td>
                </tr>

               <tr class="hover:bg-gray-50/60 transition-colors">
                    <td class="px-4 py-4 text-center font-semibold text-gray-900">
                        INV-0010
                    </td>
                    <td class="px-4 py-4">
                        <p class="font-semibold text-gray-800 text-sm">Toko Buku Ilmu</p>
                    </td>
                    <td class="px-4 py-4 text-sm text-gray-500">
                        15 Des 2025
                    </td>
                    <td class="px-4 py-4 font-semibold text-gray-800">
                        Rp 100.000
                    </td>
                    <td class="px-4 py-4 text-center">
                        <span class="px-2 py-0.5 rounded-full bg-rose-50 text-rose-600 text-[11px] font-bold">
                            Jatuh Tempo
                        </span>
                    </td>
                    <td class="px-4 py-4 text-center">
                        <div class="flex justify-center gap-2">
                            <button class="p-2 text-gray-400 hover:text-amber-500 transition">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="p-2 text-gray-400 hover:text-blue-500 transition">
                                <i class="fas fa-download"></i>
                            </button>
                        </div>
                    </td>
                </tr>

                <tr class="hover:bg-gray-50/60 transition-colors">
                    <td class="px-4 py-4 text-center font-semibold text-gray-900">
                        INV-002
                    </td>
                    <td class="px-4 py-4">
                        <p class="font-semibold text-gray-800 text-sm">Toko Pagi Cerah</p>
                    </td>
                    <td class="px-4 py-4 text-sm text-gray-500">
                        10 Des 2025
                    </td>
                    <td class="px-4 py-4 font-semibold text-gray-800">
                        Rp 149.000
                    </td>
                    <td class="px-4 py-4 text-center">
                        <span class="px-2 py-0.5 rounded-full bg-amber-50 text-amber-600 text-[11px] font-bold">
                            Menunggu
                        </span>
                    </td>
                    <td class="px-4 py-4 text-center">
                        <div class="flex justify-center gap-2">
                            <button class="p-2 text-gray-400 hover:text-amber-500 transition">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="p-2 text-gray-400 hover:text-blue-500 transition">
                                <i class="fas fa-download"></i>
                            </button>
                        </div>
                    </td>
                </tr>

                <tr class="hover:bg-gray-50/60 transition-colors">
                    <td class="px-4 py-4 text-center font-semibold text-gray-900">
                        INV-005
                    </td>
                    <td class="px-4 py-4">
                        <p class="font-semibold text-gray-800 text-sm">Mart ADB</p>
                    </td>
                    <td class="px-4 py-4 text-sm text-gray-500">
                        01 Des 2025
                    </td>
                    <td class="px-4 py-4 font-semibold text-gray-800">
                        Rp 222.000
                    </td>
                    <td class="px-4 py-4 text-center">
                        <span class="px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-600 text-[11px] font-bold">
                            Lunas
                        </span>
                    </td>
                    <td class="px-4 py-4 text-center">
                        <div class="flex justify-center gap-2">
                            <button class="p-2 text-gray-400 hover:text-amber-500 transition">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="p-2 text-gray-400 hover:text-blue-500 transition">
                                <i class="fas fa-download"></i>
                            </button>
                        </div>
                    </td>
                </tr>

               <tr class="hover:bg-gray-50/60 transition-colors">
                    <td class="px-4 py-4 text-center font-semibold text-gray-900">
                        INV-0033
                    </td>
                    <td class="px-4 py-4">
                        <p class="font-semibold text-gray-800 text-sm">SRC Bu Ratih</p>
                    </td>
                    <td class="px-4 py-4 text-sm text-gray-500">
                        29 Des 2025
                    </td>
                    <td class="px-4 py-4 font-semibold text-gray-800">
                        Rp 1.000.000
                    </td>
                    <td class="px-4 py-4 text-center">
                        <span class="px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-600 text-[11px] font-bold">
                            Lunas
                        </span>
                    </td>
                    <td class="px-4 py-4 text-center">
                        <div class="flex justify-center gap-2">
                            <button class="p-2 text-gray-400 hover:text-amber-500 transition">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="p-2 text-gray-400 hover:text-blue-500 transition">
                                <i class="fas fa-download"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            
                <tr class="hover:bg-gray-50/60 transition-colors">
                    <td class="px-4 py-4 text-center font-semibold text-gray-900">
                        INV-0020
                    </td>
                    <td class="px-4 py-4">
                        <p class="font-semibold text-gray-800 text-sm">Toko Siang Cerah</p>
                    </td>
                    <td class="px-4 py-4 text-sm text-gray-500">
                        11 Des 2025
                    </td>
                    <td class="px-4 py-4 font-semibold text-gray-800">
                        Rp 199.000
                    </td>
                    <td class="px-4 py-4 text-center">
                        <span class="px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-600 text-[11px] font-bold">
                            Lunas
                        </span>
                    </td>
                    <td class="px-4 py-4 text-center">
                        <div class="flex justify-center gap-2">
                            <button class="p-2 text-gray-400 hover:text-amber-500 transition">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="p-2 text-gray-400 hover:text-blue-500 transition">
                                <i class="fas fa-download"></i>
                            </button>
                        </div>
                    </td>
                </tr>

                <tr class="hover:bg-gray-50/60 transition-colors">
                    <td class="px-4 py-4 text-center font-semibold text-gray-900">
                        INV-012
                    </td>
                    <td class="px-4 py-4">
                        <p class="font-semibold text-gray-800 text-sm">Toko Makmur Jaya</p>
                    </td>
                    <td class="px-4 py-4 text-sm text-gray-500">
                        05 Nov 2025
                    </td>
                    <td class="px-4 py-4 font-semibold text-gray-800">
                        Rp 992.000
                    </td>
                    <td class="px-4 py-4 text-center">
                        <span class="px-2 py-0.5 rounded-full bg-amber-50 text-amber-600 text-[11px] font-bold">
                            Menunggu
                        </span>
                    </td>
                    <td class="px-4 py-4 text-center">
                        <div class="flex justify-center gap-2">
                            <button class="p-2 text-gray-400 hover:text-amber-500 transition">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="p-2 text-gray-400 hover:text-blue-500 transition">
                                <i class="fas fa-download"></i>
                            </button>
                        </div>
                    </td>
                </tr>

               <tr class="hover:bg-gray-50/60 transition-colors">
                    <td class="px-4 py-4 text-center font-semibold text-gray-900">
                        INV-0011
                    </td>
                    <td class="px-4 py-4">
                        <p class="font-semibold text-gray-800 text-sm">Toko Pastik Emma</p>
                    </td>
                    <td class="px-4 py-4 text-sm text-gray-500">
                        11 Des 2025
                    </td>
                    <td class="px-4 py-4 font-semibold text-gray-800">
                        Rp 110.000
                    </td>
                    <td class="px-4 py-4 text-center">
                        <span class="px-2 py-0.5 rounded-full bg-rose-50 text-rose-600 text-[11px] font-bold">
                            Jatuh Tempo
                        </span>
                    </td>
                    <td class="px-4 py-4 text-center">
                        <div class="flex justify-center gap-2">
                            <button class="p-2 text-gray-400 hover:text-amber-500 transition">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="p-2 text-gray-400 hover:text-blue-500 transition">
                                <i class="fas fa-download"></i>
                            </button>
                        </div>
                    </td>
                </tr>

                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection