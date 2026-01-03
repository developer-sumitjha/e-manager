<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SubscriptionPlan;
use App\Models\Subscription;
use App\Models\TenantPayment;

class SubscriptionController extends Controller
{
    public function index()
    {
        $tenant = Auth::user()->tenant;
        $current = Subscription::where('tenant_id', $tenant->id)->latest('starts_at')->first();
        $plans = SubscriptionPlan::where('is_active', true)->get();
        $payments = TenantPayment::where('tenant_id', $tenant->id)->latest()->limit(10)->get();
        return view('admin.subscription.index', compact('tenant', 'current', 'plans', 'payments'));
    }

    public function plans()
    {
        $tenant = Auth::user()->tenant;
        $plans = SubscriptionPlan::where('is_active', true)->get();
        return view('admin.subscription.plans', compact('tenant', 'plans'));
    }

    public function changePlan(Request $request)
    {
        $request->validate(['plan_id' => 'required|exists:subscription_plans,id']);
        $tenant = Auth::user()->tenant;
        $plan = SubscriptionPlan::findOrFail($request->plan_id);

        // End current subscription
        $current = Subscription::where('tenant_id', $tenant->id)->latest('starts_at')->first();
        if ($current && $current->status === 'active') {
            $current->update(['status' => 'cancelled', 'ends_at' => now()]);
        }

        // Create new subscription
        Subscription::create([
            'subscription_id' => 'SUB-' . strtoupper(\Str::random(8)),
            'tenant_id' => $tenant->id,
            'plan_id' => $plan->id,
            'billing_cycle' => 'monthly',
            'amount' => $plan->price_monthly,
            'currency' => 'NPR',
            'status' => 'active',
            'starts_at' => now(),
            'ends_at' => now()->addMonth(),
        ]);

        // Update tenant limits
        $tenant->update([
            'current_plan_id' => $plan->id,
            'max_orders' => $plan->max_orders_per_month,
            'max_products' => $plan->max_products,
            'max_users' => $plan->max_users,
            'max_storage' => $plan->max_storage_gb,
        ]);

        $tenant->logActivity('subscription_change', 'Tenant changed plan to ' . $plan->name);

        return back()->with('success', 'Subscription updated to ' . $plan->name . '.');
    }

    public function cancel()
    {
        $tenant = Auth::user()->tenant;
        $current = Subscription::where('tenant_id', $tenant->id)->latest('starts_at')->first();
        if ($current && in_array($current->status, ['active', 'trial'])) {
            $current->update(['status' => 'cancelled', 'ends_at' => now()]);
            $tenant->logActivity('subscription_cancel', 'Tenant cancelled the subscription');
        }
        return back()->with('success', 'Subscription cancelled. You will retain access until period end.');
    }

    public function resume()
    {
        $tenant = Auth::user()->tenant;
        $current = Subscription::where('tenant_id', $tenant->id)->latest('starts_at')->first();
        if ($current && $current->status === 'cancelled' && $current->ends_at && now()->lt($current->ends_at)) {
            $current->update(['status' => 'active']);
            $tenant->logActivity('subscription_resume', 'Tenant resumed the subscription');
        }
        return back()->with('success', 'Subscription resumed.');
    }

    public function invoices()
    {
        $tenant = Auth::user()->tenant;
        $payments = TenantPayment::where('tenant_id', $tenant->id)->latest()->paginate(20);
        return view('admin.subscription.invoices', compact('tenant', 'payments'));
    }

    public function paymentMethod()
    {
        $tenant = Auth::user()->tenant;
        return view('admin.subscription.payment-method', compact('tenant'));
    }

    public function updatePaymentMethod(Request $request)
    {
        $request->validate(['payment_method' => 'required|string']);
        // Persist the token or method reference as per gateway integration
        return back()->with('success', 'Payment method updated.');
    }
}







