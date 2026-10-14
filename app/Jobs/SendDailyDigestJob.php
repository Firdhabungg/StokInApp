<?php

namespace App\Jobs;

use App\Models\Barang;
use App\Models\Toko;
use App\Services\LowStockNotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class SendDailyDigestJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 1;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     * Dijalankan oleh Laravel Scheduler setiap pagi (lihat routes/console.php).
     */
    public function handle(LowStockNotificationService $notifService): void
    {
        Log::info('[DailyDigestJob] Mulai mengirim daily digest stok...');

        // Ambil semua toko yang notifikasi emailnya diaktifkan
        $tokos = Toko::where('notif_email_enabled', true)->get();

        foreach ($tokos as $toko) {
            try {
                // Ambil semua barang dengan stok habis atau menipis
                $barangs = Barang::where('toko_id', $toko->id)
                    ->whereIn('status', ['habis', 'menipis'])
                    ->orderByRaw("CASE status WHEN 'habis' THEN 0 ELSE 1 END") // habis duluan
                    ->orderBy('stok', 'asc')
                    ->get();

                if ($barangs->isEmpty()) {
                    Log::info("[DailyDigestJob] Toko #{$toko->id} ({$toko->name}): tidak ada barang bermasalah, skip.");
                    continue;
                }

                $notifService->sendDailyDigest($toko, $barangs);
            } catch (\Throwable $e) {
                // Jangan hentikan proses toko lain jika satu toko gagal
                Log::error("[DailyDigestJob] Gagal memproses toko #{$toko->id}: {$e->getMessage()}");
            }
        }

        Log::info('[DailyDigestJob] Daily digest selesai.');
    }
}
