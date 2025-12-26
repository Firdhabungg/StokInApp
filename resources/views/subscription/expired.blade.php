@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 flex items-center justify-center p-4">
    <div class="max-w-lg w-full">
        <div class="bg-white rounded-2xl shadow-xl p-8 text-center">
            {{-- Icon --}}
            <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-clock text-red-500 text-3xl"></i>
            </div>

            {{-- Title --}}
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Langganan Berakhir</h1>
            <p class="text-gray-500 mb-6">
                Masa langganan Anda telah berakhir. Perpanjang sekarang untuk melanjutkan akses ke semua fitur StokIn.
            </p>

            {{-- Benefits --}}
            <div class="bg-amber-50 rounded-lg p-4 mb-6 text-left">
                <p class="font-semibold text-amber-700 mb-2">Dengan paket Pro, Anda mendapatkan:</p>
                <ul class="space-y-2 text-sm text-amber-700">
                    <li class="flex items-center gap-2">
                        <i class="fas fa-check-circle"></i>
                        <span>Produk & Transaksi Unlimited</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="fas fa-check-circle"></i>
                        <span>Hingga 10 pengguna</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="fas fa-check-circle"></i>
                        <span>Export Laporan PDF/Excel</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="fas fa-check-circle"></i>
                        <span>Prioritas Support</span>
                    </li>
                </ul>
            </div>

            {{-- Plans --}}
            <div class="space-y-3 mb-6">
                @foreach($plans as $plan)
                    <a href="{{ route('subscription.checkout', $plan->slug) }}" 
                        class="block w-full py-4 bg-amber-500 hover:bg-amber-600 text-white rounded-xl font-bold transition-colors">
                        Pilih {{ $plan->name }} - {{ $plan->formatted_price }}/bulan
                    </a>
                @endforeach
            </div>

            {{-- Logout Link --}}
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-gray-500 hover:text-gray-700 text-sm">
                    <i class="fas fa-sign-out-alt mr-1"></i>Keluar dari akun
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
