<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Subscription;
use App\Models\Toko;

class AdminNotificationService
{
    public function getNotifications(): array
    {
        $notifications = [];

        // Tampilkan langganan yang hampir habis
        $expiringSoon = $this->getExpiringSoonNotifications();
        $notifications = array_merge($notifications, $expiringSoon);

        // Tampilkan langganan yang baru saja expired
        $recentlyExpired = $this->getRecentlyExpiredNotifications();
        $notifications = array_merge($notifications, $recentlyExpired);

        // Tampilkan pembayaran yang pending atau overdue
        $pendingPayments = $this->getPendingPaymentNotifications();
        $notifications = array_merge($notifications, $pendingPayments);

        // Urutkan berdasarkan prioritas 
        usort($notifications, function ($a, $b) {
            return $b['priority'] <=> $a['priority'];
        });

        return $notifications;
    }

    public function getExpiringSoonNotifications(): array
    {
        $notifications = [];

        $expiringSubscriptions = Subscription::with(['toko', 'plan'])
            ->whereIn('status', ['trial', 'active'])
            ->where('expires_at', '>', now())
            ->where('expires_at', '<=', now()->addDays(7))
            ->latestPerToko()
            ->get();

        foreach ($expiringSubscriptions as $subscription) {
            $daysLeft = $subscription->daysRemaining();
            $notifications[] = [
                'type' => 'langganan_hampir_habis',
                'priority' => 2, // Warning
                'title' => 'Langganan Hampir Habis',
                'message' => "{$subscription->toko->name} - sisa {$daysLeft} hari ({$subscription->plan->name})",
                'icon' => 'fa-hourglass-half',
                'color' => 'orange',
                'link' => route('admin.pelanggan.show', $subscription->toko_id),
                'toko_id' => $subscription->toko_id,
            ];
        }

        return $notifications;
    }

    public function getRecentlyExpiredNotifications(): array
    {
        $notifications = [];

        $expiredSubscriptions = Subscription::with(['toko', 'plan'])
            ->where('status', 'expired')
            ->where('expires_at', '>=', now()->subDays(3))
            ->where('expires_at', '<', now())
            ->latestPerToko()
            ->get();

        foreach ($expiredSubscriptions as $subscription) {
            $notifications[] = [
                'type' => 'langganan_expired',
                'priority' => 3, // Critical
                'title' => 'Langganan Expired',
                'message' => "{$subscription->toko->name} - langganan {$subscription->plan->name} telah berakhir",
                'icon' => 'fa-times-circle',
                'color' => 'red',
                'link' => route('admin.pelanggan.show', $subscription->toko_id),
                'toko_id' => $subscription->toko_id,
            ];
        }

        return $notifications;
    }

    public function getPendingPaymentNotifications(): array
    {
        $notifications = [];

        $pendingPayments = Payment::with(['subscription.toko'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($pendingPayments as $payment) {
            $tokoName = $payment->subscription?->toko?->name ?? 'Unknown';
            $isOverdue = $payment->isOverdue();

            $notifications[] = [
                'type' => $isOverdue ? 'pembayaran_overdue' : 'pembayaran_pending',
                'priority' => $isOverdue ? 3 : 2, // Critical if overdue, Warning if pending
                'title' => $isOverdue ? 'Pembayaran Jatuh Tempo' : 'Pembayaran Menunggu',
                'message' => "{$tokoName} - {$payment->formatted_amount}",
                'icon' => $isOverdue ? 'fa-exclamation-circle' : 'fa-clock',
                'color' => $isOverdue ? 'red' : 'orange',
                'link' => route('admin.keuangan.show', $payment->id),
                'payment_id' => $payment->id,
            ];
        }

        return $notifications;
    }

    public function getNotificationCount(): int
    {
        return count($this->getNotifications());
    }

    public function getCriticalCount(): int
    {
        $notifications = $this->getNotifications();
        return count(array_filter($notifications, fn($n) => $n['priority'] === 3));
    }
}
