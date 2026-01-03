<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class LandingController extends Controller
{
    public function index()
    {
        return view('public.landing');
    }

    public function signup()
    {
        $plans = SubscriptionPlan::where('is_active', true)
            ->orderBy('sort_order')
            ->get();
        
        return view('public.signup', compact('plans'));
    }

    public function pricing()
    {
        $plans = SubscriptionPlan::where('is_active', true)
            ->orderBy('sort_order')
            ->get();
        
        return view('public.pricing', compact('plans'));
    }

    public function submitSignup(Request $request)
    {
        $validated = $request->validate([
            'business_name' => 'required|string|max:255',
            'business_email' => 'required|email|unique:tenants,business_email',
            'business_phone' => 'nullable|string|max:20',
            'business_address' => 'nullable|string|max:255',
            'owner_name' => 'required|string|max:255',
            'owner_email' => 'required|email|unique:users,email',
            'owner_phone' => 'nullable|string|max:20',
            'subdomain' => 'required|string|alpha_dash|unique:tenants,subdomain|min:3|max:20',
            'password' => 'required|string|min:8|confirmed',
            'plan_id' => 'required|exists:subscription_plans,id',
        ]);

        $plan = SubscriptionPlan::findOrFail($validated['plan_id']);

        try {
            // Generate unique tenant_id
            do {
                $tenantId = 'TEN-' . strtoupper(Str::random(8));
            } while (Tenant::where('tenant_id', $tenantId)->exists());

            // Create tenant (admin customer)
            $tenant = Tenant::create([
                'tenant_id' => $tenantId,
                'business_name' => $validated['business_name'],
                'business_email' => $validated['business_email'],
                'business_phone' => $validated['business_phone'] ?? null,
                'business_address' => $validated['business_address'] ?? null,
                'owner_name' => $validated['owner_name'],
                'owner_email' => $validated['owner_email'],
                'owner_phone' => $validated['owner_phone'] ?? null,
                'subdomain' => $validated['subdomain'],
                'password' => Hash::make($validated['password']),
                'current_plan_id' => $plan->id,
                'status' => 'pending',
                'max_orders' => $plan->max_orders_per_month,
                'max_products' => $plan->max_products,
                'max_users' => $plan->max_users,
                'max_storage' => $plan->max_storage_gb,
            ]);

            // Create admin user account
            $user = User::create([
                'tenant_id' => $tenant->id,
                'name' => $validated['owner_name'],
                'email' => $validated['owner_email'],
                'phone' => $validated['owner_phone'] ?? null,
                'password' => Hash::make($validated['password']),
                'role' => 'admin',
                'is_active' => true,
            ]);

            // Create subscription record (pending until payment/approval)
            \App\Models\Subscription::create([
                'subscription_id' => 'SUB-' . strtoupper(Str::random(8)),
                'tenant_id' => $tenant->id,
                'plan_id' => $plan->id,
                'billing_cycle' => 'monthly',
                'amount' => $plan->price_monthly,
                'currency' => 'NPR',
                'status' => 'pending',
                'starts_at' => now(),
                'ends_at' => now()->addMonth(),
                'trial_ends_at' => $plan->trial_days ? now()->addDays($plan->trial_days) : null,
            ]);

            return redirect()->route('vendor.login')->with('success', 'Account created. Awaiting approval/payment. You will be notified once active.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Failed to create account. Please try again.')->withInput();
        }
    }
}


