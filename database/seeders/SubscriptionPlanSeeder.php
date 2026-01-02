<?php

namespace Database\Seeders;

use App\Models\SubscriptionPlan;
use Illuminate\Database\Seeder;

class SubscriptionPlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Free Trial',
                'slug' => 'free',
                'price' => 0,
                'duration_days' => 14,
                'features' => [
                    'max_products' => -1, // unlimited
                    'max_transactions' => -1, // unlimited
                    'max_users' => 2, // max 2 user (owner + 1 kasir)
                    'export_report' => false,
                    'priority_support' => false,
                    'analytics_dashboard' => false,
                    'description' => 'Coba gratis 14 hari',
                ],
                'is_active' => true,
            ],
            [
                'name' => 'Pro',
                'slug' => 'pro',
                'price' => 149000,
                'duration_days' => 30,
                'features' => [
                    'max_products' => -1, // unlimited
                    'max_transactions' => -1, // unlimited
                    'max_users' => -1, // unlimited
                    'export_report' => true,
                    'priority_support' => true,
                    'analytics_dashboard' => true,
                    'description' => 'Fitur lengkap untuk bisnis Anda',
                    'badge' => 'Rekomendasi',
                ],
                'is_active' => true,
            ],
        ];

        foreach ($plans as $plan) {
            SubscriptionPlan::updateOrCreate(
                ['slug' => $plan['slug']],
                $plan
            );
        }
    }
}
