<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SubscriptionPlan;

class SubscriptionPlansSeeder extends Seeder
{
    public function run()
    {
        $plans = [
            [
                'plan_id' => 'PLAN-FREE',
                'name' => 'Free',
                'slug' => 'free',
                'description' => 'Perfect for getting started with basic features',
                'price_monthly' => 0,
                'price_yearly' => 0,
                'max_orders_per_month' => 50,
                'max_products' => 25,
                'max_users' => 1,
                'max_storage_gb' => 1,
                'has_inventory' => true,
                'has_manual_delivery' => false,
                'has_logistics_integration' => false,
                'has_accounting' => false,
                'has_analytics' => false,
                'has_api_access' => false,
                'has_multi_user' => false,
                'has_custom_domain' => false,
                'has_priority_support' => false,
                'trial_days' => 14,
                'is_active' => true,
                'is_featured' => false,
                'sort_order' => 1,
            ],
            [
                'plan_id' => 'PLAN-STARTER',
                'name' => 'Starter',
                'slug' => 'starter',
                'description' => 'For growing businesses with essential tools',
                'price_monthly' => 2500,
                'price_yearly' => 25000,
                'max_orders_per_month' => 500,
                'max_products' => 200,
                'max_users' => 3,
                'max_storage_gb' => 5,
                'has_inventory' => true,
                'has_manual_delivery' => true,
                'has_logistics_integration' => false,
                'has_accounting' => false,
                'has_analytics' => true,
                'has_api_access' => false,
                'has_multi_user' => true,
                'has_custom_domain' => false,
                'has_priority_support' => false,
                'trial_days' => 14,
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 2,
            ],
            [
                'plan_id' => 'PLAN-PROFESSIONAL',
                'name' => 'Professional',
                'slug' => 'professional',
                'description' => 'For established businesses needing advanced features',
                'price_monthly' => 5000,
                'price_yearly' => 50000,
                'max_orders_per_month' => 2000,
                'max_products' => 1000,
                'max_users' => 10,
                'max_storage_gb' => 20,
                'has_inventory' => true,
                'has_manual_delivery' => true,
                'has_logistics_integration' => true,
                'has_accounting' => true,
                'has_analytics' => true,
                'has_api_access' => true,
                'has_multi_user' => true,
                'has_custom_domain' => false,
                'has_priority_support' => true,
                'trial_days' => 14,
                'is_active' => true,
                'is_featured' => false,
                'sort_order' => 3,
            ],
            [
                'plan_id' => 'PLAN-ENTERPRISE',
                'name' => 'Enterprise',
                'slug' => 'enterprise',
                'description' => 'For large-scale operations with unlimited potential',
                'price_monthly' => 10000,
                'price_yearly' => 100000,
                'max_orders_per_month' => 10000,
                'max_products' => 5000,
                'max_users' => 50,
                'max_storage_gb' => 100,
                'has_inventory' => true,
                'has_manual_delivery' => true,
                'has_logistics_integration' => true,
                'has_accounting' => true,
                'has_analytics' => true,
                'has_api_access' => true,
                'has_multi_user' => true,
                'has_custom_domain' => true,
                'has_priority_support' => true,
                'trial_days' => 30,
                'is_active' => true,
                'is_featured' => false,
                'sort_order' => 4,
            ],
        ];

        foreach ($plans as $plan) {
            SubscriptionPlan::updateOrCreate(
                ['plan_id' => $plan['plan_id']],
                $plan
            );
        }

        $this->command->info('✅ Created 4 subscription plans');
    }
}
