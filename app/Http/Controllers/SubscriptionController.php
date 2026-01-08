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
        $hasUsedFreeTrial = $toko->hasUsedFreeTrial();

        return view('subscription.index', compact('subscription', 'plans', 'toko', 'hasUsedFreeTrial'));
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
    
        // Perbaikan: Jika paket gratis, langsung alihkan ke fungsi trial tanpa Midtrans
        if ($plan->isFree() || $plan->price <= 0) {
            return $this->createTrialSubscription($toko, $plan);
        }
    
        // Tetap buat data pending untuk paket berbayar
        $subscription = Subscription::updateOrCreate(
            [
                'toko_id' => $toko->id,
                'status' => 'pending',
            ],
            [
                'plan_id' => $plan->id,
                'starts_at' => now(),
                'expires_at' => now()->addDays((int) $plan->duration_days),
            ]
        );
    
        // Ambil snap token untuk pembayaran berbayar
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
        // Periksa apakah toko sedang memiliki langganan yang benar-benar aktif
        if ($toko->activeSubscription && $toko->activeSubscription->status === 'active') {
            return redirect()->route('subscription.index')
                ->with('error', 'Anda masih memiliki langganan aktif.');
        }
    
        // Periksa apakah sudah pernah menggunakan jatah trial
        if ($toko->hasUsedFreeTrial()) {
            return redirect()->route('subscription.index')
                ->with('error', 'Anda sudah pernah menggunakan jatah trial sebelumnya.');
        }
    
        // Buat langganan baru dengan proteksi tipe data (int)
        Subscription::create([
            'toko_id' => $toko->id,
            'plan_id' => $plan->id,
            'status' => 'active', // Set aktif agar bisa langsung digunakan
            'starts_at' => now(),
            'expires_at' => now()->addDays((int) $plan->duration_days),
        ]);
    
        return redirect()->route('dashboard')
            ->with('success', 'Selamat! Paket ' . $plan->name . ' Anda sudah aktif.');
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
                'expires_at' => now()->addDays((int) $plan->duration_days),
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
