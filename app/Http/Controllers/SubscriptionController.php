<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Services\MidtransService;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    protected $midtransService;

    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }

    /**
     * Show subscription plans (pricing page)
     */
    public function plans()
    {
        $plans = SubscriptionPlan::where('is_active', true)->get();
        
        return view('subscription.plans', compact('plans'));
    }

    /**
     * Show current subscription status
     */
    public function index()
    {
        $user = auth()->user();
        $toko = $user->toko;
        
        if (!$toko) {
            return redirect()->route('dashboard')->with('error', 'Anda belum memiliki toko.');
        }

        $subscription = $toko->activeSubscription;
        $plans = SubscriptionPlan::where('is_active', true)->get();

        return view('subscription.index', compact('subscription', 'plans', 'toko'));
    }

    /**
     * Show checkout page for a plan
     */
    public function checkout(string $planSlug)
    {
        $user = auth()->user();
        $toko = $user->toko;
        
        if (!$toko) {
            return redirect()->route('dashboard')->with('error', 'Anda belum memiliki toko.');
        }

        $plan = SubscriptionPlan::where('slug', $planSlug)->where('is_active', true)->firstOrFail();

        // If free plan, create trial directly
        if ($plan->isFree()) {
            return $this->createTrialSubscription($toko, $plan);
        }

        // Create or get pending subscription for this toko
        $subscription = Subscription::updateOrCreate(
            [
                'toko_id' => $toko->id,
                'status' => 'pending',
            ],
            [
                'plan_id' => $plan->id,
                'starts_at' => now(),
                'expires_at' => now()->addDays($plan->duration_days),
            ]
        );

        // Get snap token
        $snapData = $this->midtransService->createSnapToken($subscription, $plan);

        return view('subscription.checkout', [
            'subscription' => $subscription,
            'plan' => $plan,
            'snapToken' => $snapData['snap_token'],
            'clientKey' => $this->midtransService->getClientKey(),
            'isProduction' => $this->midtransService->isProduction(),
        ]);
    }

    /**
     * Create trial subscription
     */
    protected function createTrialSubscription($toko, $plan)
    {
        // Check if already had trial
        $existingTrial = Subscription::where('toko_id', $toko->id)
            ->where('status', 'trial')
            ->exists();

        if ($existingTrial) {
            return redirect()->route('subscription.index')
                ->with('error', 'Anda sudah pernah menggunakan trial.');
        }

        Subscription::create([
            'toko_id' => $toko->id,
            'plan_id' => $plan->id,
            'status' => 'trial',
            'starts_at' => now(),
            'expires_at' => now()->addDays($plan->duration_days),
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'Selamat! Trial ' . $plan->duration_days . ' hari Anda sudah aktif.');
    }

    /**
     * Handle callback from Midtrans (redirect after payment)
     */
    public function callback(Request $request)
    {
        $orderId = $request->get('order_id');
        $transactionStatus = $request->get('transaction_status');

        if ($transactionStatus === 'settlement' || $transactionStatus === 'capture') {
            // Activate subscription (for localhost where webhook doesn't work)
            $this->activateSubscriptionByOrderId($orderId);
            
            return redirect()->route('subscription.index')
                ->with('success', 'Pembayaran berhasil! Langganan Anda sudah aktif.');
        }

        if ($transactionStatus === 'pending') {
            return redirect()->route('subscription.index')
                ->with('info', 'Pembayaran sedang diproses. Silakan selesaikan pembayaran.');
        }

        return redirect()->route('subscription.index')
            ->with('error', 'Pembayaran gagal atau dibatalkan.');
    }

    /**
     * Activate subscription by order ID (used in callback for localhost)
     */
    protected function activateSubscriptionByOrderId($orderId)
    {
        if (!$orderId) return;

        $payment = \App\Models\Payment::where('order_id', $orderId)->first();
        
        if ($payment && $payment->status !== 'success') {
            $payment->update([
                'status' => 'success',
                'paid_at' => now(),
            ]);

            $subscription = $payment->subscription;
            $plan = $subscription->plan;

            $subscription->update([
                'status' => 'active',
                'starts_at' => now(),
                'expires_at' => now()->addDays($plan->duration_days),
            ]);
        }
    }

    /**
     * Handle webhook notification from Midtrans
     */
    public function webhook(Request $request)
    {
        $result = $this->midtransService->handleNotification();

        return response()->json($result);
    }

    /**
     * Show expired subscription page
     */
    public function expired()
    {
        $plans = SubscriptionPlan::where('is_active', true)->where('price', '>', 0)->get();
        
        return view('subscription.expired', compact('plans'));
    }
}
