@extends('admin.layouts.app')

@section('title', 'Semua Toko')
@section('header_title', 'Manajemen Toko')
@section('header_description', 'Daftar semua toko yang terdaftar')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <div class="flex items-center gap-3">
                <div class="bg-amber-100 p-3 rounded-full">
                    <i class="fas fa-store text-amber-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Total Toko</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalToko }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <div class="flex items-center gap-3">
                <div class="bg-blue-100 p-3 rounded-full">
                    <i class="fas fa-users text-blue-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Total Users</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalUsers }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <div class="flex items-center gap-3">
                <div class="bg-green-100 p-3 rounded-full">
                    <i class="fas fa-box text-green-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Total Barang</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalBarang }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Toko List --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Daftar Toko</h2>
        
        <div class="overflow-x-auto">
            <table id="stokInTable" class="w-full text-sm display">
                <thead>
                    <tr>
                        <th>Toko</th>
                        <th>Email</th>
                        <th>Telepon</th>
                        <th>Users</th>
                        <th>Barang</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tokos as $toko)
                        <tr>
                            <td>
                                <p class="font-semibold text-gray-900">{{ $toko->name }}</p>
                                <p class="text-xs text-gray-500">{{ Str::limit($toko->address, 40) }}</p>
                            </td>
                            <td class="text-gray-600">{{ $toko->email }}</td>
                            <td class="text-gray-600">{{ $toko->phone }}</td>
                            <td class="text-center">
                                <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-medium">
                                    {{ $toko->users_count }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium">
                                    {{ $toko->barangs_count }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.toko.show', $toko) }}" 
                                    class="text-amber-600 hover:text-amber-700">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

