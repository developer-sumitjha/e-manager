<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Show customer login form
     */
    public function showLogin($subdomain)
    {
        $tenant = Tenant::where('subdomain', $subdomain)->firstOrFail();
        return view('customer.auth.login', compact('tenant'));
    }

    /**
     * Handle customer login
     */
    public function login(Request $request, $subdomain)
    {
        $tenant = Tenant::where('subdomain', $subdomain)->firstOrFail();
        
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        $credentials = $request->only('email', 'password');
        $credentials['tenant_id'] = $tenant->id;
        $credentials['role'] = 'customer';
        $credentials['is_active'] = true;

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            return redirect()->intended(route('customer.dashboard', $subdomain))
                ->with('success', 'Welcome back!');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Show customer registration form
     */
    public function showRegister($subdomain)
    {
        $tenant = Tenant::where('subdomain', $subdomain)->firstOrFail();
        return view('customer.auth.register', compact('tenant'));
    }

    /**
     * Handle customer registration
     */
    public function register(Request $request, $subdomain)
    {
        $tenant = Tenant::where('subdomain', $subdomain)->firstOrFail();
        
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = \App\Models\User::create([
            'tenant_id' => $tenant->id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'name' => $request->first_name . ' ' . $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'customer',
            'is_active' => true,
        ]);

        Auth::login($user);

        return redirect()->route('customer.dashboard', $subdomain)
            ->with('success', 'Account created successfully! Welcome!');
    }

    /**
     * Handle customer logout
     */
    public function logout(Request $request, $subdomain)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('storefront.preview', $subdomain)
            ->with('success', 'You have been logged out successfully.');
    }
}