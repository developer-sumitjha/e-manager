<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EmployeePermission
{
    public function handle(Request $request, Closure $next, string $feature): Response
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        // Admins bypass permission checks
        if ($user->role === 'admin') {
            return $next($request);
        }

        // Employees must have explicit permission flag
        if ($user->role === 'employee') {
            $permissions = is_array($user->permissions) ? $user->permissions : [];
            if (!in_array($feature, $permissions, true)) {
                return redirect()->back()->with('error', 'You do not have permission to access this section.');
            }
            return $next($request);
        }

        // Others denied
        return redirect()->route('login')->with('error', 'Access denied.');
    }
}







