<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('vendor.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        // Try to authenticate as a vendor (admin user of a tenant)
        $user = User::where('email', $request->email)
            ->where('role', 'admin')
            ->first();

        if ($user && $user->tenant && Hash::check($request->password, $user->password)) {
            // Check tenant status
            $tenant = $user->tenant;
            
            if ($tenant->status === 'suspended') {
                return back()
                    ->withInput($request->only('email'))
                    ->withErrors(['email' => 'Your vendor account is suspended. Please contact support.']);
            }
            
            if ($tenant->status === 'pending') {
                return back()
                    ->withInput($request->only('email'))
                    ->withErrors(['email' => 'Your vendor account is pending approval. Please wait for activation.']);
            }
            
            // Login the user
            Auth::login($user);
            
            // Update last login
            $user->update(['last_login_at' => now()]);
            
            return redirect()->intended(route('admin.dashboard'))
                ->with('success', 'Welcome back! You have successfully logged in.');
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => 'Invalid credentials or you are not a vendor admin.']);
    }

    public function showRegisterForm()
    {
        // Vendors are tenant admins; self-registration disabled. Use public signup handled by Super Admin.
        return redirect()->route('public.signup');
    }

    public function register(Request $request)
    {
        // Vendors are tenant admins managed by Super Admin; disable self-registration.
        return redirect()->route('public.signup')
            ->with('error', 'Vendor self-registration is disabled. Please apply via the signup page.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('vendor.login')
            ->with('success', 'You have been logged out successfully.');
    }
}
