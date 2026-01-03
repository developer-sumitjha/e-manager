<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class VendorMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('vendor.login');
        }

        $user = Auth::user();
        
        // Check if user is an admin of a tenant
        if ($user->role !== 'admin' || !$user->tenant) {
            Auth::logout();
            return redirect()->route('vendor.login')
                ->with('error', 'Access denied. This account is not authorized for vendor access.');
        }

        // Check if tenant is not suspended
        if ($user->tenant->status === 'suspended') {
            Auth::logout();
            return redirect()->route('vendor.login')
                ->with('error', 'Your account has been suspended. Please contact support.');
        }

        // Check if tenant is pending approval
        if ($user->tenant->status === 'pending') {
            Auth::logout();
            return redirect()->route('vendor.login')
                ->with('error', 'Your account is pending approval. Please wait for admin approval.');
        }

        return $next($request);
    }
}
