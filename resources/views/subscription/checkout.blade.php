@extends('layouts.dashboard')

@section('title', 'Checkout')
@section('page-title', 'Pembayaran')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="text-center mb-6">
            <div class="w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-credit-card text-amber-600 text-2xl"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-900">Pembayaran Langganan</h2>
            <p class="text-gray-500">Selesaikan pembayaran untuk mengaktifkan paket</p>
        </div>

        {{-- Order Summary --}}
        <div class="bg-gray-50 rounded-lg p-4 mb-6">
            <h3 class="font-semibold text-gray-900 mb-3">Ringkasan Pesanan</h3>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">Paket</span>
                    <span class="font-medium">{{ $plan->name }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Durasi</span>
                    <span class="font-medium">{{ $plan->duration_days }} Hari</span>
                </div>
                <hr class="my-2">
                <div class="flex justify-between text-lg">
                    <span class="font-semibold">Total</span>
                    <span class="font-bold text-amber-600">{{ $plan->formatted_price }}</span>
                </div>
            </div>
        </div>

        {{-- Pay Button --}}
        <button id="pay-button" 
            class="w-full py-4 bg-amber-500 hover:bg-amber-600 text-white rounded-xl font-bold text-lg transition-colors">
            <i class="fas fa-lock mr-2"></i>Bayar Sekarang
        </button>

        <p class="text-center text-sm text-gray-500 mt-4">
            <i class="fas fa-shield-alt mr-1"></i>
            Pembayaran diproses secara aman oleh Midtrans
        </p>

        <div class="text-center mt-4">
            <a href="{{ route('subscription.index') }}" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-arrow-left mr-1"></i>Kembali
            </a>
        </div>
    </div>
</div>

{{-- Midtrans Snap JS --}}
@if($isProduction)
    <script src="https://app.midtrans.com/snap/snap.js" data-client-key="{{ $clientKey }}"></script>
@else
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ $clientKey }}"></script>
@endif

<script>
    document.getElementById('pay-button').addEventListener('click', function() {
        snap.pay('{{ $snapToken }}', {
            onSuccess: function(result) {
                window.location.href = '{{ route('subscription.callback') }}?order_id=' + result.order_id + '&transaction_status=settlement';
            },
            onPending: function(result) {
                window.location.href = '{{ route('subscription.callback') }}?order_id=' + result.order_id + '&transaction_status=pending';
            },
            onError: function(result) {
                window.location.href = '{{ route('subscription.callback') }}?order_id=' + result.order_id + '&transaction_status=error';
            },
            onClose: function() {
                // User closed the popup without finishing payment
            }
        });
    });
</script>
@endsection
