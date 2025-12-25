@extends('layouts.dashboard')

@section('title', 'Notifikasi')
@section('page-title', 'Notifikasi')

@section('content')
    @php
        $criticalCount = count(array_filter($notifications, fn($n) => $n['priority'] === 3));
        $warningCount = count(array_filter($notifications, fn($n) => $n['priority'] === 2));
    @endphp

    {{-- Summary --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <div class="flex items-center gap-3">
                <div class="bg-blue-100 p-3 rounded-full">
                    <i class="fas fa-bell text-blue-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Total Notifikasi</p>
                    <p class="text-2xl font-bold text-gray-900">{{ count($notifications) }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <div class="flex items-center gap-3">
                <div class="bg-red-100 p-3 rounded-full">
                    <i class="fas fa-exclamation-circle text-red-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Kritis</p>
                    <p class="text-2xl font-bold text-red-600">{{ $criticalCount }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <div class="flex items-center gap-3">
                <div class="bg-orange-100 p-3 rounded-full">
                    <i class="fas fa-exclamation-triangle text-orange-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Peringatan</p>
                    <p class="text-2xl font-bold text-orange-600">{{ $warningCount }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Semua Notifikasi</h2>

        @if (count($notifications) > 0)
            <div class="space-y-3">
                @foreach ($notifications as $notif)
                    <a href="{{ $notif['link'] }}" 
                        class="flex items-start gap-4 p-4 rounded-lg hover:bg-gray-50 transition-colors border
                            @if($notif['priority'] === 3) border-red-200 bg-red-50
                            @elseif($notif['priority'] === 2) border-orange-200 bg-orange-50
                            @else border-gray-200 @endif">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0
                            @if($notif['color'] === 'red') bg-red-100
                            @elseif($notif['color'] === 'orange') bg-orange-100
                            @else bg-gray-100 @endif">
                            <i class="fas {{ $notif['icon'] }}
                                @if($notif['color'] === 'red') text-red-600
                                @elseif($notif['color'] === 'orange') text-orange-600
                                @else text-gray-600 @endif"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-gray-900">{{ $notif['title'] }}</p>
                            <p class="text-sm text-gray-600">{{ $notif['message'] }}</p>
                        </div>
                        <div class="flex-shrink-0">
                            <span class="px-2 py-1 rounded-full text-xs font-medium
                                @if($notif['priority'] === 3) bg-red-100 text-red-700
                                @elseif($notif['priority'] === 2) bg-orange-100 text-orange-700
                                @else bg-gray-100 text-gray-700 @endif">
                                @if($notif['priority'] === 3) Kritis
                                @elseif($notif['priority'] === 2) Peringatan
                                @else Info @endif
                            </span>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-check text-green-600 text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-1">Tidak Ada Notifikasi</h3>
                <p class="text-gray-500">Semua stok dalam kondisi aman</p>
            </div>
        @endif
    </div>
@endsection
