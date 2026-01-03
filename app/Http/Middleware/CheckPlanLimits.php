<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\User;
use App\Models\Order;
use Symfony\Component\HttpFoundation\Response;

class CheckPlanLimits
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $limitType): Response
    {
        $user = auth()->user();
        
        // Skip for super admins
        if ($user && $user->role === 'super_admin') {
            return $next($request);
        }

        // Get tenant
        $tenant = $user->tenant ?? null;
        
        if (!$tenant) {
            return $next($request);
        }

        // Check the specific limit type
        switch ($limitType) {
            case 'products':
                $currentCount = Product::where('tenant_id', $tenant->id)->count();
                $limit = $tenant->max_products;
                $limitName = 'Products';
                $upgradeMessage = "You've reached your plan limit of {$limit} products. Please upgrade your subscription plan to add more products.";
                break;

            case 'users':
                $currentCount = User::where('tenant_id', $tenant->id)->count();
                $limit = $tenant->max_users;
                $limitName = 'Users';
                $upgradeMessage = "You've reached your plan limit of {$limit} users. Please upgrade your subscription plan to add more users.";
                break;

            case 'orders':
                $currentCount = Order::where('tenant_id', $tenant->id)
                    ->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->count();
                $limit = $tenant->max_orders;
                $limitName = 'Orders';
                $upgradeMessage = "You've reached your monthly plan limit of {$limit} orders. Please upgrade your subscription plan or wait for next month.";
                break;

            default:
                return $next($request);
        }

        // Check if limit is exceeded (only on POST/PUT/PATCH requests - creation/update)
        if (in_array($request->method(), ['POST', 'PUT', 'PATCH'])) {
            // For update requests, don't count if we're editing existing
            $isUpdate = $request->route()->hasParameter('product') || 
                       $request->route()->hasParameter('user') || 
                       $request->route()->hasParameter('order');

            if (!$isUpdate && $currentCount >= $limit) {
                // Return appropriate response based on request type
                if ($request->expectsJson()) {
                    return response()->json([
                        'error' => $upgradeMessage,
                        'limit' => $limit,
                        'current' => $currentCount,
                    ], 403);
                }

                return redirect()->back()
                    ->with('error', $upgradeMessage)
                    ->with('limit_exceeded', true)
                    ->with('limit_type', $limitName)
                    ->with('limit_value', $limit)
                    ->with('current_value', $currentCount);
            }
        }

        return $next($request);
    }
}
