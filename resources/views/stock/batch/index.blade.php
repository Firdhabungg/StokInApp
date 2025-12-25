@extends('layouts.dashboard')

@section('title', 'Daftar Batch Stok')
@section('page-title', 'Daftar Batch Stok')

@section('content')
    {{-- Status Summary Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Batch Aman</p>
                    <p class="text-2xl font-bold text-green-600">{{ $statusCounts['aman'] }}</p>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Hampir Kadaluarsa</p>
                    <p class="text-2xl font-bold text-orange-600">{{ $statusCounts['hampir_kadaluarsa'] }}</p>
                </div>
                <div class="bg-orange-100 p-3 rounded-full">
                    <i class="fas fa-exclamation-triangle text-orange-600 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Kadaluarsa</p>
                    <p class="text-2xl font-bold text-red-600">{{ $statusCounts['kadaluarsa'] }}</p>
                </div>
                <div class="bg-red-100 p-3 rounded-full">
                    <i class="fas fa-times-circle text-red-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-semibold text-gray-900">Semua Batch Stok</h2>
            
            {{-- Filter --}}
            <form method="GET" class="flex gap-2">
                <select name="status" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    <option value="">Semua Status</option>
                    <option value="aman" {{ request('status') == 'aman' ? 'selected' : '' }}>Aman</option>
                    <option value="hampir_kadaluarsa" {{ request('status') == 'hampir_kadaluarsa' ? 'selected' : '' }}>Hampir Kadaluarsa</option>
                    <option value="kadaluarsa" {{ request('status') == 'kadaluarsa' ? 'selected' : '' }}>Kadaluarsa</option>
                </select>
                <select name="barang_id" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    <option value="">Semua Barang</option>
                    @foreach ($barangs as $barang)
                        <option value="{{ $barang->id }}" {{ request('barang_id') == $barang->id ? 'selected' : '' }}>
                            {{ $barang->nama_barang }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 rounded-lg text-sm">
                    <i class="fas fa-filter mr-1"></i>Filter
                </button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-center">
                <thead class="text-gray-700 bg-gray-50">
                    <tr>
                        <th class="px-6 py-3">Kode Batch</th>
                        <th class="px-6 py-3">Barang</th>
                        <th class="px-6 py-3">Jumlah Awal</th>
                        <th class="px-6 py-3">Sisa</th>
                        <th class="px-6 py-3">Tgl Masuk</th>
                        <th class="px-6 py-3">Tgl Kadaluarsa</th>
                        <th class="px-6 py-3">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($batches as $batch)
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="px-6 py-4 font-mono text-xs">{{ $batch->batch_code }}</td>
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $batch->barang->nama_barang }}</td>
                            <td class="px-6 py-4">{{ $batch->jumlah_awal }}</td>
                            <td class="px-6 py-4">
                                <span class="font-semibold {{ $batch->jumlah_sisa > 0 ? 'text-green-600' : 'text-gray-400' }}">
                                    {{ $batch->jumlah_sisa }}
                                </span>
                            </td>
                            <td class="px-6 py-4">{{ $batch->tgl_masuk->format('d/m/Y') }}</td>
                            <td class="px-6 py-4">{{ $batch->tgl_kadaluarsa ? $batch->tgl_kadaluarsa->format('d/m/Y') : '-' }}</td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-medium
                                    @if($batch->status == 'aman') bg-green-100 text-green-700
                                    @elseif($batch->status == 'hampir_kadaluarsa') bg-orange-100 text-orange-700
                                    @else bg-red-100 text-red-700
                                    @endif">
                                    {{ str_replace('_', ' ', ucfirst($batch->status)) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                <i class="fas fa-inbox text-4xl mb-2"></i>
                                <p>Belum ada data batch</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $batches->appends(request()->query())->links() }}
        </div>
    </div>
@endsection
