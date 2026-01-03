<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CustomerMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            // Extract subdomain from the request
            $subdomain = $request->route('subdomain');
            return redirect()->route('customer.login', $subdomain);
        }

        if (Auth::user()->role !== 'customer') {
            $subdomain = $request->route('subdomain');
            return redirect()->route('customer.login', $subdomain)->with('error', 'Customers only.');
        }

        return $next($request);
    }
}



