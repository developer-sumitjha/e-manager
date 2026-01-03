<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\Tenant;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;

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
                  ->orWhere('tenant_id', 'like', '%' . $request->search . '%');
            });
        }

        $subscriptions = $query->latest()->paginate(20);
        $plans = SubscriptionPlan::all();

        return view('super-admin.subscriptions.index', compact('subscriptions', 'plans'));
    }

    public function show(Subscription $subscription)
    {
        $subscription->load(['tenant', 'plan', 'payments']);

        return view('super-admin.subscriptions.show', compact('subscription'));
    }

    public function cancel(Subscription $subscription)
    {
        $subscription->cancel('Cancelled by super admin');

        return redirect()->back()->with('success', 'Subscription cancelled successfully');
    }

    public function renew(Subscription $subscription)
    {
        $subscription->renew();

        return redirect()->back()->with('success', 'Subscription renewed successfully');
    }
}







