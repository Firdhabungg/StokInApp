@extends('admin.layouts.app')

@section('title', 'Manajemen Paket')
@section('header_description', 'Atur harga dan fitur paket berlangganan StokIn')

@section('content')
    <div class="space-y-6">

        {{-- SUMMARY CARDS --}}
        <div class="grid grid-cols-1 md:grid-cols-{{ $plans->count() > 3 ? 3 : $plans->count() }} gap-6">
            @foreach ($plans as $plan)
                <div
                    class="bg-white rounded-xl p-6 shadow-sm border {{ $plan->slug === 'pro' ? 'border-amber-200' : 'border-gray-100' }} relative overflow-hidden">
                    @if ($plan->slug === 'pro')
                        <span
                            class="absolute top-0 right-0 bg-amber-500 text-white text-[10px] font-black px-4 py-1 rounded-bl-xl uppercase">
                            Populer
                        </span>
                    @endif
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="p-3 {{ $plan->slug === 'pro' ? 'bg-amber-50 text-amber-600' : ($plan->slug === 'free' ? 'bg-blue-50 text-blue-600' : 'bg-purple-50 text-purple-600') }} rounded-xl">
                            <i
                                class="fas {{ $plan->slug === 'pro' ? 'fa-bolt' : ($plan->slug === 'free' ? 'fa-gift' : 'fa-crown') }} text-lg"></i>
                        </div>
                        <span
                            class="text-[10px] font-bold {{ $plan->slug === 'pro' ? 'text-amber-500' : 'text-gray-400' }} uppercase tracking-widest">
                            {{ $plan->slug === 'free' ? 'Trial' : ($plan->slug === 'pro' ? 'Growth' : 'Premium') }}
                        </span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">{{ $plan->name }}</h3>
                    <p class="text-3xl font-extrabold text-gray-900 mt-2">
                        {{ $plan->active_count }} <span class="text-sm font-medium text-gray-400">Toko Aktif</span>
                    </p>
                </div>
            @endforeach
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">

            {{-- Success Message --}}
            @if (session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg mb-6">
                    <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                </div>
            @endif

            {{-- JUDUL TABEL --}}
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-semibold text-gray-900">
                    Daftar Konfigurasi Paket
                </h2>
            </div>

            <div class="overflow-x-auto">
                <table id="paketTable" class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-700 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-[12px] font-bold uppercase tracking-widest">Nama Paket</th>
                            <th class="px-6 py-4 text-left text-[12px] font-bold uppercase tracking-widest">Harga</th>
                            <th class="px-6 py-4 text-left text-[12px] font-bold uppercase tracking-widest">Durasi</th>
                            <th class="px-6 py-4 text-left text-[12px] font-bold uppercase tracking-widest">Limit User</th>
                            <th class="px-6 py-4 text-center text-[12px] font-bold uppercase tracking-widest">Export</th>
                            <th class="px-6 py-4 text-center text-[12px] font-bold uppercase tracking-widest">Status</th>
                            <th class="px-6 py-4 text-center text-[12px] font-bold uppercase tracking-widest">Toko Aktif
                            </th>
                            <th class="px-6 py-4 text-center text-[12px] font-bold uppercase tracking-widest">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($plans as $plan)
                            @php
                                $features = $plan->features ?? [];
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <p class="font-semibold text-gray-900">{{ $plan->name }}</p>
                                    <p class="text-xs text-gray-400">{{ $features['description'] ?? $plan->slug }}</p>
                                </td>
                                <td class="px-6 py-4 font-semibold text-gray-700">
                                    @if ($plan->price == 0)
                                        <span class="text-blue-600">Gratis</span>
                                    @else
                                        Rp {{ number_format($plan->price, 0, ',', '.') }}
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-gray-600">
                                    {{ $plan->duration_days }} hari
                                </td>
                                <td class="px-6 py-4 text-gray-600">
                                    @if (isset($features['max_users']) && $features['max_users'] == -1)
                                        <span class="text-emerald-600 font-semibold">Unlimited</span>
                                    @else
                                        {{ $features['max_users'] ?? '-' }} user
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if ($features['export_report'] ?? false)
                                        <i class="fas fa-check-circle text-emerald-500"></i>
                                    @else
                                        <i class="fas fa-times-circle text-gray-300"></i>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if ($plan->is_active)
                                        <span
                                            class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-600 text-xs font-semibold">
                                            Aktif
                                        </span>
                                    @else
                                        <span
                                            class="px-3 py-1 rounded-full bg-gray-100 text-gray-500 text-xs font-semibold">
                                            Nonaktif
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-3 py-1 rounded-full bg-amber-50 text-amber-600 text-xs font-bold">
                                        {{ $plan->active_count }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.kelola-paket.edit', $plan) }}"
                                            class="p-2 text-gray-400 hover:text-amber-600 transition-colors" title="Edit">
                                            <i class="fas fa-pen"></i>
                                        </a>
                                        <form action="{{ route('admin.kelola-paket.toggle', $plan) }}" method="POST"
                                            class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                class="p-2 {{ $plan->is_active ? 'text-gray-400 hover:text-rose-600' : 'text-gray-400 hover:text-emerald-600' }} transition-colors"
                                                title="{{ $plan->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                                <i
                                                    class="fas {{ $plan->is_active ? 'fa-toggle-on text-emerald-500' : 'fa-toggle-off' }}"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
