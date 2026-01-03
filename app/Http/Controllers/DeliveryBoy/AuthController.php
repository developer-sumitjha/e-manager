<?php

namespace App\Http\Controllers\DeliveryBoy;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::guard('delivery_boy')->check()) {
            return redirect()->route('delivery-boy.dashboard');
        }
        return view('delivery-boy.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'password' => 'required|string',
        ]);

        // Try to find delivery boy by phone first, then by email
        $deliveryBoy = \App\Models\DeliveryBoy::where('phone', $request->phone)
            ->orWhere('email', $request->phone)
            ->first();

        if (!$deliveryBoy) {
            return back()->withErrors([
                'phone' => 'The provided credentials do not match our records.',
            ])->onlyInput('phone');
        }

        // Try authentication with email if exists
        $credentials = [];
        if ($deliveryBoy->email && filter_var($request->phone, FILTER_VALIDATE_EMAIL)) {
            $credentials = [
                'email' => $request->phone,
                'password' => $request->password,
            ];
        } else {
            $credentials = [
                'phone' => $request->phone,
                'password' => $request->password,
            ];
        }

        // Try to authenticate
        if (Auth::guard('delivery_boy')->attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            
            $deliveryBoy = Auth::guard('delivery_boy')->user();
            $deliveryBoy->update(['last_login_at' => now()]);
            
            // Only log activity if method exists
            if (method_exists($deliveryBoy, 'logActivity')) {
                $deliveryBoy->logActivity('login', 'Logged in to dashboard');
            }

            return redirect()->intended(route('delivery-boy.dashboard'))
                ->with('success', 'Welcome back, ' . $deliveryBoy->name . '!');
        }

        return back()->withErrors([
            'phone' => 'The provided credentials do not match our records.',
        ])->onlyInput('phone');
    }

    public function logout(Request $request)
    {
        $deliveryBoy = Auth::guard('delivery_boy')->user();
        if ($deliveryBoy) {
            $deliveryBoy->logActivity('logout', 'Logged out from dashboard');
        }

        Auth::guard('delivery_boy')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('delivery-boy.login')->with('success', 'You have been logged out successfully.');
    }
}

