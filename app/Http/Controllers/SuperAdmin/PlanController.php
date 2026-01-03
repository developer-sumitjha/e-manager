<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PlanController extends Controller
{
    public function index()
    {
        $plans = SubscriptionPlan::withCount(['tenants', 'subscriptions'])
            ->orderBy('sort_order')
            ->get();
        
        return view('super-admin.plans.index', compact('plans'));
    }

    public function create()
    {
        return view('super-admin.plans.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price_monthly' => 'required|numeric|min:0',
            'price_yearly' => 'nullable|numeric|min:0',
            'max_orders_per_month' => 'required|integer|min:0',
            'max_products' => 'required|integer|min:0',
            'max_users' => 'required|integer|min:0',
            'max_storage_gb' => 'required|integer|min:0',
            'trial_days' => 'required|integer|min:0',
            'is_active' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['plan_id'] = 'PLAN-' . strtoupper(Str::random(8));
        $validated['currency'] = $request->input('currency', 'NPR');
        // Ensure unchecked checkboxes persist as false
        $validated['is_active'] = $request->has('is_active');
        $validated['is_featured'] = $request->has('is_featured');

        // Feature flags (checkboxes)
        foreach ([
            'has_inventory','has_manual_delivery','has_logistics_integration','has_accounting','has_analytics',
            'has_api_access','has_multi_user','has_custom_domain','has_priority_support'
        ] as $flag) {
            $validated[$flag] = $request->has($flag);
        }

        SubscriptionPlan::create($validated);

        return redirect()->route('super.plans.index')->with('success', 'Plan created successfully');
    }

    public function edit(SubscriptionPlan $plan)
    {
        return view('super-admin.plans.edit', compact('plan'));
    }

    public function update(Request $request, SubscriptionPlan $plan)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price_monthly' => 'required|numeric|min:0',
            'price_yearly' => 'nullable|numeric|min:0',
            'max_orders_per_month' => 'required|integer|min:0',
            'max_products' => 'required|integer|min:0',
            'max_users' => 'required|integer|min:0',
            'max_storage_gb' => 'required|integer|min:0',
            'trial_days' => 'required|integer|min:0',
            'is_active' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);
        // Keep slug in sync with name
        $validated['slug'] = Str::slug($validated['name']);
        $validated['currency'] = $request->input('currency', $plan->currency ?? 'NPR');
        // Explicitly set booleans from checkboxes
        $validated['is_active'] = $request->has('is_active');
        $validated['is_featured'] = $request->has('is_featured');

        foreach ([
            'has_inventory','has_manual_delivery','has_logistics_integration','has_accounting','has_analytics',
            'has_api_access','has_multi_user','has_custom_domain','has_priority_support'
        ] as $flag) {
            $validated[$flag] = $request->has($flag);
        }

        $plan->update($validated);

        return redirect()->route('super.plans.index')->with('success', 'Plan updated successfully');
    }

    public function destroy(SubscriptionPlan $plan)
    {
        // Check if plan has active subscriptions
        $subscriptionsCount = \App\Models\Subscription::where('plan_id', $plan->id)->count();
        
        if ($subscriptionsCount > 0) {
            return redirect()->back()->with('error', 'Cannot delete plan. This plan has ' . $subscriptionsCount . ' active subscription(s). Please reassign or cancel them first.');
        }

        // Check if plan has tenants
        if ($plan->tenants()->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete plan with active tenants. Please reassign tenants to another plan first.');
        }

        $plan->delete();

        return redirect()->route('super.plans.index')->with('success', 'Plan deleted successfully');
    }

    /**
     * Toggle plan active status (safer alternative to deletion)
     */
    public function toggleStatus(SubscriptionPlan $plan)
    {
        $plan->update(['is_active' => !$plan->is_active]);
        
        $status = $plan->is_active ? 'activated' : 'deactivated';
        
        return redirect()->back()->with('success', "Plan {$status} successfully");
    }
}


