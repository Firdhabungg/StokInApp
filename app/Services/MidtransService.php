<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$clientKey = config('midtrans.client_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    /**
     * Create Snap Token for payment
     */
    public function createSnapToken(Subscription $subscription, SubscriptionPlan $plan): array
    {
        $orderId = 'SUB-' . $subscription->id . '-' . time();
        
        // Create payment record
        $payment = Payment::create([
            'subscription_id' => $subscription->id,
            'order_id' => $orderId,
            'amount' => $plan->price,
            'status' => 'pending',
        ]);

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $plan->price,
            ],
            'customer_details' => [
                'first_name' => $subscription->toko->owner?->name ?? $subscription->toko->name,
                'email' => $subscription->toko->email,
                'phone' => $subscription->toko->phone ?? '',
            ],
            'item_details' => [
                [
                    'id' => $plan->slug,
                    'price' => $plan->price,
                    'quantity' => 1,
                    'name' => 'Paket ' . $plan->name . ' - ' . $plan->duration_days . ' Hari',
                ],
            ],
            'callbacks' => [
                'finish' => route('subscription.callback'),
            ],
        ];

        $snapToken = Snap::getSnapToken($params);

        return [
            'snap_token' => $snapToken,
            'order_id' => $orderId,
            'payment_id' => $payment->id,
        ];
    }

    /**
     * Handle notification from Midtrans webhook
     */
    public function handleNotification(): array
    {
        $notification = new Notification();
        
        $orderId = $notification->order_id;
        $transactionStatus = $notification->transaction_status;
        $fraudStatus = $notification->fraud_status ?? null;

        $payment = Payment::where('order_id', $orderId)->first();

        if (!$payment) {
            return ['success' => false, 'message' => 'Payment not found'];
        }

        $payment->midtrans_response = json_decode(json_encode($notification), true);

        if ($transactionStatus == 'capture') {
            if ($fraudStatus == 'accept') {
                $payment->status = 'success';
                $payment->paid_at = now();
                $this->activateSubscription($payment);
            }
        } elseif ($transactionStatus == 'settlement') {
            $payment->status = 'success';
            $payment->paid_at = now();
            $this->activateSubscription($payment);
        } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
            $payment->status = 'failed';
        } elseif ($transactionStatus == 'pending') {
            $payment->status = 'pending';
        }

        $payment->save();

        return ['success' => true, 'status' => $payment->status];
    }

    /**
     * Activate subscription after successful payment
     */
    protected function activateSubscription(Payment $payment): void
    {
        $subscription = $payment->subscription;
        $plan = $subscription->plan;

        $subscription->update([
            'status' => 'active',
            'starts_at' => now(),
            'expires_at' => now()->addDays($plan->duration_days),
        ]);
    }

    /**
     * Get Midtrans Client Key for frontend
     */
    public function getClientKey(): string
    {
        return config('midtrans.client_key');
    }

    /**
     * Check if production mode
     */
    public function isProduction(): bool
    {
        return config('midtrans.is_production');
    }
}
