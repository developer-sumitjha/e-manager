<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\Tenant;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SubscriptionController extends Controller
{
    public function index(Request $request)
    {
        $query = Subscription::with(['tenant', 'plan']);

        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('plan')) {
            $query->where('plan_id', $request->plan);
        }

        if ($request->filled('search')) {
            $query->whereHas('tenant', function($q) use ($request) {
                $q->where('business_name', 'like', '%' . $request->search . '%')
                  ->orWhere('tenant_id', 'like', '%' . $request->search . '%')
                  ->orWhere('subdomain', 'like', '%' . $request->search . '%');
            })->orWhere('subscription_id', 'like', '%' . $request->search . '%');
        }

        // Filter by expiry status
        if ($request->filled('expiry_status')) {
            if ($request->expiry_status === 'expiring_soon') {
                $query->where('ends_at', '>=', now())
                      ->where('ends_at', '<=', now()->addDays(7));
            } elseif ($request->expiry_status === 'expired') {
                $query->where('ends_at', '<', now());
            } elseif ($request->expiry_status === 'active') {
                $query->where('ends_at', '>=', now());
            }
        }

        $subscriptions = $query->latest('starts_at')->paginate(20);
        $plans = SubscriptionPlan::where('is_active', true)->get();
        
        // Statistics
        $stats = [
            'total' => Subscription::count(),
            'active' => Subscription::where('status', 'active')->count(),
            'trial' => Subscription::where('status', 'trial')->count(),
            'expired' => Subscription::where('status', 'expired')->count(),
            'cancelled' => Subscription::where('status', 'cancelled')->count(),
            'past_due' => Subscription::where('status', 'past_due')->count(),
        ];

        return view('super-admin.subscriptions.index', compact('subscriptions', 'plans', 'stats'));
    }

    public function show(Subscription $subscription)
    {
        $subscription->load(['tenant', 'plan', 'payments']);
        $plans = SubscriptionPlan::where('is_active', true)->get();

        return view('super-admin.subscriptions.show', compact('subscription', 'plans'));
    }

    public function create()
    {
        $tenants = Tenant::where('status', '!=', 'suspended')->get();
        $plans = SubscriptionPlan::where('is_active', true)->get();

        return view('super-admin.subscriptions.create', compact('tenants', 'plans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tenant_id' => 'required|exists:tenants,id',
            'plan_id' => 'required|exists:subscription_plans,id',
            'billing_cycle' => 'required|in:monthly,yearly',
            'status' => 'required|in:trial,active,expired,cancelled,past_due',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after:starts_at',
            'trial_ends_at' => 'nullable|date',
            'amount' => 'required|numeric|min:0',
            'auto_renew' => 'boolean',
        ]);

        $plan = SubscriptionPlan::findOrFail($request->plan_id);
        $tenant = Tenant::findOrFail($request->tenant_id);

        // Generate subscription ID
        $lastSubscription = Subscription::latest('id')->first();
        $nextNumber = $lastSubscription ? (int) substr($lastSubscription->subscription_id, 4) + 1 : 1;
        $subscriptionId = 'SUB-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        // Calculate dates if not provided
        $startsAt = $request->starts_at ? \Carbon\Carbon::parse($request->starts_at) : now();
        $endsAt = $request->ends_at ? \Carbon\Carbon::parse($request->ends_at) : null;
        
        if (!$endsAt && $request->status === 'active') {
            $duration = $request->billing_cycle === 'yearly' ? 12 : 1;
            $endsAt = $startsAt->copy()->addMonths($duration);
        }

        $subscription = Subscription::create([
            'subscription_id' => $subscriptionId,
            'tenant_id' => $request->tenant_id,
            'plan_id' => $request->plan_id,
            'billing_cycle' => $request->billing_cycle,
            'status' => $request->status,
            'starts_at' => $startsAt,
            'ends_at' => $endsAt,
            'trial_ends_at' => $request->trial_ends_at ? \Carbon\Carbon::parse($request->trial_ends_at) : null,
            'amount' => $request->amount,
            'currency' => $plan->currency ?? 'NPR',
            'auto_renew' => $request->has('auto_renew') ? true : false,
            'next_billing_date' => $endsAt,
        ]);

        // Update tenant's current plan
        $tenant->update(['current_plan_id' => $plan->id]);

        return redirect()->route('super.subscriptions.show', $subscription)
            ->with('success', 'Subscription created successfully.');
    }

    public function edit(Subscription $subscription)
    {
        $subscription->load(['tenant', 'plan']);
        $tenants = Tenant::where('status', '!=', 'suspended')->get();
        $plans = SubscriptionPlan::where('is_active', true)->get();

        return view('super-admin.subscriptions.edit', compact('subscription', 'tenants', 'plans'));
    }

    public function update(Request $request, Subscription $subscription)
    {
        $request->validate([
            'plan_id' => 'required|exists:subscription_plans,id',
            'billing_cycle' => 'required|in:monthly,yearly',
            'status' => 'required|in:trial,active,expired,cancelled,past_due',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after:starts_at',
            'trial_ends_at' => 'nullable|date',
            'amount' => 'required|numeric|min:0',
            'auto_renew' => 'boolean',
        ]);

        $subscription->update([
            'plan_id' => $request->plan_id,
            'billing_cycle' => $request->billing_cycle,
            'status' => $request->status,
            'starts_at' => $request->starts_at ? \Carbon\Carbon::parse($request->starts_at) : $subscription->starts_at,
            'ends_at' => $request->ends_at ? \Carbon\Carbon::parse($request->ends_at) : $subscription->ends_at,
            'trial_ends_at' => $request->trial_ends_at ? \Carbon\Carbon::parse($request->trial_ends_at) : $subscription->trial_ends_at,
            'amount' => $request->amount,
            'auto_renew' => $request->has('auto_renew') ? true : false,
        ]);

        // Update tenant's current plan
        $subscription->tenant->update(['current_plan_id' => $request->plan_id]);

        return redirect()->route('super.subscriptions.show', $subscription)
            ->with('success', 'Subscription updated successfully.');
    }

    public function activate(Subscription $subscription)
    {
        $subscription->update([
            'status' => 'active',
            'starts_at' => $subscription->starts_at ?: now(),
            'ends_at' => $subscription->ends_at ?: now()->addMonth(),
        ]);

        return redirect()->back()->with('success', 'Subscription activated successfully.');
    }

    public function cancel(Subscription $subscription, Request $request)
    {
        $subscription->cancel($request->reason ?? 'Cancelled by super admin');

        return redirect()->back()->with('success', 'Subscription cancelled successfully.');
    }

    public function renew(Subscription $subscription)
    {
        $subscription->renew();

        return redirect()->back()->with('success', 'Subscription renewed successfully.');
    }

    public function extend(Request $request, Subscription $subscription)
    {
        $request->validate([
            'months' => 'required|integer|min:1|max:12',
        ]);

        $currentEndsAt = $subscription->ends_at ?: now();
        $newEndsAt = $currentEndsAt->copy()->addMonths($request->months);

        $subscription->update([
            'ends_at' => $newEndsAt,
            'next_billing_date' => $newEndsAt,
            'status' => 'active', // Ensure it's active when extended
        ]);

        return redirect()->back()->with('success', "Subscription extended by {$request->months} month(s).");
    }

    public function updateStatus(Request $request, Subscription $subscription)
    {
        $request->validate([
            'status' => 'required|in:trial,active,expired,cancelled,past_due',
        ]);

        $subscription->update(['status' => $request->status]);

        if ($request->status === 'cancelled') {
            $subscription->update([
                'cancelled_at' => now(),
                'auto_renew' => false,
            ]);
        }

        return redirect()->back()->with('success', 'Subscription status updated successfully.');
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,cancel,delete',
            'subscription_ids' => 'required|array',
            'subscription_ids.*' => 'exists:subscriptions,id',
        ]);

        $subscriptions = Subscription::whereIn('id', $request->subscription_ids)->get();
        $count = 0;

        foreach ($subscriptions as $subscription) {
            switch ($request->action) {
                case 'activate':
                    $subscription->update(['status' => 'active']);
                    $count++;
                    break;
                case 'cancel':
                    $subscription->cancel('Bulk cancelled by super admin');
                    $count++;
                    break;
                case 'delete':
                    $subscription->delete();
                    $count++;
                    break;
            }
        }

        return redirect()->back()->with('success', "{$count} subscription(s) {$request->action}ed successfully.");
    }
}







