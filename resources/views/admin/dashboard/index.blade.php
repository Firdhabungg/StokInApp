@extends('admin.layouts.app')

@section('header_title', 'Dashboard Admin')
@section('header_description', 'Analisis Real-time Performa StokIn')

@section('content')
<div class="space-y-6">
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-3xl border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="p-3 bg-blue-50 text-blue-600 rounded-2xl">
                <i data-lucide="box" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total Produk</p>
                <h3 class="text-2xl font-black text-slate-800">1,240</h3>
            </div>
        </div>

        <div class="bg-white p-5 rounded-3xl border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="p-3 bg-amber-50 text-amber-600 rounded-2xl">
                <i data-lucide="store" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total Toko</p>
                <h3 class="text-2xl font-black text-slate-800">128</h3>
            </div>
        </div>

        <div class="bg-white p-5 rounded-3xl border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="p-3 bg-rose-50 text-rose-600 rounded-2xl">
                <i data-lucide="alert-triangle" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Stok Tipis</p>
                <h3 class="text-2xl font-black text-slate-800">24</h3>
            </div>
        </div>

        <div class="bg-white p-5 rounded-3xl border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="p-3 bg-emerald-50 text-emerald-600 rounded-2xl">
                <i data-lucide="credit-card" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Revenue MoM</p>
                <h3 class="text-2xl font-black text-slate-800">84%</h3>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-bold text-slate-800">Pertumbuhan Langganan</h3>
                    <p class="text-xs text-slate-400 font-medium">Data pendaftaran toko 7 hari terakhir</p>
                </div>
                <select class="text-xs font-bold bg-slate-50 border-none rounded-lg p-2 outline-none">
                    <option>7 Hari Terakhir</option>
                    <option>30 Hari Terakhir</option>
                </select>
            </div>
            <div class="h-[300px] w-full">
                <canvas id="growthChart"></canvas>
            </div>
        </div>

        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm">
            <h3 class="text-lg font-bold text-slate-800 mb-6">Distribusi Paket</h3>
            <div class="h-[220px] flex items-center justify-center">
                <canvas id="packageChart"></canvas>
            </div>
            <div class="mt-6 space-y-3">
                <div class="flex items-center justify-between text-xs">
                    <span class="flex items-center gap-2 font-semibold text-slate-500">
                        <span class="w-2 h-2 rounded-full bg-amber-500"></span> Pro Plan
                    </span>
                    <span class="font-bold text-slate-800">45%</span>
                </div>
                <div class="flex items-center justify-between text-xs">
                    <span class="flex items-center gap-2 font-semibold text-slate-500">
                        <span class="w-2 h-2 rounded-full bg-blue-500"></span> Enterprise
                    </span>
                    <span class="font-bold text-slate-800">30%</span>
                </div>
                <div class="flex items-center justify-between text-xs">
                    <span class="flex items-center gap-2 font-semibold text-slate-500">
                        <span class="w-2 h-2 rounded-full bg-slate-300"></span> Starter
                    </span>
                    <span class="font-bold text-slate-800">25%</span>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-50 flex items-center justify-between">
                <h3 class="text-lg font-bold text-slate-800">Log Aktivitas Toko</h3>
                <button class="p-2 hover:bg-slate-50 rounded-xl transition-colors">
                    <i data-lucide="more-horizontal" class="w-5 h-5 text-slate-400"></i>
                </button>
            </div>
            <div class="p-4 space-y-4">
                <div class="flex items-start gap-4 p-3 rounded-2xl hover:bg-slate-50 transition-colors">
                    <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center flex-shrink-0">
                        <i data-lucide="plus-circle" class="w-5 h-5 text-blue-600"></i>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-slate-800">Kopi Kenangan - Jakarta</p>
                        <p class="text-xs text-slate-500 font-medium">Menambahkan 24 stok baru pada produk "Espresso Beans"</p>
                        <p class="text-[10px] text-slate-400 font-bold mt-1 uppercase tracking-tighter">2 Menit yang lalu</p>
                    </div>
                </div>
                <div class="flex items-start gap-4 p-3 rounded-2xl hover:bg-slate-50 transition-colors">
                    <div class="w-10 h-10 rounded-full bg-amber-50 flex items-center justify-center flex-shrink-0">
                        <i data-lucide="refresh-cw" class="w-5 h-5 text-amber-600"></i>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-slate-800">Toko Berkah</p>
                        <p class="text-xs text-slate-500 font-medium">Memperbarui informasi profil toko & alamat pengiriman</p>
                        <p class="text-[10px] text-slate-400 font-bold mt-1 uppercase tracking-tighter">15 Menit yang lalu</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-slate-900 rounded-[2rem] p-8 text-white relative overflow-hidden">
            <div class="relative z-10">
                <h3 class="text-xl font-bold mb-2">Health System</h3>
                <p class="text-slate-400 text-sm mb-8 font-medium">Status server dan database pusat StokIn</p>
                
                <div class="space-y-6">
                    <div>
                        <div class="flex justify-between text-xs mb-2 font-bold uppercase tracking-widest text-slate-500">
                            <span>Server Load</span>
                            <span class="text-emerald-400">Optimal</span>
                        </div>
                        <div class="w-full bg-slate-800 h-1.5 rounded-full overflow-hidden">
                            <div class="bg-emerald-500 h-full w-[32%]"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-xs mb-2 font-bold uppercase tracking-widest text-slate-500">
                            <span>Storage Usage</span>
                            <span class="text-amber-400">64.2 GB / 128 GB</span>
                        </div>
                        <div class="w-full bg-slate-800 h-1.5 rounded-full overflow-hidden">
                            <div class="bg-amber-500 h-full w-[50%]"></div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex items-center gap-4">
                    <div class="flex -space-x-2">
                        <div class="w-8 h-8 rounded-full border-2 border-slate-900 bg-slate-700 flex items-center justify-center text-[10px] font-bold">JD</div>
                        <div class="w-8 h-8 rounded-full border-2 border-slate-900 bg-slate-700 flex items-center justify-center text-[10px] font-bold">AS</div>
                    </div>
                    <p class="text-[10px] font-bold text-slate-500 uppercase">2 Admin Online</p>
                </div>
            </div>
            <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-amber-500/10 rounded-full blur-3xl"></div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Growth Chart
        const ctxGrowth = document.getElementById('growthChart').getContext('2d');
        new Chart(ctxGrowth, {
            type: 'line',
            data: {
                labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
                datasets: [{
                    label: 'Toko Baru',
                    data: [12, 19, 15, 25, 22, 30, 45],
                    borderColor: '#f59e0b',
                    backgroundColor: 'rgba(245, 158, 11, 0.1)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3,
                    pointRadius: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { display: false },
                    x: { grid: { display: false }, ticks: { font: { size: 10, weight: '600' } } }
                }
            }
        });

        // Package Distribution Chart
        const ctxPackage = document.getElementById('packageChart').getContext('2d');
        new Chart(ctxPackage, {
            type: 'doughnut',
            data: {
                datasets: [{
                    data: [45, 30, 25],
                    backgroundColor: ['#f59e0b', '#3b82f6', '#e2e8f0'],
                    borderWidth: 0,
                    cutout: '80%'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } }
            }
        });
    });
</script>
@endsection