@extends('admin.layouts.app')

@section('title', 'Daftar Toko')
@section('header_description', 'Daftar semua toko yang terdaftar dan status langganan')

@section('content')
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Daftar Toko</h2>
                <p class="text-gray-500 mt-1">Kelola semua toko yang terdaftar di StokIn</p>
            </div>
        </div>
    </div>
    
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-6">
        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-amber-50 text-amber-600 rounded-lg">
                    <i class="fas fa-store"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-400">Total Toko</p>
                    <p class="text-xl font-bold text-gray-900">{{ $totalToko }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-emerald-50 text-emerald-600 rounded-lg">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-400">Aktif</p>
                    <p class="text-xl font-bold text-emerald-600">{{ $tokoAktif }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-blue-50 text-blue-600 rounded-lg">
                    <i class="fas fa-clock"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-400">Trial</p>
                    <p class="text-xl font-bold text-blue-600">{{ $tokoTrial }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-rose-50 text-rose-600 rounded-lg">
                    <i class="fas fa-times-circle"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-400">Expired</p>
                    <p class="text-xl font-bold text-rose-600">{{ $tokoExpired }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-purple-50 text-purple-600 rounded-lg">
                    <i class="fas fa-users"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-400">Total Users</p>
                    <p class="text-xl font-bold text-gray-900">{{ $totalUsers }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm p-5 mb-6">
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <i class="fas fa-search text-amber-500 text-lg"></i>
            </div>
            <input type="text" id="customSearchInput" 
                class="w-full pl-12 pr-4 py-2 bg-white border-2 border-amber-200/50 rounded-xl text-gray-700 placeholder-gray-400 focus:outline-none focus:border-amber-400 focus:ring-4 focus:ring-amber-100 transition-all duration-300 text-base shadow-sm"
                placeholder="Cari toko berdasarkan nama, email, atau paket...">
        </div>
        <div class="flex items-center gap-4 mt-3 text-sm text-gray-500">
            <span class="flex items-center gap-1.5">
                <i class="fas fa-filter text-amber-500"></i>
                <span>Filter cepat:</span>
            </span>
            <button type="button" onclick="filterByStatus('Aktif')" class="filter-btn px-3 py-1 bg-emerald-100 text-emerald-600 rounded-full hover:bg-emerald-200 transition-colors font-medium">
                <i class="fas fa-check-circle mr-1"></i> Aktif
            </button>
            <button type="button" onclick="filterByStatus('Trial')" class="filter-btn px-3 py-1 bg-blue-100 text-blue-600 rounded-full hover:bg-blue-200 transition-colors font-medium">
                <i class="fas fa-clock mr-1"></i> Trial
            </button>
            <button type="button" onclick="filterByStatus('Expired')" class="filter-btn px-3 py-1 bg-rose-100 text-rose-600 rounded-full hover:bg-rose-200 transition-colors font-medium">
                <i class="fas fa-times-circle mr-1"></i> Expired
            </button>
            <button type="button" onclick="clearFilter()" class="filter-btn px-3 py-1 bg-gray-100 text-gray-600 rounded-full hover:bg-gray-200 transition-colors font-medium">
                <i class="fas fa-redo mr-1"></i> Reset
            </button>
        </div>
    </div>

    {{-- Toko List --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="overflow-x-auto">
            <table id="tokoTable" class="w-full text-sm display">
            <thead class="bg-gray-50 text-gray-700 border-b border-gray-200">
                <tr class="border-b border-gray-400 text-gray-600 text-[12px] font-bold uppercase tracking-widest">
                    <th class="px-4 py-4">Toko</th>
                    <th class="px-4 py-4">Paket</th>
                    <th class="px-4 py-4 text-center">Status</th>
                    <th class="px-4 py-4 text-center">Sisa Waktu</th>
                    <th class="px-4 py-4 text-center">Users</th>
                    <th class="px-4 py-4 text-center">Barang</th>
                    <th class="px-4 py-4 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100">
            @foreach ($tokos as $toko)
            @php
                $sub = $toko->activeSubscription;
                $plan = $sub?->plan;
                $daysRemaining = $sub ? (int) floor(now()->diffInDays($sub->expires_at, false)) : 0;
            @endphp
            <tr class="hover:bg-gray-50 transition">
                <td class="px-4 py-4">
                    <p class="font-bold text-gray-800 text-sm">{{ $toko->name }}</p>
                    <p class="text-[10px] text-gray-400">{{ $toko->email }}</p>
                </td>

                <td class="px-4 py-4">
                    @if($plan)
                        <span class="font-semibold text-gray-700">{{ $plan->name }}</span>
                    @else
                        <span class="text-gray-400">-</span>
                    @endif
                </td>

                <td class="px-4 py-4 text-center">
                    @if($sub)
                        @if($sub->status === 'trial')
                            <span class="px-2 py-1 bg-blue-50 text-blue-600 rounded-full text-[11px] font-bold">Trial</span>
                        @elseif($sub->status === 'active')
                            <span class="px-2 py-1 bg-emerald-50 text-emerald-600 rounded-full text-[11px] font-bold">Aktif</span>
                        @else
                            <span class="px-2 py-1 bg-rose-50 text-rose-600 rounded-full text-[11px] font-bold">Expired</span>
                        @endif
                    @else
                        <span class="px-2 py-1 bg-gray-100 text-gray-500 rounded-full text-[11px] font-bold">Belum Langganan</span>
                    @endif
                </td>

                <td class="px-4 py-4 text-center">
                    @if($sub && $daysRemaining > 0)
                        <span class="{{ $daysRemaining <= 7 ? 'text-rose-600 font-bold' : 'text-gray-600' }}">
                            {{ $daysRemaining }} hari
                        </span>
                    @elseif($sub)
                        <span class="text-rose-600 font-bold">Expired</span>
                    @else
                        <span class="text-gray-400">-</span>
                    @endif
                </td>

                <td class="px-4 py-4 text-center">
                    <span class="px-2 py-0.5 bg-purple-50 text-purple-600 rounded-full text-[11px] font-bold">
                        {{ $toko->users_count }}
                    </span>
                </td>

                <td class="px-4 py-4 text-center">
                    <span class="px-2 py-0.5 bg-cyan-50 text-cyan-600 rounded-full text-[11px] font-bold">
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

@push('scripts')
<script>
    let table;
    
    document.addEventListener('DOMContentLoaded', function() {
        if (document.querySelectorAll('#tokoTable tbody tr').length > 0) {
            table = new DataTable('#tokoTable', {
                responsive: true,
                pageLength: 10,
                dom: 'lrtip',
                language: {
                    lengthMenu: 'Tampilkan _MENU_ data per halaman',
                    info: 'Menampilkan <b>_START_</b> sampai <b>_END_</b> dari <b>_TOTAL_</b> toko',
                    paginate: { first: '<<', last: '>>', next: '>', previous: '<' },
                    zeroRecords: 'Tidak ada data yang ditemukan',
                    infoEmpty: 'Menampilkan 0 data',
                    infoFiltered: '(disaring dari _MAX_ total data)',
                    emptyTable: 'Belum ada data toko'
                }
            });

            // Connect custom search input to DataTable
            const customSearch = document.getElementById('customSearchInput');
            customSearch.addEventListener('keyup', function() {
                table.search(this.value).draw();
            });
        }
    });

    // Filter by status
    function filterByStatus(status) {
        if (table) {
            document.getElementById('customSearchInput').value = status;
    
            // kolom STATUS = index ke-2 (mulai dari 0)
            table.column(2).search('^' + status + '$', true, false).draw();
    
            document.querySelectorAll('.filter-btn').forEach(btn => {
                btn.classList.remove('ring-2', 'ring-offset-1');
            });
    
            event.target.closest('.filter-btn')
                .classList.add('ring-2', 'ring-offset-1');
        }
    }


    // Clear filter
    function clearFilter() {
        if (table) {
            document.getElementById('customSearchInput').value = '';
            table.search('').columns().search('').draw();
    
            document.querySelectorAll('.filter-btn').forEach(btn => {
                btn.classList.remove('ring-2', 'ring-offset-1');
            });
        }
    }

</script>
@endpush
