<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureActiveSubscription
{
    public function handle(Request $request, Closure $next): Response
    {
        // Try standard web guard first, then delivery_boy guard
        $user = Auth::user() ?: Auth::guard('delivery_boy')->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Enforce for any authenticated user that belongs to a tenant (admins and employees)
        if (!$user->tenant) {
            return $next($request);
        }

        $tenant = $user->tenant;

        // Block suspended or pending tenants
        if (in_array($tenant->status, ['suspended', 'pending'], true)) {
            return redirect()->back()->with('error', 'Account not active. Please contact support.');
        }

        // Check subscription: active or trial and not expired
        $subscription = \App\Models\Subscription::where('tenant_id', $tenant->id)
            ->latest('starts_at')
            ->first();

        if (!$subscription) {
            return redirect()->back()->with('error', 'No subscription found. Please subscribe to continue.');
        }

        $isActive = $subscription->status === 'active';
        $isTrialValid = $subscription->status === 'trial' && (!$subscription->trial_ends_at || now()->lte($subscription->trial_ends_at));
        $withinPeriod = (!$subscription->ends_at) || now()->lte($subscription->ends_at);

        if (!($withinPeriod && ($isActive || $isTrialValid))) {
            return redirect()->back()->with('error', 'Your subscription is inactive or expired.');
        }

        return $next($request);
    }
}



