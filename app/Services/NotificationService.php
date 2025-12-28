<?php

namespace App\Services;

use App\Models\Barang;
use App\Models\StockBatch;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class NotificationService
{
    /**
     * Get all notifications for the current toko.
     */
    public function getNotifications(int $tokoId): array
    {
        $notifications = [];

        // Get low stock notifications
        $lowStock = $this->getLowStockNotifications($tokoId);
        $notifications = array_merge($notifications, $lowStock);

        // Get near expiry notifications
        $nearExpiry = $this->getNearExpiryNotifications($tokoId);
        $notifications = array_merge($notifications, $nearExpiry);

        // Sort by priority (critical first)
        usort($notifications, function ($a, $b) {
            return $b['priority'] <=> $a['priority'];
        });

        return $notifications;
    }

    /**
     * Get low stock notifications.
     */
    public function getLowStockNotifications(int $tokoId): array
    {
        $notifications = [];

        // Barang dengan stok habis (critical)
        $stokHabis = Barang::where('toko_id', $tokoId)
            ->where('status', 'habis')
            ->get();

        foreach ($stokHabis as $barang) {
            $notifications[] = [
                'type' => 'stok_habis',
                'priority' => 3, // Critical
                'title' => 'Stok Habis',
                'message' => "{$barang->nama_barang} sudah habis stoknya",
                'icon' => 'fa-times-circle',
                'color' => 'red',
                'link' => route('stock.in.create'),
                'barang_id' => $barang->id,
            ];
        }

        // Barang dengan stok menipis (warning)
        $stokMenipis = Barang::where('toko_id', $tokoId)
            ->where('status', 'menipis')
            ->get();

        foreach ($stokMenipis as $barang) {
            $notifications[] = [
                'type' => 'stok_menipis',
                'priority' => 2, // Warning
                'title' => 'Stok Menipis',
                'message' => "{$barang->nama_barang} tersisa {$barang->stok} unit",
                'icon' => 'fa-exclamation-triangle',
                'color' => 'orange',
                'link' => route('stock.in.create'),
                'barang_id' => $barang->id,
            ];
        }

        return $notifications;
    }

    /**
     * Get near expiry notifications.
     */
    public function getNearExpiryNotifications(int $tokoId): array
    {
        $notifications = [];

        // Batch yang sudah kadaluarsa (critical)
        $kadaluarsa = StockBatch::with('barang')
            ->where('toko_id', $tokoId)
            ->where('status', 'kadaluarsa')
            ->where('jumlah_sisa', '>', 0)
            ->get();

        foreach ($kadaluarsa as $batch) {
            $notifications[] = [
                'type' => 'kadaluarsa',
                'priority' => 3, // Critical
                'title' => 'Sudah Kadaluarsa',
                'message' => "{$batch->barang->nama_barang} (Batch: {$batch->batch_code}) sudah kadaluarsa",
                'icon' => 'fa-skull-crossbones',
                'color' => 'red',
                'link' => route('stock.batch.index'),
                'batch_id' => $batch->id,
            ];
        }
 
        $hampirKadaluarsa = StockBatch::with('barang')
            ->where('toko_id', $tokoId)
            ->where('status', 'hampir_kadaluarsa')
            ->where('jumlah_sisa', '>', 0)
            ->get()
            ->map(function ($batch) {
                $daysLeft = round(now()->diffInDays($batch->tgl_kadaluarsa, false));
                return [
                    'batch' => $batch,
                    'daysLeft' => $daysLeft,
                ];
            })
            ->sortBy('daysLeft') 
            ->values();

        foreach ($hampirKadaluarsa as $item) {
            $batch = $item['batch'];
            $daysLeft = $item['daysLeft'];

            $notifications[] = [
                'type' => 'hampir_kadaluarsa',
                'priority' => 2,
                'title' => 'Hampir Kadaluarsa',
                'message' => "{$batch->barang->nama_barang} akan kadaluarsa dalam {$daysLeft} hari",
                'icon' => 'fa-clock',
                'color' => 'orange',
                'link' => route('stock.batch.index'),
                'batch_id' => $batch->id,
            ];
        }

        return $notifications;
    }

    /**
     * Get notification count (for badge).
     */
    public function getNotificationCount(int $tokoId): int
    {
        return count($this->getNotifications($tokoId));
    }

    /**
     * Get critical notification count.
     */
    public function getCriticalCount(int $tokoId): int
    {
        $notifications = $this->getNotifications($tokoId);
        return count(array_filter($notifications, fn($n) => $n['priority'] === 3));
    }
}
