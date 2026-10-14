<div>
    <div class="max-w-4xl mx-auto space-y-6">

        {{-- Header Breadcrumb / Title --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-bell text-amber-500"></i> Pengaturan Notifikasi Email
                </h1>
                <p class="text-sm text-gray-500 mt-1">
                    Kelola preferensi dan jam pengiriman notifikasi stok menipis untuk toko <strong>{{ $toko_name }}</strong>.
                </p>
            </div>
            <div>
                <a href="{{ route('notifications.index') }}" wire:navigate
                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors shadow-sm">
                    <i class="fas fa-list text-gray-400"></i> Lihat Riwayat Notifikasi
                </a>
            </div>
        </div>

        {{-- Flash Messages --}}
        @if (session()->has('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-800 text-sm flex items-center gap-3">
                <i class="fas fa-check-circle text-emerald-500 text-lg"></i>
                <div>{{ session('success') }}</div>
            </div>
        @endif

        @if (session()->has('test_success'))
            <div class="p-4 bg-blue-50 border border-blue-200 rounded-xl text-blue-800 text-sm flex items-center gap-3">
                <i class="fas fa-paper-plane text-blue-500 text-lg"></i>
                <div>{{ session('test_success') }}</div>
            </div>
        @endif

        @if (session()->has('test_error'))
            <div class="p-4 bg-rose-50 border border-rose-200 rounded-xl text-rose-800 text-sm flex items-center gap-3">
                <i class="fas fa-exclamation-triangle text-rose-500 text-lg"></i>
                <div>{{ session('test_error') }}</div>
            </div>
        @endif

        {{-- Main Settings Form --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8 space-y-6">
            <form wire:submit.prevent="save" class="space-y-6">

                {{-- Status & Toggle --}}
                <div class="flex items-start justify-between pb-6 border-b border-gray-100">
                    <div class="pr-4">
                        <label for="notif-toggle" class="text-base font-semibold text-gray-900 cursor-pointer">
                            Aktifkan Notifikasi Email
                        </label>
                        <p class="text-sm text-gray-500 mt-0.5">
                            Jika dinonaktifkan, Anda tidak akan menerima peringatan stok habis atau laporan stok harian melalui email.
                        </p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer flex-shrink-0">
                        <input type="checkbox" id="notif-toggle" wire:model.live="notif_email_enabled" class="sr-only peer">
                        <div class="w-12 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-amber-500"></div>
                    </label>
                </div>

                {{-- Recipient Info Box --}}
                <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-envelope text-lg"></i>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Email Tujuan Notifikasi</p>
                            <p class="text-sm font-bold text-gray-800">{{ $recipient_email ?? 'Belum diatur' }}</p>
                        </div>
                    </div>
                </div>

                {{-- Schedule Time --}}
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-gray-800">
                        Jam Pengiriman Laporan Harian (Daily Digest)
                    </label>
                    <p class="text-xs text-gray-500">
                        Setiap hari pada jam ini, sistem akan merangkum seluruh barang yang stoknya menipis atau habis dalam satu email.
                    </p>
                    <div class="relative max-w-xs">
                        <input type="time" wire:model.defer="notif_digest_time"
                            class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-gray-700 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400 @error('notif_digest_time') border-red-400 @enderror">
                    </div>
                    @error('notif_digest_time')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Trigger Strategy Explanation --}}
                <div class="p-4 bg-amber-50/60 border border-amber-200/70 rounded-xl space-y-2 text-xs text-gray-700">
                    <div class="font-bold text-amber-900 flex items-center gap-2 text-sm">
                        <i class="fas fa-shield-alt text-amber-600"></i> Strategi Notifikasi Anti-Spam
                    </div>
                    <ul class="list-disc list-inside space-y-1 text-gray-600 pl-1">
                        <li><strong>🔴 Stok Habis (0 unit):</strong> Email instan langsung dikirim setelah transaksi.</li>
                        <li><strong>🟡 Stok Menipis (≤ Stok Minimum):</strong> Masuk antrian laporan harian pada jam di atas.</li>
                        <li><strong>🛡️ Cooldown 24 Jam:</strong> Setiap barang tidak akan mengirim spam lebih dari 1 kali dalam 24 jam.</li>
                    </ul>
                </div>

                {{-- Actions --}}
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 pt-4 border-t border-gray-100">
                    <button type="button" wire:click="sendTestEmail" wire:loading.attr="disabled"
                        class="px-4 py-2.5 rounded-xl border border-gray-300 text-gray-700 hover:bg-gray-50 font-medium text-sm transition-all flex items-center justify-center gap-2 disabled:opacity-50">
                        <span wire:loading.remove wire:target="sendTestEmail">
                            <i class="fas fa-paper-plane text-amber-500"></i> Kirim Test Email
                        </span>
                        <span wire:loading wire:target="sendTestEmail" class="flex items-center gap-2">
                            <i class="fas fa-spinner fa-spin text-amber-500"></i> Mengirim...
                        </span>
                    </button>

                    <button type="submit" wire:loading.attr="disabled"
                        class="px-6 py-2.5 bg-amber-500 hover:bg-amber-600 active:scale-95 text-white font-semibold text-sm rounded-xl transition-all shadow-md shadow-amber-500/20 flex items-center justify-center gap-2">
                        <span wire:loading.remove wire:target="save">
                            <i class="fas fa-save"></i> Simpan Pengaturan
                        </span>
                        <span wire:loading wire:target="save" class="flex items-center gap-2">
                            <i class="fas fa-spinner fa-spin"></i> Menyimpan...
                        </span>
                    </button>
                </div>
            </form>
        </div>

        {{-- Recent Notification Logs --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-base font-bold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-history text-gray-400"></i> Log Pengiriman Terakhir
                </h3>
                <span class="text-xs text-gray-400">Audit trail sistem email</span>
            </div>

            @if($recentLogs->isEmpty())
                <div class="text-center py-8 text-gray-400 text-sm">
                    <i class="fas fa-inbox text-3xl mb-2 text-gray-300 block"></i>
                    Belum ada riwayat email notifikasi yang tercatat.
                </div>
            @else
                <div class="divide-y divide-gray-100">
                    @foreach($recentLogs as $log)
                        <div class="py-3 flex items-center justify-between gap-4 text-sm">
                            <div class="flex items-center gap-3 min-w-0">
                                <span class="w-2.5 h-2.5 rounded-full flex-shrink-0 
                                    @if($log->status === 'sent') bg-emerald-500 
                                    @elseif($log->status === 'failed') bg-rose-500 
                                    @else bg-gray-400 @endif">
                                </span>
                                <div class="truncate">
                                    <p class="font-medium text-gray-800 truncate">
                                        @if($log->type === 'critical_stock')
                                            Stok Habis: {{ $log->barang?->nama_barang ?? 'Barang' }}
                                        @elseif($log->type === 'daily_digest')
                                            Laporan Stok Harian (Digest)
                                        @elseif($log->type === 'test_email')
                                            Uji Coba Pengiriman Notifikasi
                                        @else
                                            {{ ucfirst(str_replace('_', ' ', $log->type)) }}
                                        @endif
                                    </p>
                                    <p class="text-xs text-gray-400 truncate">
                                        Ke: {{ $log->recipient }} &bull; {{ $log->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex-shrink-0">
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold
                                    @if($log->status === 'sent') bg-emerald-100 text-emerald-800
                                    @elseif($log->status === 'failed') bg-rose-100 text-rose-800
                                    @else bg-gray-100 text-gray-700 @endif">
                                    {{ ucfirst($log->status) }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>
</div>
