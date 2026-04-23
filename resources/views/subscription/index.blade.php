@extends('layouts.dashboard')

@section('title', 'Langganan')
@section('page-title', 'Kelola Langganan')
@section('page-description', 'Informasi masa aktif layanan')

@section('content')
    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <div class="flex items-center justify-between mb-2">
                <h2 class="text-lg font-bold text-gray-900">Status Langganan</h2>
                @if ($subscription && $subscription->isActive())
                    <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-semibold">
                        <i class="fas fa-check-circle mr-1"></i>Aktif
                    </span>
                @else
                    <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-sm font-semibold">
                        <i class="fas fa-times-circle mr-1"></i>Tidak Aktif
                    </span>
                @endif
            </div>

            @if ($subscription)
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-amber-500 rounded-lg p-4">
                        <p class="text-sm text-black mb-1">Paket Saat Ini</p>
                        <p class="text-xl font-bold text-amber-100">{{ $subscription->plan->name }}</p>
                    </div>
                    @if ($subscription->isTrial())
                        <div class="bg-amber-400 rounded-lg p-4">
                            <p class="text-sm text-gray-500 mb-1">Status</p>
                            <span class="text-amber-600 text-xl font-bold">Trial</span>
                        </div>
                    @elseif($subscription->isActive())
                        <div class="bg-green-400 rounded-lg p-4">
                            <p class="text-sm text-black mb-1">Status</p>
                            <span class="text-green-100 text-xl font-bold">Aktif</span>
                        </div>
                    @else
                        <div class="bg-red-400 rounded-lg p-4">
                            <p class="text-sm text-black mb-1">Status</p>
                            <span class="text-red-100 text-xl font-bold">Expired</span>
                        </div>
                    @endif

                    <div class="bg-blue-600 rounded-lg p-4 flex justify-between items-center">
                        <div class="items-center gap-2">
                            <p class="text-sm text-black mb-1">Berlaku Hingga</p>
                            <p class="text-xl font-bold text-blue-200">{{ $subscription->expires_at->format('d M Y') }}</p>
                        </div>
                        <p class="bg-blue-400 px-3 py-1 rounded-full text-sm text-white">
                            {{ $subscription->daysRemaining() }} hari lagi</p>
                    </div>
                </div>
            @else
                <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                    <p class="text-amber-700">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Anda belum memiliki langganan aktif. Silakan pilih paket di bawah.
                    </p>
                </div>
            @endif
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <h2 class="text-lg font-bold text-gray-900 mb-2">Pilih Paket</h2>

            <div class="grid md:grid-cols-2 gap-4">
                @foreach ($plans as $plan)
                    <div
                        class="border-2 rounded-xl p-6 transition-all {{ $subscription && $subscription->plan_id == $plan->id ? 'border-amber-300 border-2 bg-amber-400' : 'border-gray-200 hover:border-amber-300' }}">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-xl font-bold text-gray-900">{{ $plan->name }}</h3>
                            @if ($subscription && $subscription->plan_id == $plan->id)
                                <span class="px-4 py-1 bg-amber-300 text-black font-bold text-xs rounded-full">Aktif</span>
                            @endif
                        </div>

                        <p class="text-3xl font-extrabold text-gray-900 mb-2">
                            {{ $plan->isFree() ? 'Gratis' : $plan->formatted_price }}
                            <span class="text-base font-normal text-gray-500">/ {{ $plan->duration_days }} hari</span>
                        </p>

                        <ul class="space-y-2 my-4 text-sm">
                            @if ($plan->features)
                                @if (isset($plan->features['description']))
                                    <li class="flex items-center gap-2 text-amber-600 font-semibold">
                                        <i class="fas fa-star text-amber-500"></i>
                                        <span>{{ $plan->features['description'] }}</span>
                                    </li>
                                @endif

                                <li class="flex items-center gap-2">
                                    <i class="fas fa-check text-green-500"></i>
                                    <span>Produk & Transaksi Unlimited</span>
                                </li>

                                @php
                                    $maxKasir = $plan->features['max_kasir'] ?? 1;
                                @endphp
                                <li class="flex items-center gap-2">
                                    <i class="fas fa-check text-green-500"></i>
                                    @if ($maxKasir == -1)
                                        <span>Unlimited Pengguna</span>
                                    @elseif ($maxKasir == 1)
                                        <span>1 Owner + 1 Kasir</span>
                                    @else
                                        <span>1 Owner + {{ $maxKasir }} Kasir</span>
                                    @endif
                                </li>

                                <li class="flex items-center gap-2">
                                    <i class="fas fa-check text-green-500"></i>
                                    <span>Manajemen Stok Barang</span>
                                </li>
                                <li class="flex items-center gap-2">
                                    <i class="fas fa-check text-green-500"></i>
                                    <span>Transaksi Penjualan</span>
                                </li>
                                <li class="flex items-center gap-2">
                                    <i class="fas fa-check text-green-500"></i>
                                    <span>Laporan & Dashboard</span>
                                </li>

                                <li class="flex items-center gap-2">
                                    @if ($plan->features['export_report'] ?? false)
                                        <i class="fas fa-check text-green-500"></i>
                                        <span>Export Laporan</span>
                                    @else
                                        <i class="fas fa-times text-gray-400"></i>
                                        <span class="text-gray-400">Export Laporan</span>
                                    @endif
                                </li>
                            @endif
                        </ul>

                        @if ($subscription && $subscription->plan_id == $plan->id && $subscription->isActive())
                            <button disabled
                                class="w-full py-3 bg-amber-500 text-green-200 rounded-lg font-semibold cursor-not-allowed">
                                Paket Aktif
                            </button>
                        @elseif($plan->isFree() && $hasUsedFreeTrial)
                            <button disabled
                                class="w-full py-3 bg-red-200 text-red-500 rounded-lg font-semibold cursor-not-allowed">
                                <i class="fas fa-ban mr-2"></i>Trial Sudah Digunakan
                            </button>
                        @else
                            <a href="{{ route('subscription.checkout', $plan->slug) }}"
                                class="block w-full py-3 text-center bg-amber-500 hover:bg-amber-600 text-white rounded-lg font-semibold transition-colors">
                                {{ $plan->isFree() ? 'Mulai Trial' : 'Pilih Paket' }}
                            </a>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
