@extends('admin.layouts.app')

@section('title', 'Detail Invoice')
@section('header_title', 'Detail Invoice')
@section('header_description', 'Informasi lengkap tentang invoice pembayaran')

@section('content')
<div class="space-y-6">
    <div>
        <a href="{{ route('admin.keuangan.index') }}" 
           class="inline-flex items-center gap-2 text-gray-600 hover:text-amber-600 transition-colors">
            <i class="fas fa-arrow-left"></i>
            <span class="font-medium">Kembali ke Daftar Invoice</span>
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Invoice Info --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Invoice Header --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                <div class="flex items-start justify-between mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">{{ $payment->invoice_number }}</h2>
                        <p class="text-sm text-gray-500 mt-1">
                            Dibuat pada {{ $payment->created_at->translatedFormat('d F Y, H:i') }}
                        </p>
                    </div>
                    <span class="px-3 py-1.5 rounded-full bg-{{ $payment->status_color }}-50 text-{{ $payment->status_color }}-600 text-sm font-bold">
                        {{ $payment->status_label }}
                    </span>
                </div>

                <div class="border-t border-gray-100 pt-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Toko Info --}}
                        <div>
                            <p class="text-xs text-gray-500 font-semibold uppercase mb-2">Informasi Toko</p>
                            <p class="font-bold text-gray-900">{{ $payment->subscription?->toko?->name ?? '-' }}</p>
                            <p class="text-sm text-gray-600">{{ $payment->subscription?->toko?->email ?? '-' }}</p>
                            <p class="text-sm text-gray-600">{{ $payment->subscription?->toko?->phone ?? '-' }}</p>
                        </div>

                        {{-- Payment Info --}}
                        <div>
                            <p class="text-xs text-gray-500 font-semibold uppercase mb-2">Informasi Pembayaran</p>
                            <p class="text-sm text-gray-600">
                                <span class="text-gray-500">Order ID:</span> 
                                <span class="font-medium text-gray-900">{{ $payment->order_id }}</span>
                            </p>
                            @if($payment->paid_at)
                            <p class="text-sm text-gray-600">
                                <span class="text-gray-500">Dibayar pada:</span> 
                                <span class="font-medium text-gray-900">{{ $payment->paid_at->translatedFormat('d F Y, H:i') }}</span>
                            </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Subscription Details --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Detail Langganan</h3>
                
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr class="text-xs text-gray-500 font-bold uppercase">
                                <th class="text-left px-4 py-3">Paket</th>
                                <th class="text-left px-4 py-3">Durasi</th>
                                <th class="text-left px-4 py-3">Periode</th>
                                <th class="text-right px-4 py-3">Harga</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-t border-gray-100">
                                <td class="px-4 py-4">
                                    <p class="font-semibold text-gray-900">{{ $payment->subscription?->plan?->name ?? '-' }}</p>
                                </td>
                                <td class="px-4 py-4 text-gray-600">
                                    {{ $payment->subscription?->plan?->duration_days ?? '-' }} hari
                                </td>
                                <td class="px-4 py-4 text-gray-600">
                                    @if($payment->subscription)
                                        {{ $payment->subscription->starts_at?->format('d/m/Y') }} - 
                                        {{ $payment->subscription->expires_at?->format('d/m/Y') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-4 py-4 text-right font-bold text-gray-900">
                                    {{ $payment->formatted_amount }}
                                </td>
                            </tr>
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <td colspan="3" class="px-4 py-4 text-right font-bold text-gray-700">Total</td>
                                <td class="px-4 py-4 text-right font-bold text-xl text-amber-600">
                                    {{ $payment->formatted_amount }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Status Card --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                <h3 class="text-sm font-bold text-gray-700 uppercase mb-4">Status Pembayaran</h3>
                
                <div class="flex items-center gap-3 p-4 rounded-lg bg-{{ $payment->status_color }}-50">
                    @if($payment->status === 'success')
                        <div class="w-10 h-10 rounded-full bg-emerald-500 text-white flex items-center justify-center">
                            <i class="fas fa-check"></i>
                        </div>
                        <div>
                            <p class="font-bold text-emerald-700">Pembayaran Berhasil</p>
                            <p class="text-sm text-emerald-600">Langganan sudah aktif</p>
                        </div>
                    @elseif($payment->status === 'pending')
                        <div class="w-10 h-10 rounded-full bg-{{ $payment->isOverdue() ? 'rose' : 'amber' }}-500 text-white flex items-center justify-center">
                            <i class="fas fa-{{ $payment->isOverdue() ? 'exclamation' : 'clock' }}"></i>
                        </div>
                        <div>
                            <p class="font-bold text-{{ $payment->isOverdue() ? 'rose' : 'amber' }}-700">
                                {{ $payment->isOverdue() ? 'Jatuh Tempo' : 'Menunggu Pembayaran' }}
                            </p>
                            <p class="text-sm text-{{ $payment->isOverdue() ? 'rose' : 'amber' }}-600">
                                {{ $payment->isOverdue() ? 'Sudah melewati batas waktu' : 'Belum dibayar' }}
                            </p>
                        </div>
                    @else
                        <div class="w-10 h-10 rounded-full bg-gray-500 text-white flex items-center justify-center">
                            <i class="fas fa-times"></i>
                        </div>
                        <div>
                            <p class="font-bold text-gray-700">{{ ucfirst($payment->status) }}</p>
                            <p class="text-sm text-gray-600">Pembayaran tidak berhasil</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Midtrans Response (if available) --}}
            @if($payment->midtrans_response)
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                <h3 class="text-sm font-bold text-gray-700 uppercase mb-4">Response Gateway</h3>
                <div class="bg-gray-50 rounded-lg p-4">
                    <pre class="text-xs text-gray-600 overflow-x-auto">{{ json_encode($payment->midtrans_response, JSON_PRETTY_PRINT) }}</pre>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
