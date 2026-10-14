<?php

namespace App\Services;

use App\Mail\CriticalStockMail;
use App\Mail\LowStockDigestMail;
use App\Models\Barang;
use App\Models\NotificationLogs;
use App\Models\Toko;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class LowStockNotificationService
{
    /**
     * Tentukan apakah barang ini berhak dikirimi notifikasi.
     */
    public function shouldSendNotification(Barang $barang, Toko $toko): bool
    {
        if (! $toko->notif_email_enabled) {
            return false;
        }

        return $barang->needsNotification();
    }

    /**
     * Kirim email instan ketika stok barang habis (0 unit).
     */
    public function sendCriticalAlert(Barang $barang, Toko $toko): void
    {
        $recipient = $toko->getNotifEmail();

        if (! $recipient) {
            Log::warning("[LowStockNotif] Toko #{$toko->id} tidak memiliki email owner, critical alert dilewati.");
            return;
        }

        try {
            Mail::to($recipient)->send(new CriticalStockMail($barang, $toko));

            $this->markAsNotified($barang);

            $this->log([
                'toko_id'   => $toko->id,
                'barang_id' => $barang->id,
                'type'      => 'critical_stock',
                'channel'   => 'email',
                'status'    => 'sent',
                'recipient' => $recipient,
                'payload'   => [
                    'barang'       => $barang->nama_barang,
                    'stok'         => $barang->stok,
                    'stok_minimum' => $barang->stok_minimum,
                ],
                'sent_at'   => now(),
            ]);

            Log::info("[LowStockNotif] Critical alert terkirim ke {$recipient} untuk barang #{$barang->id}");
        } catch (\Throwable $e) {
            $this->log([
                'toko_id'   => $toko->id,
                'barang_id' => $barang->id,
                'type'      => 'critical_stock',
                'channel'   => 'email',
                'status'    => 'failed',
                'recipient' => $recipient,
                'error'     => $e->getMessage(),
                'sent_at'   => now(),
            ]);

            Log::error("[LowStockNotif] Gagal mengirim critical alert: {$e->getMessage()}");
            throw $e;
        }
    }

    /**
     * Tandai waktu notifikasi terakhir (cooldown 24 jam).
     */
    public function markAsNotified(Barang $barang): void
    {
        $barang->low_stock_notified_at = now();
        $barang->saveQuietly();
    }

    /**
     * Reset cooldown saat restok.
     */
    public function resetNotification(Barang $barang): void
    {
        $barang->low_stock_notified_at = null;
        $barang->saveQuietly();
    }

    /**
     * Kirim email rangkuman harian (daily digest) per toko.
     */
    public function sendDailyDigest(Toko $toko, Collection $barangs): void
    {
        if ($barangs->isEmpty()) {
            return;
        }

        $recipient = $toko->getNotifEmail();

        if (! $recipient) {
            Log::warning("[LowStockNotif] Toko #{$toko->id} tidak memiliki email, digest dilewati.");
            return;
        }

        try {
            Mail::to($recipient)->send(new LowStockDigestMail($toko, $barangs));

            $this->log([
                'toko_id'   => $toko->id,
                'barang_id' => null,
                'type'      => 'daily_digest',
                'channel'   => 'email',
                'status'    => 'sent',
                'recipient' => $recipient,
                'payload'   => [
                    'total_habis'   => $barangs->where('status', 'habis')->count(),
                    'total_menipis' => $barangs->where('status', 'menipis')->count(),
                    'barang_ids'    => $barangs->pluck('id')->toArray(),
                ],
                'sent_at'   => now(),
            ]);

            Log::info("[LowStockNotif] Daily digest terkirim ke {$recipient} toko #{$toko->id}");
        } catch (\Throwable $e) {
            $this->log([
                'toko_id'   => $toko->id,
                'barang_id' => null,
                'type'      => 'daily_digest',
                'channel'   => 'email',
                'status'    => 'failed',
                'recipient' => $recipient,
                'error'     => $e->getMessage(),
                'sent_at'   => now(),
            ]);

            Log::error("[LowStockNotif] Gagal mengirim daily digest ke toko #{$toko->id}: {$e->getMessage()}");
        }
    }

    /**
     * Catat audit log pengiriman notifikasi.
     */
    private function log(array $data): void
    {
        try {
            NotificationLogs::create($data);
        } catch (\Throwable $e) {
            Log::error("[LowStockNotif] Gagal menyimpan log notifikasi: {$e->getMessage()}");
        }
    }
}
