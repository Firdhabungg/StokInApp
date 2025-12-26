@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-5xl mx-auto px-4">
        {{-- Header --}}
        <div class="text-center mb-12">
            <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-4">Pilih Paket yang Tepat</h1>
            <p class="text-gray-500 text-lg">Mulai kelola inventaris bisnis Anda dengan lebih efisien</p>
        </div>

        {{-- Plans --}}
        <div class="grid md:grid-cols-2 gap-8 max-w-4xl mx-auto">
            @foreach($plans as $plan)
                <div class="bg-white rounded-2xl shadow-lg p-8 {{ !$plan->isFree() ? 'border-2 border-amber-500 relative' : 'border border-gray-200' }}">
                    @if(!$plan->isFree())
                        <div class="absolute -top-3 left-1/2 transform -translate-x-1/2 bg-amber-500 text-white px-4 py-1 rounded-full text-sm font-bold">
                            POPULER
                        </div>
                    @endif

                    <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $plan->name }}</h3>
                    <p class="text-gray-500 mb-4">
                        {{ $plan->isFree() ? 'Coba gratis selama ' . $plan->duration_days . ' hari' : 'Untuk bisnis yang berkembang' }}
                    </p>

                    <p class="text-4xl font-extrabold text-gray-900 mb-6">
                        {{ $plan->isFree() ? 'Gratis' : $plan->formatted_price }}
                        <span class="text-base font-normal text-gray-500">
                            {{ $plan->isFree() ? '' : '/bulan' }}
                        </span>
                    </p>

                    <ul class="space-y-3 mb-8">
                        @if($plan->features)
                            <li class="flex items-center gap-3">
                                <i class="fas fa-check text-green-500"></i>
                                <span>{{ $plan->features['max_products'] == -1 ? 'Produk Unlimited' : 'Max ' . $plan->features['max_products'] . ' produk' }}</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <i class="fas fa-check text-green-500"></i>
                                <span>{{ $plan->features['max_transactions'] == -1 ? 'Transaksi Unlimited' : 'Max ' . $plan->features['max_transactions'] . ' transaksi/bulan' }}</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <i class="fas fa-check text-green-500"></i>
                                <span>{{ $plan->features['max_users'] }} pengguna</span>
                            </li>
                            <li class="flex items-center gap-3">
                                @if($plan->features['export_report'])
                                    <i class="fas fa-check text-green-500"></i>
                                @else
                                    <i class="fas fa-times text-gray-300"></i>
                                @endif
                                <span class="{{ $plan->features['export_report'] ? '' : 'text-gray-400' }}">Export Laporan</span>
                            </li>
                            <li class="flex items-center gap-3">
                                @if($plan->features['priority_support'])
                                    <i class="fas fa-check text-green-500"></i>
                                @else
                                    <i class="fas fa-times text-gray-300"></i>
                                @endif
                                <span class="{{ $plan->features['priority_support'] ? '' : 'text-gray-400' }}">Prioritas Support</span>
                            </li>
                        @endif
                    </ul>

                    @auth
                        <a href="{{ route('subscription.checkout', $plan->slug) }}" 
                            class="block w-full py-3 text-center rounded-xl font-bold transition-colors {{ $plan->isFree() ? 'bg-gray-100 hover:bg-gray-200 text-gray-700' : 'bg-amber-500 hover:bg-amber-600 text-white' }}">
                            {{ $plan->isFree() ? 'Mulai Trial' : 'Pilih Pro' }}
                        </a>
                    @else
                        <a href="{{ route('register', ['plan' => $plan->slug]) }}" 
                            class="block w-full py-3 text-center rounded-xl font-bold transition-colors {{ $plan->isFree() ? 'bg-gray-100 hover:bg-gray-200 text-gray-700' : 'bg-amber-500 hover:bg-amber-600 text-white' }}">
                            {{ $plan->isFree() ? 'Mulai Trial' : 'Pilih Pro' }}
                        </a>
                    @endauth
                </div>
            @endforeach
        </div>

        {{-- Back to Home --}}
        <div class="text-center mt-8">
            <a href="/" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-arrow-left mr-1"></i>Kembali ke Beranda
            </a>
        </div>
    </div>
</div>
@endsection
