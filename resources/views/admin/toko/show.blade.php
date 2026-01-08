@extends('layouts.dashboard')

@section('title', 'Detail Toko')
@section('page-title', $toko->name)

@section('content')
    {{-- Back Button --}}
    <div class="mb-4">
        <a href="{{ route('admin.toko.index') }}" class="text-amber-600 hover:text-amber-700">
            <i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar Toko
        </a>
    </div>

    {{-- Toko Info --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
        <div class="flex items-start justify-between">
            <div class="flex items-start gap-4">
                <div class="w-16 h-16 bg-amber-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-store text-amber-600 text-2xl"></i>
                </div>
                <div class="flex-1">
                    <h2 class="text-xl font-bold text-gray-900">{{ $toko->name }}</h2>
                    <p class="text-gray-500">{{ $toko->email }}</p>
                    <p class="text-gray-500">{{ $toko->phone }}</p>
                    <p class="text-gray-500 mt-2">{{ $toko->address }}</p>
                </div>
            </div>
            
            {{-- Akses Toko Button --}}
            <form action="{{ route('admin.akses-toko.start', $toko) }}" method="POST">
                @csrf
                <button type="submit" 
                    class="flex items-center gap-2 px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-lg transition-colors font-medium shadow-lg shadow-amber-200">
                    <i class="fas fa-sign-in-alt"></i>
                    <span>Masuk sebagai Toko Ini</span>
                </button>
            </form>
        </div>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500">Total Users</p>
            <p class="text-2xl font-bold text-blue-600">{{ $toko->users->count() }}</p>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500">Total Barang</p>
            <p class="text-2xl font-bold text-green-600">{{ $toko->barangs->count() }}</p>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500">Total Stok</p>
            <p class="text-2xl font-bold text-amber-600">{{ number_format($totalStok) }}</p>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500">Total Penjualan</p>
            <p class="text-2xl font-bold text-purple-600">Rp {{ number_format($totalPenjualan / 1000) }}k</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Users --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Users</h3>
            <div class="space-y-3">
                @foreach ($toko->users as $user)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-amber-500 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-white"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ $user->name }}</p>
                                <p class="text-xs text-gray-500">{{ $user->email }}</p>
                            </div>
                        </div>
                        <span class="px-2 py-1 rounded-full text-xs font-medium
                            @if($user->role == 'owner') bg-amber-100 text-amber-700
                            @else bg-gray-100 text-gray-700 @endif">
                            {{ ucfirst($user->role) }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Recent Barang --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Barang Terbaru</h3>
            <div class="space-y-3">
                @foreach ($toko->barangs->take(5) as $barang)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div>
                            <p class="font-medium text-gray-900">{{ $barang->nama_barang }}</p>
                            <p class="text-xs text-gray-500">{{ $barang->kode_barang }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-gray-900">{{ $barang->stok }}</p>
                            <p class="text-xs text-gray-500">stok</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
