@extends('admin.layouts.app')

@section('title', 'Semua Toko')
@section('header_title', 'Manajemen Toko')
@section('header_description', 'Daftar semua toko yang terdaftar')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-amber-50 text-amber-600 rounded-xl">
                    <i class="fas fa-store text-lg"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-400">Total Toko</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalToko }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-blue-50 text-blue-600 rounded-xl">
                    <i class="fas fa-users text-lg"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-400">Total Users</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalUsers }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl">
                    <i class="fas fa-box text-lg"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-400">Total Barang</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalBarang }}</p>
                </div>
            </div>
        </div>
    </div>


    {{-- Toko List --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">

        {{-- JUDUL TABEL --}}
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-semibold text-gray-900">
                Daftar Toko
            </h2>
        </div>
        
        <div class="overflow-x-auto">
            <table id="stokInTable" class="w-full text-sm display">
            <thead class="bg-gray-50 text-gray-700 border-b border-gray-200">
                <tr class="border-b border-gray-400 text-gray-600 text-[12px] font-bold uppercase tracking-widest">
                    <th class="px-4 py-4">Toko</th>
                    <th class="px-4 py-4">Email</th>
                    <th class="px-4 py-4">Telepon</th>
                    <th class="px-4 py-4 text-center">Users</th>
                    <th class="px-4 py-4 text-center">Barang</th>
                    <th class="px-4 py-4 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100">
            @foreach ($tokos as $toko)
            <tr class="hover:bg-gray-50 transition">
                <td class="px-4 py-4">
                    <p class="font-bold text-gray-800 text-sm">{{ $toko->name }}</p>
                    <p class="text-[10px] text-gray-400">
                        {{ Str::limit($toko->address, 40) }}
                    </p>
                </td>

                <td class="px-4 py-4 text-gray-600">
                    {{ $toko->email }}
                </td>

                <td class="px-4 py-4 text-gray-600">
                    {{ $toko->phone }}
                </td>

                <td class="px-4 py-4 text-center">
                    <span class="px-2 py-0.5 bg-blue-50 text-blue-600 rounded-full text-[11px] font-bold">
                        {{ $toko->users_count }}
                    </span>
                </td>

                <td class="px-4 py-4 text-center">
                    <span class="px-2 py-0.5 bg-emerald-50 text-emerald-600 rounded-full text-[11px] font-bold">
                        {{ $toko->barangs_count }}
                    </span>
                </td>

                <td class="px-4 py-4 text-center">
                    <a href="{{ route('admin.toko.show', $toko) }}"
                    class="inline-flex items-center gap-1 text-amber-600 hover:text-amber-700 text-sm font-semibold">
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

