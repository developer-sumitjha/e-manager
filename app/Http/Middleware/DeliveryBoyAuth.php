<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class DeliveryBoyAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('delivery_boy')->check()) {
            return redirect()->route('delivery-boy.login')->with('error', 'Please login to continue');
        }

        // Check if delivery boy is active
        $deliveryBoy = Auth::guard('delivery_boy')->user();
        if ($deliveryBoy->status === 'inactive') {
            Auth::guard('delivery_boy')->logout();
            return redirect()->route('delivery-boy.login')->with('error', 'Your account has been deactivated. Please contact admin.');
        }

        return $next($request);
    }
}







