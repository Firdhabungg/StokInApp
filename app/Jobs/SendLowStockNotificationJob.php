<?php

namespace App\Jobs;

use App\Models\Barang;
use App\Services\LowStockNotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class SendLowStockNotificationJob implements ShouldQueue
{
    use Queueable;

    /**
     * Jumlah maksimal percobaan ulang jika job gagal.
     */
    public int $tries = 3;

    /**
     * Jeda (detik) antar percobaan ulang: 60, 120, 240 detik.
     */
    public array $backoff = [60, 120, 240];

    /**
     * Create a new job instance.
     */
    public function __construct(public readonly int $barangId)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(LowStockNotificationService $notifService): void
    {
        // Reload data barang terbaru dari DB (stok bisa saja sudah berubah)
        $barang = Barang::with('toko')->find($this->barangId);

        if (! $barang) {
            Log::warning("[LowStockJob] Barang #{$this->barangId} tidak ditemukan, job dihentikan.");
            return;
        }

        $toko = $barang->toko;

        if (! $toko) {
            Log::warning("[LowStockJob] Toko untuk barang #{$this->barangId} tidak ditemukan, job dihentikan.");
            return;
        }

        // Cek apakah notifikasi diaktifkan dan cooldown belum berlaku
        if (! $notifService->shouldSendNotification($barang, $toko)) {
            Log::info("[LowStockJob] Notifikasi untuk barang #{$barang->id} ({$barang->nama_barang}) dilewati (disabled atau cooldown aktif).");
            return;
        }

        // Stok habis total → kirim email langsung (critical alert)
        if ($barang->isOutOfStock()) {
            Log::info("[LowStockJob] Stok habis untuk barang #{$barang->id}, mengirim critical alert...");
            $notifService->sendCriticalAlert($barang, $toko);
            return;
        }

        // Stok menipis → tandai saja, akan masuk dalam daily digest
        if ($barang->isLowStock()) {
            Log::info("[LowStockJob] Stok menipis untuk barang #{$barang->id}, ditandai untuk daily digest.");
            $notifService->markAsNotified($barang);
        }
    }
}
