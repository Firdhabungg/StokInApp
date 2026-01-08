@extends('admin.layouts.app')

@section('title', 'Tagihan & Faktur')
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
                    <p class="text-xl font-bold text-gray-900">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
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
                    <p class="text-xl font-bold text-gray-900">{{ $menungguPembayaran }} Invoice</p>
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
                    <p class="text-xl font-bold text-gray-900">{{ $jatuhTempo }} Invoice</p>
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
                    <input type="text" id="searchInput" placeholder="Cari invoice / toko..."
                        class="pl-10 pr-4 py-2 text-sm bg-gray-50 border border-gray-200 rounded-lg focus:ring-amber-500 focus:border-amber-500">
                </div>

                <select id="statusFilter"
                    class="px-4 py-2 text-sm bg-gray-50 border border-gray-200 rounded-lg font-semibold text-gray-600 focus:ring-amber-500 focus:border-amber-500">
                    <option value="">Semua Status</option>
                    <option value="Lunas">Lunas</option>
                    <option value="Menunggu">Menunggu</option>
                    <option value="Jatuh Tempo">Jatuh Tempo</option>
                </select>
            </div>
        </div>

        {{-- Tabel Invoice --}}
        <div class="rounded-xl border-gray-100 shadow-sm">
            <div class="overflow-x-auto">
                <table id="invoiceTable" class="w-full text-left border-collapse">
                    <thead class="bg-gray-50 text-gray-700 border-b border-gray-200">
                        <tr class="border-b border-gray-400 text-gray-600 text-[12px] font-bold uppercase tracking-widest">
                            <th class="px-4 py-4 text-center">Invoice</th>
                            <th class="px-4 py-4">Toko</th>
                            <th class="px-4 py-4">Paket</th>
                            <th class="px-4 py-4">Tanggal</th>
                            <th class="px-4 py-4">Total</th>
                            <th class="px-4 py-4 text-center">Status</th>
                            <th class="px-4 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                        @forelse ($payments as $payment)
                            <tr class="hover:bg-gray-50/60 transition-colors">
                                <td class="px-4 py-4 font-semibold text-gray-900 text-center">
                                    {{ $payment->invoice_number }}
                                </td>

                                <td class="px-4 py-4">
                                    <p class="font-semibold text-gray-800 text-sm">
                                        {{ $payment->subscription?->toko?->name ?? '-' }}
                                    </p>
                                    <p class="text-[10px] text-gray-400">
                                        {{ $payment->subscription?->toko?->email ?? '' }}
                                    </p>
                                </td>

                                <td class="px-4 py-4 text-gray-600 text-sm">
                                    {{ $payment->subscription?->plan?->name ?? '-' }}
                                </td>

                                <td class="px-4 py-4 text-gray-500 text-sm">
                                    {{ $payment->created_at->translatedFormat('d M Y') }}
                                </td>

                                <td class="px-4 py-4 font-semibold text-gray-800">
                                    {{ $payment->formatted_amount }}
                                </td>

                                <td class="px-4 py-4 text-center">
                                    <span class="px-2 py-0.5 rounded-full bg-{{ $payment->status_color }}-50 text-{{ $payment->status_color }}-600 text-[11px] font-bold">
                                        {{ $payment->status_label }}
                                    </span>
                                </td>

                                <td class="px-4 py-4 text-center">
                                    <div class="flex justify-center gap-2">
                                        <a href="{{ route('admin.keuangan.show', $payment) }}" 
                                           class="p-2 text-gray-400 hover:text-amber-500 transition" title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-12 text-center">
                                    <div class="flex flex-col items-center gap-2">
                                        <i class="fas fa-file-invoice text-4xl text-gray-300"></i>
                                        <p class="text-gray-500 font-medium">Belum ada data invoice</p>
                                        <p class="text-gray-400 text-sm">Invoice akan muncul setelah ada transaksi pembayaran</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const table = document.getElementById('invoiceTable');
    const rows = table.querySelectorAll('tbody tr');

    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();
        const statusTerm = statusFilter.value;

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            const status = row.querySelector('td:nth-child(6)')?.textContent.trim() || '';
            
            const matchSearch = text.includes(searchTerm);
            const matchStatus = !statusTerm || status === statusTerm;

            row.style.display = matchSearch && matchStatus ? '' : 'none';
        });
    }

    searchInput.addEventListener('input', filterTable);
    statusFilter.addEventListener('change', filterTable);
});
</script>
@endpush
@endsection