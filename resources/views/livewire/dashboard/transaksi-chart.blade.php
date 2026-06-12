<div x-data="transaksiChart(@js($chartData))" x-init="$nextTick(() => init())" wire:ignore.self
    class="p-5 bg-white border border-gray-200 rounded-2xl shadow-sm">

    {{-- Header & Tombol Filter --}}
    <div class="flex items-start justify-between mb-5">
        <div>
            <h1 class="text-xl font-bold text-gray-900">Grafik Penjualan</h1>
            <p class="text-sm text-gray-500 mt-0.5">Transaksi & total pendapatan</p>
        </div>

        {{-- ① Tombol Filter Periode --}}
        <div class="inline-flex rounded-lg shadow-sm">
            @foreach (['weekly' => 'Minggu', 'monthly' => 'Bulan', 'yearly' => 'Tahun'] as $val => $label)
                <button wire:click="$set('period', '{{ $val }}')"
                    class="px-4 py-2 text-sm font-medium border transition-all
                        {{ $loop->first ? 'rounded-s-lg' : '' }}
                        {{ $loop->last ? 'rounded-e-lg' : '' }}
                        {{ $period === $val
                            ? 'bg-orange-500 text-white border-orange-500'
                            : 'bg-white text-gray-700 border-gray-200 hover:bg-gray-50' }}">
                    {{ $label }}
                </button>
            @endforeach
        </div>
    </div>

    {{-- Legend --}}
    <div class="flex gap-4 mb-4">
        <div class="flex items-center gap-1.5">
            <span class="w-3 h-3 rounded-full bg-orange-500 inline-block"></span>
            <span class="text-sm text-gray-500">Transaksi</span>
        </div>
        <div class="flex items-center gap-1.5">
            <span class="w-3 h-3 rounded-full bg-blue-500 inline-block"></span>
            <span class="text-sm text-gray-500">Penjualan (Rp)</span>
        </div>
    </div>

    {{-- ② Canvas Chart.js — wire:ignore mencegah Livewire merusak canvas --}}
    <div wire:ignore>
        <canvas id="transaksiChart" height="130"></canvas>
    </div>

    {{-- ③ Listener event dari Livewire → Alpine.js --}}
    <span x-on:transaksi-chart-updated.window="updateChart($event.detail.data)"></span>
</div>

@script
    <script>
        Alpine.data('transaksiChart', (initialData) => ({
            chart: null,

            // ④ Inisialisasi Chart.js saat komponen dimuat pertama kali
            init() {
                const canvas = document.getElementById('transaksiChart');
                if (!canvas) {
                    console.error('Canvas transaksiChart tidak ditemukan');
                    return;
                }

                const ctx = canvas.getContext('2d');

                // Destroy chart lama jika ada (cegah duplikasi)
                if (this.chart) {
                    this.chart.destroy();
                }

                this.chart = new Chart(ctx, {
                    type: 'bar',
                    data: this.buildDataset(initialData),
                    options: {
                        responsive: true,
                        interaction: {
                            mode: 'index',
                            intersect: false
                        },
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: (ctx) => {
                                        if (ctx.dataset.label === 'Penjualan') {
                                            return ' Rp ' + ctx.raw.toLocaleString('id-ID');
                                        }
                                        return ` ${ctx.raw} transaksi`;
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: '#f3f4f6'
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });
            },

            // ⑤ Susun struktur dataset Chart.js
            buildDataset(data) {
                return {
                    labels: data.labels,
                    datasets: [{
                            label: 'Transaksi',
                            data: data.transaksi,
                            borderColor: '#f97316',
                            backgroundColor: 'rgba(249,115,22,0.08)',
                            tension: 0.4,
                            fill: true,
                        },
                        {
                            label: 'Penjualan',
                            data: data.penjualan,
                            borderColor: '#3b82f6',
                            backgroundColor: 'rgba(59,130,246,0.08)',
                            tension: 0.4,
                            fill: true,
                        }
                    ]
                };
            },

            // ⑥ Update chart saat event diterima dari Livewire
            updateChart(data) {
                this.chart.data = this.buildDataset(data);
                this.chart.update();
            }
        }));
    </script>
@endscript
