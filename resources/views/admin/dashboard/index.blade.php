@extends('admin.layouts.app')

@section('header_title', 'Dashboard Admin')
@section('header_description', 'Analisis Real-time Performa StokIn')

@section('content')

@php
    /*
    |--------------------------------------------------------------------------
    | FALLBACK DATA (ANTI ERROR)
    |--------------------------------------------------------------------------
    */

    // Distribusi paket
    $paketLabels = $paketLabels ?? [];
    $paketData   = $paketData ?? [];
    $totalPaket  = $totalPaket ?? 0;

    // Log aktivitas
    $activityLogs = $activityLogs ?? [];

    // Kesehatan sistem
    $cpu             = $system['cpu'] ?? 0;
    $storageUsed     = $system['storage_used'] ?? 0;
    $storageTotal    = $system['storage_total'] ?? 0;
    $storagePercent  = $system['storage_percent'] ?? 0;
@endphp

<div class="space-y-6">

    {{-- ================= SUMMARY ================= --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        @php
            $cards = [
                ['icon'=>'boxes-stacked','color'=>'blue','title'=>'Total Produk','value'=>$totalProduk ?? 0],
                ['icon'=>'store','color'=>'amber','title'=>'Total Toko','value'=>$totalToko ?? 0],
                ['icon'=>'triangle-exclamation','color'=>'rose','title'=>'Stok Menipis','value'=>$stokMenipis ?? 0],
                ['icon'=>'credit-card','color'=>'emerald','title'=>'Pertumbuhan Omzet','value'=>($pertumbuhanOmzet ?? 0).'%'],
            ];
        @endphp

        @foreach ($cards as $c)
            <div class="bg-white rounded-xl p-6 shadow-sm flex items-center gap-4">
                <div class="p-3 bg-{{ $c['color'] }}-50 text-{{ $c['color'] }}-600 rounded-xl">
                    <i class="fas fa-{{ $c['icon'] }}"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                        {{ $c['title'] }}
                    </p>
                    <h3 class="text-2xl font-extrabold text-gray-900">
                        {{ $c['value'] }}
                    </h3>
                </div>
            </div>
        @endforeach
    </div>

    {{-- ================= CHART ================= --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Pertumbuhan Langganan --}}
        <div class="lg:col-span-2 bg-white rounded-xl p-6 shadow-sm">
            <h3 class="text-lg font-bold mb-1">Pertumbuhan Langganan</h3>
            <p class="text-xs text-gray-400 mb-6">7 hari terakhir</p>
            <canvas id="growthChart"></canvas>
        </div>

        {{-- Distribusi Paket --}}
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <h3 class="text-lg font-bold mb-6">Distribusi Paket</h3>

            <div class="h-[220px] flex justify-center">
                <canvas id="packageChart"></canvas>
            </div>

            <div class="mt-6 space-y-3 text-xs">
                @forelse ($paketLabels as $i => $planName)
                    @php
                        $jumlah = $paketData[$i];
                        $persen = $totalPaket > 0
                            ? round(($jumlah / $totalPaket) * 100)
                            : 0;

                        $color = match (strtolower($planName)) {
                            'pro'      => '#f59e0b',
                            'premium' => '#10b981',
                            default   => '#3b82f6',
                        };
                    @endphp

                    <div class="flex justify-between items-center font-semibold text-gray-600">
                        <span class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full"
                                style="background-color: {{ $color }}"></span>
                            {{ $planName }}
                        </span>

                        <span class="font-bold text-gray-900">
                            {{ $persen }}%
                        </span>
                    </div>
                @empty
                    <p class="text-center text-gray-400">
                        Belum ada langganan
                    </p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ================= LOG & SYSTEM ================= --}}
    <div class="">

        {{-- Log Aktivitas --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">

            {{-- Header --}}
            <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Log Aktivitas Toko</h3>
                    <p class="text-xs text-gray-400 mt-1">Transaksi terbaru</p>
                </div>

                <div class="flex gap-2">
                    <button id="prevLog"
                        class="px-3 py-1 text-sm border rounded-lg hover:bg-gray-50">
                        ‹
                    </button>
                    <button id="nextLog"
                        class="px-3 py-1 text-sm border rounded-lg hover:bg-gray-50">
                        ›
                    </button>
                </div>
            </div>

            {{-- List --}}
            <div id="logContainer" class="p-4 space-y-4">
                @foreach ($activityLogs as $index => $sale)
                    <div class="log-item {{ $index >= 5 ? 'hidden' : '' }}">
                        <div class="flex gap-4 p-3 rounded-xl hover:bg-gray-50 transition">
                            <div
                                class="w-10 h-10 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center">
                                <i class="fas fa-cart-shopping"></i>
                            </div>

                            <div class="flex-1">
                                <p class="text-sm font-bold text-gray-900">
                                    {{ $sale->toko->name ?? 'Toko tidak diketahui' }}
                                </p>

                                <p class="text-xs text-gray-500">
                                    {{ $sale->user->name ?? 'Kasir' }}
                                    • Rp {{ number_format($sale->total, 0, ',', '.') }}
                                </p>

                                <p class="text-[10px] text-gray-400 font-bold mt-1 uppercase">
                                    {{ $sale->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
</div>

{{-- ================= SCRIPT ================= --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const growthLabels = @json($growthLabels ?? []);
    const growthData   = @json($growthData ?? []);
    const paketLabels = @json($paketLabels ?? []);
    const paketData   = @json($paketData ?? []);

    if (document.getElementById('growthChart')) {
        new Chart(growthChart, {
            type: 'line',
            data: {
                labels: growthLabels,
                datasets: [{
                    data: growthData,
                    backgroundColor: 'rgba(245,158,11,.15)',
                    fill: true,
                    tension: .4,
                    pointRadius: 0
                }]
            },
            options: { plugins:{ legend:{ display:false }}}
        });
    }

    if (document.getElementById('packageChart')) {

        const paketLabels = @json($paketLabels ?? []);
        const paketData   = @json($paketData ?? []);

        const colors = paketLabels.map(label => {
            label = label.toLowerCase();
            if (label.includes('pro')) return '#f59e0b';
            if (label.includes('premium')) return '#10b981';
            return '#3b82f6';
        });

        new Chart(packageChart, {
            type: 'doughnut',
            data: {
                labels: paketLabels,
                datasets: [{
                    data: paketData,
                    backgroundColor: colors,
                    cutout: '75%'
                }]
            },
            options: {
                plugins: {
                    legend: { display: false }
                }
            }
        });
    }
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const logs = document.querySelectorAll('.log-item');
    const perPage = 5;
    let page = 0;
    const totalPage = Math.ceil(logs.length / perPage);

    function render() {
        logs.forEach((el, i) => {
            el.classList.toggle(
                'hidden',
                i < page * perPage || i >= (page + 1) * perPage
            );
        });
    }

    document.getElementById('nextLog').addEventListener('click', () => {
        if (page < totalPage - 1) {
            page++;
            render();
        }
    });

    document.getElementById('prevLog').addEventListener('click', () => {
        if (page > 0) {
            page--;
            render();
        }
    });

    render();
});
</script>

@endsection
