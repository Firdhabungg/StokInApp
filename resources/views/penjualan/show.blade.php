<div>

    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">

            {{-- Header: Toko Info --}}
            <div class="text-center border-b border-gray-200 pb-4 mb-4">
                <h1 class="text-2xl font-bold text-gray-900">
                    {{ $sale->toko->name }}
                </h1>
                <p class="text-gray-500 text-sm">{{ $sale->toko->address }}</p>
                <p class="text-gray-500 text-sm">Telp: {{ $sale->toko->phone }}</p>
            </div>

            {{-- Transaction Info --}}
            <div class="grid grid-cols-2 gap-3 mb-4 text-sm">
                <div>
                    <p class="text-gray-500">Kode Transaksi</p>
                    <p class="font-mono font-semibold text-gray-900">{{ $sale->kode_transaksi }}</p>
                </div>

                <div class="text-right">
                    <p class="text-gray-500">Tanggal</p>
                    <p class="font-semibold text-gray-900">
                        {{ $sale->tanggal->format('d/m/Y H:i') }}
                    </p>
                </div>

                <div>
                    <p class="text-gray-500">Kasir</p>
                    <p class="font-semibold text-gray-900">{{ $sale->user->name }}</p>
                </div>

                <div class="text-right">
                    <p class="text-gray-500">Status</p>
                    <span
                        class="inline-flex px-2.5 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
                        {{ ucfirst($sale->status) }}
                    </span>
                </div>

                <div>
                    <p class="text-gray-500">Metode Pembayaran</p>
                    <p class="font-semibold uppercase text-gray-900">
                        {{ $sale->metode_pembayaran }}
                    </p>
                </div>
            </div>

            {{-- Items Table --}}
            <div class="border border-gray-200 rounded-xl overflow-hidden mb-4">
                <table class="w-full text-sm">
                    <thead class="bg-green-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Barang</th>
                            <th class="px-4 py-3 text-center font-semibold text-gray-700">Qty</th>
                            <th class="px-4 py-3 text-right font-semibold text-gray-700">Harga</th>
                            <th class="px-4 py-3 text-right font-semibold text-gray-700">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sale->items as $item)
                            <tr class="border-t border-gray-100">
                                <td class="px-4 py-3">
                                    <p class="font-medium text-gray-900">
                                        {{ $item->barang->nama_barang }}
                                    </p>
                                    <p class="text-xs text-gray-400 font-mono">
                                        {{ $item->barang->kode_barang }}
                                    </p>
                                </td>
                                <td class="px-4 py-3 text-center text-gray-700">
                                    {{ $item->jumlah }}
                                </td>
                                <td class="px-4 py-3 text-right text-gray-700">
                                    Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-3 text-right font-semibold text-gray-900">
                                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-green-50 font-semibold border-t border-gray-200">
                        <tr>
                            <td colspan="3" class="px-4 py-3 text-right text-gray-700">Total</td>
                            <td class="px-4 py-3 text-right text-lg text-green-600">
                                Rp {{ number_format($sale->total, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            {{-- Payment Details (Cash) --}}
            @if ($sale->metode_pembayaran === 'cash')
                <div class="grid grid-cols-2 gap-4 text-sm mb-4">
                    <div>
                        <p class="text-gray-500">Uang Dibayar</p>
                        <p class="font-semibold text-gray-900">
                            Rp {{ number_format($sale->uang_dibayar, 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="text-gray-500">Kembalian</p>
                        <p class="font-semibold text-green-600">
                            Rp {{ number_format($sale->kembalian, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            @endif

            {{-- Keterangan --}}
            @if ($sale->keterangan)
                <div class="bg-gray-50 rounded-lg p-4 mb-4 border border-gray-100">
                    <p class="text-sm text-gray-500 mb-1">Keterangan</p>
                    <p class="text-gray-700 text-sm">{{ $sale->keterangan }}</p>
                </div>
            @endif

            {{-- Bukti Pembayaran --}}
            @if ($sale->bukti_pembayaran)
                <div class="mb-6">
                    <p class="text-sm text-gray-500 mb-2">Bukti Pembayaran</p>
                    <a href="{{ asset('storage/' . $sale->bukti_pembayaran) }}" target="_blank"
                        class="inline-flex items-start gap-4 group">
                        <img src="{{ asset('storage/' . $sale->bukti_pembayaran) }}" alt="Bukti Pembayaran"
                            class="w-32 h-auto rounded-lg border border-gray-200 shadow-sm group-hover:shadow-md transition cursor-zoom-in object-contain">
                        <p class="text-xs text-blue-600 mt-1 text-center group-hover:underline">Klik untuk melihat
                            ukuran penuh</p>
                    </a>
                </div>
            @endif

            {{-- Actions --}}
            <div class="flex gap-3 justify-between items-center pt-4 border-t border-gray-200">
                <a href="{{ route('penjualan.index') }}"
                    class="px-6 py-2.5 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white rounded-lg font-medium transition-all duration-300 shadow-sm">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>

                <div class="flex items-center gap-2">
                    <a href="{{ route('penjualan.create') }}"
                        class="px-6 py-2.5 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white rounded-lg font-medium transition-all duration-300 shadow-sm">
                        <i class="fas fa-plus mr-2"></i>Transaksi Baru
                    </a>
                    <button onclick="window.print()"
                        class="px-6 py-2.5 bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 text-white rounded-lg font-medium transition-all duration-300 shadow-sm">
                        <i class="fas fa-print mr-2"></i>Cetak
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Print Styles --}}
    <style>
        @media print {
            body * {
                visibility: hidden;
            }

            .max-w-3xl,
            .max-w-3xl * {
                visibility: visible;
            }

            .max-w-3xl {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }

            .flex.gap-3.justify-between {
                display: none !important;
            }
        }
    </style>
</div>
