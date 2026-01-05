@extends('admin.layouts.app')

@section('header_title', 'Dashboard Admin')
@section('header_description', 'Analisis Platform StokIn')

@section('content')

@php
/*
|--------------------------------------------------------------------------
| FALLBACK DATA (ANTI ERROR)
|--------------------------------------------------------------------------
*/

$paketLabels = $paketLabels ?? [];
$paketData   = $paketData ?? [];
$totalPaket  = $totalPaket ?? 0;

/*
|--------------------------------------------------------------------------
| FIX COLLECTION / ARRAY (ANTI ERROR 100%)
|--------------------------------------------------------------------------
*/
$growthLabelsArr = collect($growthLabels ?? [])->toArray();
$growthDataArr   = collect($growthData ?? [])->toArray();
$paketLabelsArr  = collect($paketLabels)->toArray();
$paketDataArr    = collect($paketData)->toArray();
@endphp

<div class="space-y-6">

    {{-- ================= SUMMARY ================= --}}
    @php
    $cards = [
        [
            'icon'  => 'boxes-stacked',
            'color' => 'blue',
            'title' => 'Total Produk',
            'value' => $totalProduk
        ],
        [
            'icon'  => 'store',
            'color' => 'amber',
            'title' => 'Total Toko',
            'value' => $totalToko
        ],
        [
            'icon'  => 'credit-card',
            'color' => 'emerald',
            'title' => 'Omzet Platform',
            'value' => $omzetFormatted,
            'sub'   => $omzetStatus
        ],
    ];
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
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

                @isset($c['sub'])
                <p class="text-xs font-semibold text-gray-500 mt-1">
                    {{ $c['sub'] }}
                </p>
                @endisset
            </div>
        </div>
        @endforeach
    </div>

    {{-- ================= CHART ================= --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Pendapatan Bulanan --}}
        <div
            id="growthChartWrapper"
            data-labels="{{ implode(',', $growthLabelsArr) }}"
            data-values="{{ implode(',', $growthDataArr) }}"
            class="lg:col-span-2 bg-white rounded-xl p-6 shadow-sm">

            <h3 class="text-lg font-bold mb-1">Pendapatan Bulanan</h3>
            <p class="text-xs text-gray-400 mb-6">12 bulan terakhir</p>

            {{-- Skeleton --}}
            <div id="growthSkeleton" class="h-[260px] animate-pulse bg-gray-100 rounded-lg"></div>
            <canvas id="growthChart" class="hidden"></canvas>
        </div>

        {{-- Distribusi Paket --}}
        <div
            id="packageChartWrapper"
            data-labels="{{ implode(',', $paketLabelsArr) }}"
            data-values="{{ implode(',', $paketDataArr) }}"
            class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">

            <h3 class="text-lg font-bold mb-6">Distribusi Paket</h3>

            {{-- Skeleton --}}
            <div id="packageSkeleton" class="h-[220px] animate-pulse bg-gray-100 rounded-lg"></div>
            <canvas id="packageChart" class="hidden"></canvas>
        </div>

    </div>

    {{-- ================= INSIGHT PLATFORM ================= --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">

        <div class="p-6 border-b border-gray-100">
            <h3 class="text-lg font-bold text-gray-900">Insight & Alert Platform</h3>
            <p class="text-xs text-gray-400 mt-1">
                Ringkasan kondisi SaaS
            </p>
        </div>

        <div class="p-6 space-y-4 text-sm">

            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center">
                    <i class="fas fa-clock"></i>
                </div>
                <p class="text-gray-700">
                    <b>{{ $tokoTidakAktif }}</b> toko tidak aktif ≥ 14 hari
                </p>
            </div>

            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                    <i class="fas fa-credit-card"></i>
                </div>
                <p class="text-gray-700">
                    <b>{{ $langgananHampirHabis }}</b> langganan akan berakhir ≤ 3 hari
                </p>
            </div>

            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <i class="fas fa-flask"></i>
                </div>
                <p class="text-gray-700">
                    <b>{{ $trialAktif }}</b> akun trial aktif
                </p>
            </div>

        </div>
    </div>

</div>

{{-- ================= SCRIPT ================= --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {

    /* ===== Pendapatan Bulanan ===== */
    const growthWrapper = document.getElementById('growthChartWrapper');

    if (growthWrapper) {
        const labels = growthWrapper.dataset.labels.split(',');
        const values = growthWrapper.dataset.values.split(',').map(Number);

        document.getElementById('growthSkeleton').classList.add('hidden');
        const canvas = document.getElementById('growthChart');
        canvas.classList.remove('hidden');

        new Chart(canvas, {
            type: 'line',
            data: {
                labels,
                datasets: [{
                    data: values,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 0,
                    backgroundColor: 'rgba(99,102,241,.15)'
                }]
            },
            options: {
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } }
            }
        });
    }

    /* ===== Distribusi Paket ===== */
    const packageWrapper = document.getElementById('packageChartWrapper');

    if (packageWrapper) {
        const labels = packageWrapper.dataset.labels.split(',');
        const values = packageWrapper.dataset.values.split(',').map(Number);

        const colors = labels.map(label => {
            label = label.toLowerCase();
            if (label.includes('pro')) return '#f59e0b';
            if (label.includes('trial')) return '#10b981';
            return '#3b82f6';
        });

        document.getElementById('packageSkeleton').classList.add('hidden');
        const canvas = document.getElementById('packageChart');
        canvas.classList.remove('hidden');

        new Chart(canvas, {
            type: 'doughnut',
            data: {
                labels,
                datasets: [{
                    data: values,
                    backgroundColor: colors,
                    cutout: '75%'
                }]
            },
            options: {
                plugins: { legend: { display: false } }
            }
        });
    }
});
</script>

@endsection
