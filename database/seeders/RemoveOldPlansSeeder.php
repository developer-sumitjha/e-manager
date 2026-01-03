<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RemoveOldPlansSeeder extends Seeder
{
    public function run(): void
    {
        $oldPlans = \App\Models\SubscriptionPlan::whereIn('slug', ['free', 'starter'])->get();
        if ($oldPlans->isEmpty()) {
            return; // nothing to remove
        }

        $standard = \App\Models\SubscriptionPlan::where('slug', 'standard')->first();
        if (!$standard) {
            // fallback to any active plan
            $standard = \App\Models\SubscriptionPlan::where('is_active', true)->first();
        }

        $oldPlanIds = $oldPlans->pluck('id')->all();

        if ($standard) {
            // Reassign existing subscriptions and tenants to Standard (or first active) plan
            \App\Models\Subscription::whereIn('plan_id', $oldPlanIds)->update(['plan_id' => $standard->id]);
            \App\Models\Tenant::whereIn('current_plan_id', $oldPlanIds)->update(['current_plan_id' => $standard->id]);
        } else {
            // If no standard exists, set to NULL to break FKs before delete (only if nullable). If not nullable, skip reassignment.
            try { \App\Models\Subscription::whereIn('plan_id', $oldPlanIds)->update(['plan_id' => null]); } catch (\Throwable $e) {}
            try { \App\Models\Tenant::whereIn('current_plan_id', $oldPlanIds)->update(['current_plan_id' => null]); } catch (\Throwable $e) {}
        }

        // Now safe to delete old plans
        \App\Models\SubscriptionPlan::whereIn('id', $oldPlanIds)->delete();
    }
}







