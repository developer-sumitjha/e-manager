<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RebuildPlansSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        // Deactivate all existing plans (keep for history)
        \App\Models\SubscriptionPlan::query()->update(['is_active' => false]);

        // Deactivate Free/Starter plans safely (FKs exist), hide from selection
        \App\Models\SubscriptionPlan::whereIn('slug', ['free', 'starter'])->update(['is_active' => false, 'sort_order' => 99]);

        // Package 1: Limited (orders/users/storage)
        \App\Models\SubscriptionPlan::updateOrCreate(
            ['slug' => 'standard'],
            [
                'plan_id' => 'PLAN-STD',
                'name' => 'Standard',
                'description' => 'Limited plan with capped orders, users and storage. All features included.',
                'price_monthly' => 1500,
                'price_yearly' => 12000,
                'trial_days' => 60,
                'max_orders_per_month' => 1000,
                'max_products' => 500,
                'max_users' => 5,
                'max_storage_gb' => 5,
                'is_active' => true,
                'sort_order' => 1,
                'updated_at' => $now,
            ]
        );

        // Package 2: Pro (unlimited orders & users, 20GB storage)
        \App\Models\SubscriptionPlan::updateOrCreate(
            ['slug' => 'pro'],
            [
                'plan_id' => 'PLAN-PRO',
                'name' => 'Pro',
                'description' => 'Unlimited orders and users with 20GB storage. All features included.',
                'price_monthly' => 2500,
                'price_yearly' => 24000,
                'trial_days' => 60,
                'max_orders_per_month' => 999999,
                'max_products' => 100000,
                'max_users' => 99999,
                'max_storage_gb' => 20,
                'is_active' => true,
                'sort_order' => 2,
                'updated_at' => $now,
            ]
        );
    }
}


