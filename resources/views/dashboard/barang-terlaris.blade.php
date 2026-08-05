<div x-data="barangTerlaris(@js($chartData))" x-init="$nextTick(() => init())" wire:ignore.self
    class="p-5 bg-white border border-gray-200 rounded-2xl shadow-sm">
    <div class="flex items-start justify-between mb-3">
        <div>
            <h1 class="text-xl font-bold text-gray-900">Barang Terlaris</h1>
            <p class="text-sm text-gray-500 mt-0.5">Top 7 produk terjual</p>
        </div>
        <div class="inline-flex rounded-lg shadow-sm">
            @foreach (['weekly' => 'Minggu', 'monthly' => 'Bulan', 'yearly' => 'Tahun'] as $val => $label)
                <button type="button" wire:click="$set('period', '{{ $val }}')"
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

    <div wire:ignore class="relative" style="height: 220px;">
        <canvas id="barangTerlarisChart"></canvas>
    </div>

    <span x-on:barang-terlaris-updated.window="updateChart($event.detail.data)"></span>
</div>

@script
    <script>
        Alpine.data('barangTerlaris', (initialData) => ({
            chart: null,
            init() {
                const canvas = document.getElementById('barangTerlarisChart');
                if (!canvas) return;
                if (this.chart) this.chart.destroy();

                this.chart = new Chart(canvas.getContext('2d'), {
                    type: 'pie',
                    data: this.buildDataset(initialData),
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'right',
                                labels: {
                                    boxWidth: 12,
                                    padding: 10,
                                    font: {
                                        size: 11
                                    }
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: (ctx) => ` ${ctx.label}: ${ctx.raw} pcs`
                                }
                            }
                        }
                    }
                });
            },
            buildDataset(data) {
                return {
                    labels: data.labels,
                    datasets: [{
                        data: data.data,
                        backgroundColor: data.colors,
                        borderWidth: 2,
                        borderColor: '#fff',
                    }]
                };
            },
            updateChart(data) {
                this.chart.data = this.buildDataset(data);
                this.chart.update();
            }
        }));
    </script>
@endscript
