    @extends('layouts.dashboard')

    @section('title', 'Dashboard')
    @section('page-title', 'Dashboard')
    @section('page-description', 'Ringkasan dan overview sistem manajemen stok')

    @section('content')
        <!-- Alert Notification -->
        {{-- <div class="mb-6 p-4 bg-amber-50 border-l-4 border-amber-500 rounded-r-lg flex items-start gap-3">
        <div class="w-8 h-8 bg-amber-500 rounded-full flex items-center justify-center flex-shrink-0">
            <i class="fas fa-exclamation text-white text-sm"></i>
        </div>
        <div>
            <h4 class="font-semibold text-amber-800">Perhatian!</h4>
            <p class="text-sm text-amber-700">Ada 3 notifikasi penting yang memerlukan perhatian Anda.</p>
        </div>
    </div> --}}

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <!-- Total Stok Barang -->
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-sm text-gray-500">Total Stok Barang</span>
                    <i class="fas fa-boxes text-amber-500"></i>
                </div>
                <p class="text-3xl font-bold text-gray-900">442</p>
                <p class="text-sm text-gray-400 mt-1">8 jenis produk</p>
            </div>

            <!-- Penjualan Hari Ini -->
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-sm text-gray-500">Penjualan Hari Ini</span>
                    <i class="fas fa-chart-line text-green-500"></i>
                </div>
                <p class="text-3xl font-bold text-gray-900">Rp 606k</p>
                <p class="text-sm text-green-500 mt-1"><i class="fas fa-arrow-up mr-1"></i>+12% dari kemarin</p>
            </div>

            <!-- Stok Menipis -->
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-sm text-gray-500">Stok Menipis</span>
                    <i class="fas fa-exclamation-triangle text-orange-500"></i>
                </div>
                <p class="text-3xl font-bold text-gray-900">2</p>
                <p class="text-sm text-orange-500 mt-1">Perlu restock segera</p>
            </div>

            <!-- Mendekati Kadaluarsa -->
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-sm text-gray-500">Mendekati Kadaluarsa</span>
                    <i class="fas fa-clock text-red-500"></i>
                </div>
                <p class="text-3xl font-bold text-gray-900">2</p>
                <p class="text-sm text-red-500 mt-1">Dalam 30 hari</p>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Tren Penjualan & Pembelian -->
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <h3 class="font-semibold text-gray-900 mb-1">Tren Penjualan & Pembelian</h3>
                <p class="text-sm text-gray-500 mb-4">Data 7 bulan terakhir</p>
                <div class="h-64">
                    <canvas id="salesChart"></canvas>
                </div>
                <div class="flex items-center justify-center gap-6 mt-4 text-sm">
                    <span class="flex items-center gap-2"><span class="w-3 h-3 bg-blue-500 rounded-full"></span>
                        Keuntungan</span>
                    <span class="flex items-center gap-2"><span class="w-3 h-3 bg-green-500 rounded-full"></span>
                        Pembelian</span>
                    <span class="flex items-center gap-2"><span class="w-3 h-3 bg-amber-500 rounded-full"></span>
                        Penjualan</span>
                </div>
            </div>

            <!-- Distribusi Penjualan per Kategori -->
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <h3 class="font-semibold text-gray-900 mb-1">Distribusi Penjualan per Kategori</h3>
                <p class="text-sm text-gray-500 mb-4">Berdasarkan nilai transaksi</p>
                <div class="h-64 flex items-center justify-center">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Bottom Cards -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Stok Menipis -->
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <h3 class="font-semibold text-gray-900 mb-1">Stok Menipis</h3>
                <p class="text-sm text-gray-500 mb-4">Perlu segera direstock</p>
                <div class="space-y-3">
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div>
                            <p class="font-medium text-gray-900">Aqua 600ml</p>
                            <p class="text-xs text-gray-500">Stok: 45 item</p>
                        </div>
                        <span
                            class="px-3 py-1 bg-orange-100 text-orange-600 text-xs font-medium rounded-full">Menipis</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div>
                            <p class="font-medium text-gray-900">Teh Pucuk 350ml</p>
                            <p class="text-xs text-gray-500">Stok: 23 item</p>
                        </div>
                        <span class="px-3 py-1 bg-red-100 text-red-600 text-xs font-medium rounded-full">Kritis</span>
                    </div>
                </div>
            </div>

            <!-- Mendekati Kadaluarsa -->
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <h3 class="font-semibold text-gray-900 mb-1">Mendekati Kadaluarsa</h3>
                <p class="text-sm text-gray-500 mb-4">Prioritas penjualan</p>
                <div class="space-y-3">
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div>
                            <p class="font-medium text-gray-900">Teh Pucuk 350ml</p>
                            <p class="text-xs text-gray-500">6 hari lagi</p>
                        </div>
                        <span class="px-3 py-1 bg-red-100 text-red-600 text-xs font-medium rounded-full">5 Nov</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div>
                            <p class="font-medium text-gray-900">Susu Dancow 400g</p>
                            <p class="text-xs text-gray-500">15 hari lagi</p>
                        </div>
                        <span class="px-3 py-1 bg-orange-100 text-orange-600 text-xs font-medium rounded-full">15 Nov</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div>
                            <p class="font-medium text-gray-900">Indomie Goreng</p>
                            <p class="text-xs text-gray-500">42 hari lagi</p>
                        </div>
                        <span class="px-3 py-1 bg-gray-100 text-gray-600 text-xs font-medium rounded-full">31 Des</span>
                    </div>
                </div>
            </div>

            <!-- Transaksi Terbaru -->
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <h3 class="font-semibold text-gray-900 mb-1">Transaksi Terbaru</h3>
                <p class="text-sm text-gray-500 mb-4">5 transaksi terakhir</p>
                <div class="space-y-3">
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div>
                            <p class="font-medium text-gray-900">PJL-20251027</p>
                            <p class="text-xs text-gray-500">U-001 • 27 Okt, 09:15</p>
                        </div>
                        <span class="font-semibold text-green-600">Rp 50k</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div>
                            <p class="font-medium text-gray-900">BLI-20251028</p>
                            <p class="text-xs text-gray-500">-001 • 14 Okt, 11:30</p>
                        </div>
                        <span class="font-semibold text-red-600">Rp 550k</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div>
                            <p class="font-medium text-gray-900">PJL-20251027</p>
                            <p class="text-xs text-gray-500">U-002 • 29 Okt, 11:45</p>
                        </div>
                        <span class="font-semibold text-green-600">Rp 128k</span>
                    </div>
                </div>
            </div>
        </div>
    @endsection

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Sales & Purchases Trend Chart
            const salesCtx = document.getElementById('salesChart').getContext('2d');
            new Chart(salesCtx, {
                type: 'line',
                data: {
                    labels: ['Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt'],
                    datasets: [{
                            label: 'Keuntungan',
                            data: [30000000, 45000000, 35000000, 50000000, 40000000, 60000000, 55000000, 70000000],
                            borderColor: '#3B82F6',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            tension: 0.4,
                            fill: true
                        },
                        {
                            label: 'Pembelian',
                            data: [20000000, 30000000, 25000000, 35000000, 30000000, 40000000, 35000000, 45000000],
                            borderColor: '#22C55E',
                            backgroundColor: 'transparent',
                            tension: 0.4
                        },
                        {
                            label: 'Penjualan',
                            data: [50000000, 75000000, 60000000, 85000000, 70000000, 100000000, 90000000,
                                115000000
                            ],
                            borderColor: '#F59E0B',
                            backgroundColor: 'transparent',
                            tension: 0.4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return (value / 1000000) + 'jt';
                                }
                            }
                        }
                    }
                }
            });

            // Category Distribution Pie Chart
            const categoryCtx = document.getElementById('categoryChart').getContext('2d');
            new Chart(categoryCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Makanan 35%', 'Minuman 20%', 'Kebutuhan Rumah 20%', 'Dapur 15%', 'Lainnya 5%',
                        'Kebersihan 5%'
                    ],
                    datasets: [{
                        data: [35, 20, 20, 15, 5, 5],
                        backgroundColor: [
                            '#22C55E',
                            '#3B82F6',
                            '#EAB308',
                            '#EF4444',
                            '#8B5CF6',
                            '#EC4899'
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                            labels: {
                                boxWidth: 12,
                                padding: 15
                            }
                        }
                    }
                }
            });
        </script>
    @endpush
