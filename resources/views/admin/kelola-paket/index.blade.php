@extends('admin.layouts.app')

@section('header_title', 'Manajemen Paket')
@section('header_description', 'Atur harga dan fitur paket berlangganan StokIn')

@section('content')
<div class="space-y-6">

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-slate-50 text-slate-400 rounded-2xl">
                    <i data-lucide="package" class="w-6 h-6"></i>
                </div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Entry Level</span>
            </div>
            <h3 class="text-xl font-bold text-slate-800">Starter Plan</h3>
            <p class="text-3xl font-black text-slate-900 mt-2">42 <span class="text-sm font-medium text-slate-400 text-slate-500">Toko Aktif</span></p>
        </div>

        <div class="bg-white p-6 rounded-[2rem] border border-amber-100 shadow-sm relative overflow-hidden">
            <div class="absolute top-0 right-0 bg-amber-500 text-white text-[10px] font-black px-4 py-1 rounded-bl-xl uppercase tracking-tighter">Populer</div>
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-amber-50 text-amber-600 rounded-2xl">
                    <i data-lucide="zap" class="w-6 h-6"></i>
                </div>
                <span class="text-[10px] font-bold text-amber-500 uppercase tracking-widest">Growth</span>
            </div>
            <h3 class="text-xl font-bold text-slate-800">Pro Plan</h3>
            <p class="text-3xl font-black text-slate-900 mt-2">86 <span class="text-sm font-medium text-slate-400 text-slate-500">Toko Aktif</span></p>
        </div>

        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-purple-50 text-purple-600 rounded-2xl">
                    <i data-lucide="building" class="w-6 h-6"></i>
                </div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">High Scale</span>
            </div>
            <h3 class="text-xl font-bold text-slate-800">Enterprise</h3>
            <p class="text-3xl font-black text-slate-900 mt-2">12 <span class="text-sm font-medium text-slate-400 text-slate-500">Toko Aktif</span></p>
        </div>
    </div>

    <div class="space-y-4">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-4 rounded-3xl border border-slate-100 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-amber-50 rounded-xl">
                    <i data-lucide="settings-2" class="w-5 h-5 text-amber-600"></i>
                </div>
                <h2 class="font-bold text-slate-800">Daftar Konfigurasi Paket</h2>
            </div>
            <button class="flex items-center gap-2 px-5 py-2.5 bg-slate-900 text-white rounded-xl text-sm font-bold hover:bg-slate-800 transition-all shadow-lg shadow-slate-200">
                <i data-lucide="plus" class="w-4 h-4"></i>
                Buat Paket Baru
            </button>
        </div>

        <div class="bg-white border border-slate-100 rounded-[2rem] overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-50 text-slate-400 text-[11px] font-bold uppercase tracking-[0.1em]">
                            <th class="px-8 py-5">Nama Paket</th>
                            <th class="px-6 py-5">Harga (Bulan)</th>
                            <th class="px-6 py-5">Limit Produk</th>
                            <th class="px-6 py-5">Limit Transaksi</th>
                            <th class="px-6 py-5 text-center">Status</th>
                            <th class="px-8 py-5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-8 py-4">
                                <span class="font-bold text-slate-800 block">Starter Plan</span>
                                <span class="text-[10px] text-slate-400 font-medium">Cocok untuk toko UMKM kecil</span>
                            </td>
                            <td class="px-6 py-4 font-bold text-slate-700">Rp 0 <span class="text-[10px] text-slate-400">(Free)</span></td>
                            <td class="px-6 py-4 text-sm text-slate-600">Max 50 Produk</td>
                            <td class="px-6 py-4 text-sm text-slate-600">100 / Bulan</td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-full text-[10px] font-black uppercase">Aktif</span>
                            </td>
                            <td class="px-8 py-4 text-right">
                                <button class="p-2 text-slate-400 hover:text-amber-500 transition-colors"><i data-lucide="edit-3" class="w-4 h-4"></i></button>
                                <button class="p-2 text-slate-400 hover:text-rose-500 transition-colors"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                            </td>
                        </tr>
                        <tr class="hover:bg-slate-50/50 transition-colors bg-amber-50/10">
                            <td class="px-8 py-4">
                                <span class="font-bold text-slate-800 block">Pro Plan</span>
                                <span class="text-[10px] text-amber-500 font-bold">Paling Banyak Dipilih</span>
                            </td>
                            <td class="px-6 py-4 font-bold text-slate-700">Rp 149.000</td>
                            <td class="px-6 py-4 text-sm text-slate-600 font-bold">Unlimited</td>
                            <td class="px-6 py-4 text-sm text-slate-600">5.000 / Bulan</td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-full text-[10px] font-black uppercase">Aktif</span>
                            </td>
                            <td class="px-8 py-4 text-right">
                                <button class="p-2 text-slate-400 hover:text-amber-500 transition-colors"><i data-lucide="edit-3" class="w-4 h-4"></i></button>
                                <button class="p-2 text-slate-400 hover:text-rose-500 transition-colors"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                            </td>
                        </tr>
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-8 py-4">
                                <span class="font-bold text-slate-800 block">Enterprise</span>
                                <span class="text-[10px] text-slate-400 font-medium">Custom branding & multi-cabang</span>
                            </td>
                            <td class="px-6 py-4 font-bold text-slate-700">Rp 499.000</td>
                            <td class="px-6 py-4 text-sm text-slate-600 font-bold">Unlimited</td>
                            <td class="px-6 py-4 text-sm text-slate-600 font-bold">Unlimited</td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-3 py-1 bg-slate-100 text-slate-400 rounded-full text-[10px] font-black uppercase">Draft</span>
                            </td>
                            <td class="px-8 py-4 text-right">
                                <button class="p-2 text-slate-400 hover:text-amber-500 transition-colors"><i data-lucide="edit-3" class="w-4 h-4"></i></button>
                                <button class="p-2 text-slate-400 hover:text-rose-500 transition-colors"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="bg-amber-600 rounded-[2rem] p-8 text-white flex flex-col md:flex-row items-center justify-between gap-6 relative overflow-hidden shadow-xl shadow-amber-100">
        <div class="relative z-10 md:w-2/3">
            <h4 class="text-xl font-bold mb-2">Ingin menambah fitur baru?</h4>
            <p class="text-amber-100 text-sm leading-relaxed">Penambahan fitur seperti "Laporan AI" atau "Multi-Warehouse" dapat Anda atur melalui menu <span class="font-bold underline">Add-on Management</span> untuk menetapkan biaya tambahan diluar paket utama.</p>
        </div>
        <button class="relative z-10 px-6 py-3 bg-white text-amber-600 rounded-2xl font-bold text-sm hover:bg-amber-50 transition-all shadow-lg active:scale-95">
            Pelajari Add-on
        </button>
        <i data-lucide="help-circle" class="absolute -right-4 -bottom-4 w-32 h-32 text-amber-500/20 rotate-12"></i>
    </div>

</div>
@endsection