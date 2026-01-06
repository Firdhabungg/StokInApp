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
                    'description' => 'Coba gratis 14 hari',
                    'max_kasir' => 1, // 1 owner + 1 kasir
                    'export_report' => false,
                ],
                'is_active' => true,
            ],
            [
                'name' => 'Pro',
                'slug' => 'pro',
                'price' => 149000,
                'duration_days' => 30,
                'features' => [
                    'description' => 'Fitur lengkap untuk bisnis Anda',
                    'badge' => 'Rekomendasi',
                    'max_kasir' => -1, // unlimited kasir
                    'export_report' => true,
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
