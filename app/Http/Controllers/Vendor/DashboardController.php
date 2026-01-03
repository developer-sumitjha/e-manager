<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $tenant = Auth::user()->tenant;
        
        if (!$tenant) {
            return redirect()->route('vendor.login')
                ->with('error', 'No tenant associated with this account.');
        }

        // Get basic stats
        $stats = [
            'total_products' => Product::where('tenant_id', $tenant->id)->count(),
            'total_orders' => Order::where('tenant_id', $tenant->id)->count(),
            'total_users' => User::where('tenant_id', $tenant->id)->count(),
            'pending_orders' => Order::where('tenant_id', $tenant->id)
                ->where('status', 'pending')->count(),
        ];

        // Get recent orders
        $recentOrders = Order::where('tenant_id', $tenant->id)
            ->with('user')
            ->latest()
            ->limit(5)
            ->get();

        // Get low stock products
        $lowStockProducts = Product::where('tenant_id', $tenant->id)
            ->where('stock', '<', 10)
            ->limit(5)
            ->get();

        return view('vendor.dashboard', compact('stats', 'recentOrders', 'lowStockProducts', 'tenant'));
    }
}





