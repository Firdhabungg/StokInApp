@extends('admin.layouts.app')

@section('header_title', 'Dashboard Admin')
@section('header_description', 'Analisis Real-time Performa StokIn')

@section('content')
<div class="space-y-6">

    {{-- STAT CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="p-3 bg-blue-50 text-blue-600 rounded-xl">
                <i class="fas fa-boxes-stacked text-lg"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Total Produk</p>
                <h3 class="text-2xl font-extrabold text-gray-900">1,240</h3>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="p-3 bg-amber-50 text-amber-600 rounded-xl">
                <i class="fas fa-store text-lg"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Total Toko</p>
                <h3 class="text-2xl font-extrabold text-gray-900">128</h3>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="p-3 bg-rose-50 text-rose-600 rounded-xl">
                <i class="fas fa-triangle-exclamation text-lg"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Stok Tipis</p>
                <h3 class="text-2xl font-extrabold text-gray-900">24</h3>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl">
                <i class="fas fa-credit-card text-lg"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Revenue MoM</p>
                <h3 class="text-2xl font-extrabold text-gray-900">84%</h3>
            </div>
        </div>
    </div>

    {{-- CHART SECTION --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Pertumbuhan Langganan</h3>
                    <p class="text-xs text-gray-400 font-medium">Data pendaftaran toko 7 hari terakhir</p>
                </div>
                <select class="text-xs font-bold bg-gray-50 rounded-lg px-3 py-2 outline-none">
                    <option>7 Hari Terakhir</option>
                    <option>30 Hari Terakhir</option>
                </select>
            </div>
            <div class="h-[300px]">
                <canvas id="growthChart"></canvas>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <h3 class="text-lg font-bold text-gray-900 mb-6">Distribusi Paket</h3>
            <div class="h-[220px] flex items-center justify-center">
                <canvas id="packageChart"></canvas>
            </div>

            <div class="mt-6 space-y-3 text-xs">
                <div class="flex justify-between font-semibold text-gray-500">
                    <span class="flex items-center gap-2">
                        <span class="w-2 h-2 bg-amber-500 rounded-full"></span> Pro Plan
                    </span>
                    <span class="text-gray-900 font-bold">45%</span>
                </div>
                <div class="flex justify-between font-semibold text-gray-500">
                    <span class="flex items-center gap-2">
                        <span class="w-2 h-2 bg-blue-500 rounded-full"></span> Enterprise
                    </span>
                    <span class="text-gray-900 font-bold">30%</span>
                </div>
                <div class="flex justify-between font-semibold text-gray-500">
                    <span class="flex items-center gap-2">
                        <span class="w-2 h-2 bg-gray-300 rounded-full"></span> Starter
                    </span>
                    <span class="text-gray-900 font-bold">25%</span>
                </div>
            </div>
        </div>
    </div>

    {{-- ACTIVITY & SYSTEM --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-900">Log Aktivitas Toko</h3>
                <button class="p-2 rounded-lg hover:bg-gray-50">
                    <i class="fas fa-ellipsis-h text-gray-400"></i>
                </button>
            </div>

            <div class="p-4 space-y-4">
                <div class="flex gap-4 p-3 rounded-xl hover:bg-gray-50">
                    <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center">
                        <i class="fas fa-plus-circle"></i>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-900">Kopi Kenangan - Jakarta</p>
                        <p class="text-xs text-gray-500">Menambahkan 24 stok baru</p>
                        <p class="text-[10px] text-gray-400 font-bold mt-1 uppercase">2 menit lalu</p>
                    </div>
                </div>

                <div class="flex gap-4 p-3 rounded-xl hover:bg-gray-50">
                    <div class="w-10 h-10 bg-amber-50 text-amber-600 rounded-full flex items-center justify-center">
                        <i class="fas fa-rotate"></i>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-900">Toko Berkah</p>
                        <p class="text-xs text-gray-500">Memperbarui profil toko</p>
                        <p class="text-[10px] text-gray-400 font-bold mt-1 uppercase">15 menit lalu</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-gray-900 rounded-xl p-8 text-white relative overflow-hidden">
            <h3 class="text-xl font-bold mb-2">Health System</h3>
            <p class="text-gray-400 text-sm mb-8">Status server & database</p>

            <div class="space-y-6">
                <div>
                    <div class="flex justify-between text-xs font-bold uppercase text-gray-500 mb-2">
                        <span>Server Load</span>
                        <span class="text-emerald-400">Optimal</span>
                    </div>
                    <div class="h-1.5 bg-gray-800 rounded-full">
                        <div class="h-full w-[32%] bg-emerald-500 rounded-full"></div>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between text-xs font-bold uppercase text-gray-500 mb-2">
                        <span>Storage</span>
                        <span class="text-amber-400">64 / 128 GB</span>
                    </div>
                    <div class="h-1.5 bg-gray-800 rounded-full">
                        <div class="h-full w-[50%] bg-amber-500 rounded-full"></div>
                    </div>
                </div>
            </div>

            <div class="mt-8 flex items-center gap-4">
                <div class="flex -space-x-2">
                    <div class="w-8 h-8 bg-gray-700 rounded-full border-2 border-gray-900 flex items-center justify-center text-[10px] font-bold">JD</div>
                    <div class="w-8 h-8 bg-gray-700 rounded-full border-2 border-gray-900 flex items-center justify-center text-[10px] font-bold">AS</div>
                </div>
                <p class="text-[10px] uppercase font-bold text-gray-500">2 Admin Online</p>
            </div>
        </div>
    </div>

</div>

{{-- CHART JS --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    new Chart(document.getElementById('growthChart'), {
        type: 'line',
        data: {
            labels: ['Sen','Sel','Rab','Kam','Jum','Sab','Min'],
            datasets: [{
                data: [12,19,15,25,22,30,45],
                borderColor: '#f59e0b',
                backgroundColor: 'rgba(245,158,11,0.15)',
                fill: true,
                tension: 0.4,
                borderWidth: 3,
                pointRadius: 0
            }]
        },
        options: {
            plugins: { legend: { display: false }},
            scales: { y: { display: false }, x: { grid: { display: false }}}
        }
    });

    new Chart(document.getElementById('packageChart'), {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [45,30,25],
                backgroundColor: ['#f59e0b','#3b82f6','#e5e7eb'],
                borderWidth: 0,
                cutout: '80%'
            }]
        },
        options: { plugins: { legend: { display: false }}}
    });

});
</script>
@endsection