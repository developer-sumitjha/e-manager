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

        // Extract subdomain from request hostname
        $host = $request->getHost();
        $parts = explode('.', $host);
        
        // Extract subdomain (first part before the main domain)
        // For example: subdomain.example.com -> subdomain
        // For example: vendor1.localhost -> vendor1
        // For example.com -> null
        $requestSubdomain = null;
        
        // Check if host is a pure IP address (no subdomain possible)
        $isIpAddress = filter_var($host, FILTER_VALIDATE_IP) !== false;
        
        if (!$isIpAddress) {
            // Extract subdomain based on number of parts and domain structure
            // vendor1.localhost -> ['vendor1', 'localhost'] -> count = 2, subdomain = vendor1
            // vendor1.example.com -> ['vendor1', 'example', 'com'] -> count = 3, subdomain = vendor1
            // localhost -> ['localhost'] -> count = 1, no subdomain
            // example.com -> ['example', 'com'] -> count = 2, no subdomain (main domain)
            
            if (count($parts) > 1) {
                // Check if this is a localhost subdomain (vendor1.localhost)
                $isLocalhostSubdomain = (count($parts) === 2 && $parts[1] === 'localhost');
                
                // Check if this is a production subdomain (vendor1.example.com - 3+ parts)
                $isProductionSubdomain = count($parts) >= 3;
                
                if ($isLocalhostSubdomain || $isProductionSubdomain) {
                    $requestSubdomain = $parts[0];
                    
                    // Skip subdomain validation for special subdomains
                    if (in_array($requestSubdomain, ['www', 'super', 'admin'])) {
                        $requestSubdomain = null;
                    }
                }
            }
        }

        // Try to authenticate as a vendor (admin user of a tenant)
        $user = User::where('email', $request->email)
            ->where('role', 'admin')
            ->first();

        if ($user && $user->tenant && Hash::check($request->password, $user->password)) {
            // Check tenant status
            $tenant = $user->tenant;
            
            // Validate that the user's tenant subdomain matches the request subdomain
            // This is a security requirement: users can only log in from their own subdomain
            if ($requestSubdomain !== null) {
                // We have a subdomain in the request, it must match the user's tenant subdomain
                if ($tenant->subdomain !== $requestSubdomain) {
                    return back()
                        ->withInput($request->only('email'))
                        ->withErrors(['email' => 'You can only log in from your own vendor subdomain. Please use the correct subdomain URL.']);
                }
            } else {
                // No subdomain in request (pure localhost or IP without subdomain)
                // For security, we should require subdomain-based login
                // Allow only if explicitly accessing from main domain (which should be restricted anyway)
                // But to be safe, we'll require subdomain matching
                return back()
                    ->withInput($request->only('email'))
                    ->withErrors(['email' => 'You must log in from your vendor subdomain. Please use: ' . $tenant->subdomain . '.' . ($host === 'localhost' ? 'localhost' : 'yourdomain.com')]);
            }
            
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
