<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    /**
     * Customer dashboard
     */
    public function dashboard($subdomain)
    {
        $tenant = Tenant::where('subdomain', $subdomain)->firstOrFail();
        $user = Auth::user();
        
        // Get recent orders
        $recentOrders = Order::where('tenant_id', $tenant->id)
            ->where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();
        
        // Get order statistics
        $totalOrders = Order::where('tenant_id', $tenant->id)
            ->where('user_id', $user->id)
            ->count();
        
        $pendingOrders = Order::where('tenant_id', $tenant->id)
            ->where('user_id', $user->id)
            ->where('status', 'pending')
            ->count();
        
        $totalSpent = Order::where('tenant_id', $tenant->id)
            ->where('user_id', $user->id)
            ->where('payment_status', 'paid')
            ->sum('total');
        
        return view('customer.dashboard', compact('tenant', 'user', 'recentOrders', 'totalOrders', 'pendingOrders', 'totalSpent'));
    }

    /**
     * Show customer profile
     */
    public function profile($subdomain)
    {
        $tenant = Tenant::where('subdomain', $subdomain)->firstOrFail();
        $user = Auth::user();
        
        return view('customer.profile', compact('tenant', 'user'));
    }

    /**
     * Update customer profile
     */
    public function updateProfile(Request $request, $subdomain)
    {
        $tenant = Tenant::where('subdomain', $subdomain)->firstOrFail();
        $user = Auth::user();
        
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'required|string|max:20',
            'current_password' => 'nullable|string',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'name' => $request->first_name . ' ' . $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        // Update password if provided
        if ($request->filled('password')) {
            if (!$request->filled('current_password') || !Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect.']);
            }
            
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        return back()->with('success', 'Profile updated successfully!');
    }

    /**
     * Show customer addresses
     */
    public function addresses($subdomain)
    {
        $tenant = Tenant::where('subdomain', $subdomain)->firstOrFail();
        $user = Auth::user();
        
        $addresses = $user->addresses ?? [];
        
        return view('customer.addresses', compact('tenant', 'user', 'addresses'));
    }

    /**
     * Store customer address
     */
    public function storeAddress(Request $request, $subdomain)
    {
        $tenant = Tenant::where('subdomain', $subdomain)->firstOrFail();
        $user = Auth::user();
        
        $request->validate([
            'type' => 'required|in:billing,shipping',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'is_default' => 'boolean',
        ]);

        $addresses = $user->addresses ?? [];
        $newAddress = [
            'id' => uniqid(),
            'type' => $request->type,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'company' => $request->company,
            'address_line_1' => $request->address_line_1,
            'address_line_2' => $request->address_line_2,
            'city' => $request->city,
            'state' => $request->state,
            'postal_code' => $request->postal_code,
            'country' => $request->country,
            'phone' => $request->phone,
            'is_default' => $request->boolean('is_default'),
        ];

        // If this is set as default, unset other defaults of the same type
        if ($newAddress['is_default']) {
            foreach ($addresses as &$address) {
                if ($address['type'] === $newAddress['type']) {
                    $address['is_default'] = false;
                }
            }
        }

        $addresses[] = $newAddress;
        $user->update(['addresses' => $addresses]);

        return back()->with('success', 'Address added successfully!');
    }

    /**
     * Update customer address
     */
    public function updateAddress(Request $request, $subdomain, $addressId)
    {
        $tenant = Tenant::where('subdomain', $subdomain)->firstOrFail();
        $user = Auth::user();
        
        $request->validate([
            'type' => 'required|in:billing,shipping',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'is_default' => 'boolean',
        ]);

        $addresses = $user->addresses ?? [];
        $addressIndex = null;
        
        foreach ($addresses as $index => $address) {
            if ($address['id'] === $addressId) {
                $addressIndex = $index;
                break;
            }
        }

        if ($addressIndex === null) {
            return back()->with('error', 'Address not found.');
        }

        $addresses[$addressIndex] = [
            'id' => $addressId,
            'type' => $request->type,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'company' => $request->company,
            'address_line_1' => $request->address_line_1,
            'address_line_2' => $request->address_line_2,
            'city' => $request->city,
            'state' => $request->state,
            'postal_code' => $request->postal_code,
            'country' => $request->country,
            'phone' => $request->phone,
            'is_default' => $request->boolean('is_default'),
        ];

        // If this is set as default, unset other defaults of the same type
        if ($addresses[$addressIndex]['is_default']) {
            foreach ($addresses as &$address) {
                if ($address['type'] === $addresses[$addressIndex]['type'] && $address['id'] !== $addressId) {
                    $address['is_default'] = false;
                }
            }
        }

        $user->update(['addresses' => $addresses]);

        return back()->with('success', 'Address updated successfully!');
    }

    /**
     * Delete customer address
     */
    public function deleteAddress(Request $request, $subdomain, $addressId)
    {
        $tenant = Tenant::where('subdomain', $subdomain)->firstOrFail();
        $user = Auth::user();
        
        $addresses = $user->addresses ?? [];
        $addressIndex = null;
        
        foreach ($addresses as $index => $address) {
            if ($address['id'] === $addressId) {
                $addressIndex = $index;
                break;
            }
        }

        if ($addressIndex === null) {
            return back()->with('error', 'Address not found.');
        }

        unset($addresses[$addressIndex]);
        $addresses = array_values($addresses); // Re-index array
        
        $user->update(['addresses' => $addresses]);

        return back()->with('success', 'Address deleted successfully!');
    }

    /**
     * Show customer orders
     */
    public function orders($subdomain)
    {
        $tenant = Tenant::where('subdomain', $subdomain)->firstOrFail();
        $user = Auth::user();
        
        $orders = Order::where('tenant_id', $tenant->id)
            ->where('user_id', $user->id)
            ->with('orderItems.product')
            ->latest()
            ->paginate(10);
        
        return view('customer.orders', compact('tenant', 'user', 'orders'));
    }

    /**
     * Show order detail
     */
    public function orderDetail($subdomain, $orderId)
    {
        $tenant = Tenant::where('subdomain', $subdomain)->firstOrFail();
        $user = Auth::user();
        
        $order = Order::where('tenant_id', $tenant->id)
            ->where('user_id', $user->id)
            ->where('id', $orderId)
            ->with('orderItems.product')
            ->firstOrFail();
        
        return view('customer.order-detail', compact('tenant', 'user', 'order'));
    }
}