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

    {{-- Filter & Aksi --}}
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
        <div class="flex flex-col md:flex-row gap-4 flex-1">
            <div class="relative flex-1">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" placeholder="Cari invoice atau nama toko..."
                    class="w-full pl-10 pr-4 py-2.5 bg-gray-50 rounded-xl text-sm focus:ring-2 focus:ring-amber-500 outline-none">
            </div>

            <select class="px-4 py-2.5 bg-gray-50 rounded-xl text-sm font-semibold text-gray-600 focus:ring-2 focus:ring-amber-500 outline-none">
                <option value="">Semua Status</option>
                <option value="paid">Lunas</option>
                <option value="pending">Menunggu</option>
                <option value="overdue">Jatuh Tempo</option>
            </select>
        </div>

        <button class="flex items-center gap-2 px-4 py-2.5 bg-amber-500 text-white rounded-xl text-sm font-bold hover:bg-amber-600 transition-all">
            <i class="fas fa-plus-circle"></i>
            Buat Invoice
        </button>
    </div>

    {{-- Tabel Invoice --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr>
                        <th>Invoice</th>
                        <th>Nama Toko</th>
                        <th>Tanggal</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="hover:bg-gray-50/60">
                        <td class="px-6 py-4 font-bold text-gray-900">INV-00124</td>
                        <td class="px-6 py-4">Coffee Shop Senja</td>
                        <td class="px-6 py-4 text-gray-500">12 Des 2025</td>
                        <td class="px-6 py-4 font-semibold">Rp 299.000</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded-full bg-emerald-50 text-emerald-600 text-[10px] font-bold uppercase">
                                Lunas
                            </span>
                        </td>
                        <td class="px-8 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                <button class="p-2 text-gray-400 hover:text-amber-500">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="p-2 text-gray-400 hover:text-blue-500">
                                    <i class="fas fa-download"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <tr class="hover:bg-gray-50/60">
                        <td class="px-6 py-4 font-bold text-gray-900">INV-00125</td>
                        <td class="px-6 py-4">Laundry Wangi</td>
                        <td class="px-6 py-4 text-gray-500">14 Des 2025</td>
                        <td class="px-6 py-4 font-semibold">Rp 99.000</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded-full bg-amber-50 text-amber-600 text-[10px] font-bold uppercase">
                                Menunggu
                            </span>
                        </td>
                        <td class="px-8 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                <button class="p-2 text-gray-400 hover:text-amber-500">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="p-2 text-gray-400 hover:text-rose-500">
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <tr class="hover:bg-gray-50/60">
                        <td class="px-6 py-4 font-bold text-gray-900">INV-00126</td>
                        <td class="px-6 py-4">Toko Sembako Makmur</td>
                        <td class="px-6 py-4 text-gray-500">15 Des 2025</td>
                        <td class="px-6 py-4 font-semibold">Rp 1.250.000</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded-full bg-rose-50 text-rose-600 text-[10px] font-bold uppercase">
                                Jatuh Tempo
                            </span>
                        </td>
                        <td class="px-8 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                <button class="p-2 text-gray-400 hover:text-amber-500"><i class="fas fa-eye"></i></button>
                                <button class="p-2 text-gray-400 hover:text-rose-500"><i class="fas fa-paper-plane"></i></button>
                            </div>
                        </td>
                    </tr>

                    <tr class="hover:bg-gray-50/60">
                        <td class="px-6 py-4 font-bold text-gray-900">INV-00127</td>
                        <td class="px-6 py-4">Bakery Manis</td>
                        <td class="px-6 py-4 text-gray-500">16 Des 2025</td>
                        <td class="px-6 py-4 font-semibold">Rp 450.000</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded-full bg-emerald-50 text-emerald-600 text-[10px] font-bold uppercase">
                                Lunas
                            </span>
                        </td>
                        <td class="px-8 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                <button class="p-2 text-gray-400 hover:text-amber-500"><i class="fas fa-eye"></i></button>
                                <button class="p-2 text-gray-400 hover:text-blue-500"><i class="fas fa-download"></i></button>
                            </div>
                        </td>
                    </tr>

                    <tr class="hover:bg-gray-50/60">
                        <td class="px-6 py-4 font-bold text-gray-900">INV-00128</td>
                        <td class="px-6 py-4">Apotek Sehat</td>
                        <td class="px-6 py-4 text-gray-500">17 Des 2025</td>
                        <td class="px-6 py-4 font-semibold">Rp 320.000</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded-full bg-amber-50 text-amber-600 text-[10px] font-bold uppercase">
                                Menunggu
                            </span>
                        </td>
                        <td class="px-8 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                <button class="p-2 text-gray-400 hover:text-amber-500"><i class="fas fa-eye"></i></button>
                                <button class="p-2 text-gray-400 hover:text-rose-500"><i class="fas fa-paper-plane"></i></button>
                            </div>
                        </td>
                    </tr>

                    <tr class="hover:bg-gray-50/60">
                        <td class="px-6 py-4 font-bold text-gray-900">INV-00129</td>
                        <td class="px-6 py-4">Toko Elektronik Jaya</td>
                        <td class="px-6 py-4 text-gray-500">18 Des 2025</td>
                        <td class="px-6 py-4 font-semibold">Rp 2.150.000</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded-full bg-emerald-50 text-emerald-600 text-[10px] font-bold uppercase">
                                Lunas
                            </span>
                        </td>
                        <td class="px-8 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                <button class="p-2 text-gray-400 hover:text-amber-500"><i class="fas fa-eye"></i></button>
                                <button class="p-2 text-gray-400 hover:text-blue-500"><i class="fas fa-download"></i></button>
                            </div>
                        </td>
                    </tr>

                    <tr class="hover:bg-gray-50/60">
                        <td class="px-6 py-4 font-bold text-gray-900">INV-00130</td>
                        <td class="px-6 py-4">Percetakan Sinar</td>
                        <td class="px-6 py-4 text-gray-500">19 Des 2025</td>
                        <td class="px-6 py-4 font-semibold">Rp 680.000</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded-full bg-amber-50 text-amber-600 text-[10px] font-bold uppercase">
                                Menunggu
                            </span>
                        </td>
                        <td class="px-8 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                <button class="p-2 text-gray-400 hover:text-amber-500"><i class="fas fa-eye"></i></button>
                                <button class="p-2 text-gray-400 hover:text-rose-500"><i class="fas fa-paper-plane"></i></button>
                            </div>
                        </td>
                    </tr>

                    <tr class="hover:bg-gray-50/60">
                        <td class="px-6 py-4 font-bold text-gray-900">INV-00131</td>
                        <td class="px-6 py-4">Restoran Nusantara</td>
                        <td class="px-6 py-4 text-gray-500">20 Des 2025</td>
                        <td class="px-6 py-4 font-semibold">Rp 890.000</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded-full bg-rose-50 text-rose-600 text-[10px] font-bold uppercase">
                                Jatuh Tempo
                            </span>
                        </td>
                        <td class="px-8 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                <button class="p-2 text-gray-400 hover:text-amber-500"><i class="fas fa-eye"></i></button>
                                <button class="p-2 text-gray-400 hover:text-rose-500"><i class="fas fa-paper-plane"></i></button>
                            </div>
                        </td>
                    </tr>

                    <tr class="hover:bg-gray-50/60">
                        <td class="px-6 py-4 font-bold text-gray-900">INV-00132</td>
                        <td class="px-6 py-4">Toko Bangunan Sentosa</td>
                        <td class="px-6 py-4 text-gray-500">21 Des 2025</td>
                        <td class="px-6 py-4 font-semibold">Rp 3.200.000</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded-full bg-emerald-50 text-emerald-600 text-[10px] font-bold uppercase">
                                Lunas
                            </span>
                        </td>
                        <td class="px-8 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                <button class="p-2 text-gray-400 hover:text-amber-500"><i class="fas fa-eye"></i></button>
                                <button class="p-2 text-gray-400 hover:text-blue-500"><i class="fas fa-download"></i></button>
                            </div>
                        </td>
                    </tr>

                    <tr class="hover:bg-gray-50/60">
                        <td class="px-6 py-4 font-bold text-gray-900">INV-00133</td>
                        <td class="px-6 py-4">Salon Cantika</td>
                        <td class="px-6 py-4 text-gray-500">22 Des 2025</td>
                        <td class="px-6 py-4 font-semibold">Rp 210.000</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded-full bg-amber-50 text-amber-600 text-[10px] font-bold uppercase">
                                Menunggu
                            </span>
                        </td>
                        <td class="px-8 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                <button class="p-2 text-gray-400 hover:text-amber-500"><i class="fas fa-eye"></i></button>
                                <button class="p-2 text-gray-400 hover:text-rose-500"><i class="fas fa-paper-plane"></i></button>
                            </div>
                        </td>
                    </tr>

                    <tr class="hover:bg-gray-50/60">
                        <td class="px-6 py-4 font-bold text-gray-900">INV-00134</td>
                        <td class="px-6 py-4">Toko Buku Ilmu</td>
                        <td class="px-6 py-4 text-gray-500">23 Des 2025</td>
                        <td class="px-6 py-4 font-semibold">Rp 560.000</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded-full bg-emerald-50 text-emerald-600 text-[10px] font-bold uppercase">
                                Lunas
                            </span>
                        </td>
                        <td class="px-8 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                <button class="p-2 text-gray-400 hover:text-amber-500"><i class="fas fa-eye"></i></button>
                                <button class="p-2 text-gray-400 hover:text-blue-500"><i class="fas fa-download"></i></button>
                            </div>
                        </td>
                    </tr>

                    <tr class="hover:bg-gray-50/60">
                        <td class="px-6 py-4 font-bold text-gray-900">INV-00135</td>
                        <td class="px-6 py-4">Klinik Pratama</td>
                        <td class="px-6 py-4 text-gray-500">24 Des 2025</td>
                        <td class="px-6 py-4 font-semibold">Rp 740.000</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded-full bg-rose-50 text-rose-600 text-[10px] font-bold uppercase">
                                Jatuh Tempo
                            </span>
                        </td>
                        <td class="px-8 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                <button class="p-2 text-gray-400 hover:text-amber-500"><i class="fas fa-eye"></i></button>
                                <button class="p-2 text-gray-400 hover:text-rose-500"><i class="fas fa-paper-plane"></i></button>
                            </div>
                        </td>
                    </tr>

                    <tr class="hover:bg-gray-50/60">
                        <td class="px-6 py-4 font-bold text-gray-900">INV-00136</td>
                        <td class="px-6 py-4">Studio Foto Kenangan</td>
                        <td class="px-6 py-4 text-gray-500">25 Des 2025</td>
                        <td class="px-6 py-4 font-semibold">Rp 380.000</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded-full bg-amber-50 text-amber-600 text-[10px] font-bold uppercase">
                                Menunggu
                            </span>
                        </td>
                        <td class="px-8 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                <button class="p-2 text-gray-400 hover:text-amber-500"><i class="fas fa-eye"></i></button>
                                <button class="p-2 text-gray-400 hover:text-rose-500"><i class="fas fa-paper-plane"></i></button>
                            </div>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection