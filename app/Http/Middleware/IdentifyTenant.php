<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Tenant;
use App\Services\TenantManagerSingleDB;
use Illuminate\Http\Request;

class IdentifyTenant
{
    protected $tenantManager;

    public function __construct(TenantManagerSingleDB $tenantManager)
    {
        $this->tenantManager = $tenantManager;
    }

    public function handle(Request $request, Closure $next)
    {
        // Get hostname
        $host = $request->getHost();
        
        // Extract subdomain
        $parts = explode('.', $host);
        $subdomain = count($parts) > 2 ? $parts[0] : null;
        
        // Skip for main domain, super admin, www
        if (in_array($subdomain, ['www', 'super', 'admin', null]) || $subdomain === 'localhost') {
            return $next($request);
        }
        
        // Find tenant by subdomain
        $tenant = Tenant::where('subdomain', $subdomain)
            ->whereIn('status', ['trial', 'active'])
            ->first();
        
        if (!$tenant) {
            return response()->view('errors.tenant-not-found', [], 404);
        }
        
        // Check if trial expired
        if ($tenant->trialExpired() && !$tenant->subscriptionActive()) {
            return response()->view('errors.trial-expired', ['tenant' => $tenant], 403);
        }
        
        // Check if subscription expired
        if ($tenant->status === 'active' && !$tenant->subscriptionActive()) {
            $tenant->update(['status' => 'suspended']);
            return response()->view('errors.subscription-expired', ['tenant' => $tenant], 403);
        }
        
        // Set tenant context
        $this->tenantManager->setTenant($tenant);
        
        // Share with views
        view()->share('currentTenant', $tenant);
        config(['app.tenant' => $tenant]);
        
        return $next($request);
    }
}
