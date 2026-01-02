<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use App\Models\Subscription;
use Illuminate\Http\Request;

class AdminPaketController extends Controller
{
    /**
     * Display a listing of subscription plans.
     */
    public function index()
    {
        $plans = SubscriptionPlan::withCount([
            'subscriptions as active_count' => function ($q) {
                $q->whereIn('status', ['active', 'trial'])
                  ->where('expires_at', '>', now());
            }
        ])->get();

        // Summary stats
        $totalPlans = $plans->count();
        $totalActiveSubscriptions = Subscription::whereIn('status', ['active', 'trial'])
            ->where('expires_at', '>', now())
            ->count();

        return view('admin.kelola-paket.index', compact('plans', 'totalPlans', 'totalActiveSubscriptions'));
    }

    /**
     * Show the form for editing the specified plan.
     */
    public function edit(SubscriptionPlan $plan)
    {
        return view('admin.kelola-paket.edit', compact('plan'));
    }

    /**
     * Update the specified plan in storage.
     */
    public function update(Request $request, SubscriptionPlan $plan)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'max_users' => 'required|integer|min:-1',
            'export_report' => 'boolean',
            'priority_support' => 'boolean',
            'analytics_dashboard' => 'boolean',
            'description' => 'nullable|string|max:255',
        ]);

        $features = [
            'max_products' => -1, // always unlimited
            'max_transactions' => -1, // always unlimited
            'max_users' => (int) $request->max_users,
            'export_report' => (bool) $request->export_report,
            'priority_support' => (bool) $request->priority_support,
            'analytics_dashboard' => (bool) $request->analytics_dashboard,
            'description' => $request->description,
        ];

        $plan->update([
            'name' => $request->name,
            'price' => $request->price,
            'duration_days' => $request->duration_days,
            'features' => $features,
        ]);

        return redirect()->route('admin.kelola-paket.index')
            ->with('success', 'Paket berhasil diperbarui.');
    }

    /**
     * Toggle the active status of a plan.
     */
    public function toggleStatus(SubscriptionPlan $plan)
    {
        $plan->update(['is_active' => !$plan->is_active]);

        $status = $plan->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->route('admin.kelola-paket.index')
            ->with('success', "Paket {$plan->name} berhasil {$status}.");
    }
}

