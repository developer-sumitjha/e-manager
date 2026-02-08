<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\SubscriptionPlan;
use App\Services\TenantManagerSingleDB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TenantController extends Controller
{
    protected $tenantManager;

    public function __construct(TenantManagerSingleDB $tenantManager)
    {
        $this->tenantManager = $tenantManager;
    }

    public function index(Request $request)
    {
        $query = Tenant::with('currentPlan', 'activeSubscription');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('business_name', 'like', "%{$search}%")
                  ->orWhere('business_email', 'like', "%{$search}%")
                  ->orWhere('tenant_id', 'like', "%{$search}%")
                  ->orWhere('subdomain', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Plan filter
        if ($request->filled('plan')) {
            $query->where('current_plan_id', $request->plan);
        }

        $tenants = $query->latest()->paginate(20);
        $plans = SubscriptionPlan::all();

        return view('super-admin.tenants.index', compact('tenants', 'plans'));
    }

    public function create()
    {
        $plans = SubscriptionPlan::where('is_active', true)->get();
        return view('super-admin.tenants.create', compact('plans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'business_name' => 'required|string|max:255',
            'business_email' => 'required|email|unique:tenants,business_email',
            'business_phone' => 'nullable|string',
            'business_address' => 'nullable|string',
            'owner_name' => 'required|string',
            'owner_email' => 'required|email|unique:users,email',
            'owner_phone' => 'nullable|string',
            'subdomain' => 'required|string|alpha_dash|unique:tenants,subdomain',
            'current_plan_id' => 'required|exists:subscription_plans,id',
            'status' => 'required|in:active,trial,pending,suspended',
            // Optional: allow setting a custom admin password during tenant creation
            'owner_password' => 'nullable|string|min:8|confirmed',
        ]);

        // Generate tenant ID
        $validated['tenant_id'] = 'TEN-' . strtoupper(Str::random(8));
        
        // Set defaults
        $plan = SubscriptionPlan::find($validated['current_plan_id']);
        $validated['max_orders'] = $plan->max_orders_per_month;
        $validated['max_products'] = $plan->max_products;
        $validated['max_users'] = $plan->max_users;
        $validated['max_storage'] = $plan->max_storage_gb;
        
        // Add tenant password (for tenant admin login) — optional and independent of admin user password
        $validated['password'] = bcrypt($request->input('tenant_password', 'password123'));

        // Create tenant
        $tenant = Tenant::create($validated);

        // Create admin user
        $adminUser = \App\Models\User::create([
            'tenant_id' => $tenant->id,
            'name' => $validated['owner_name'],
            'email' => $validated['owner_email'],
            'phone' => $validated['owner_phone'] ?? null,
            // Use custom owner password if provided; fallback to default only if absent
            'password' => bcrypt($request->input('owner_password') ?: 'password123'),
            'role' => 'admin',
        ]);

        // Create subscription if not pending
        if ($validated['status'] !== 'pending') {
            \App\Models\Subscription::create([
                'subscription_id' => 'SUB-' . strtoupper(Str::random(8)),
                'tenant_id' => $tenant->id,
                'plan_id' => $plan->id,
                'billing_cycle' => 'monthly',
                'amount' => $plan->price_monthly,
                'currency' => 'NPR',
                'status' => $validated['status'] === 'trial' ? 'trial' : 'active',
                'starts_at' => now(),
                'ends_at' => now()->addMonth(),
                'trial_ends_at' => $validated['status'] === 'trial' ? now()->addDays($plan->trial_days) : null,
            ]);
        }

        // Log activity
        $tenant->logActivity('created', 'Tenant created by super admin');

        return redirect()->route('super.tenants.index')
            ->with('success', 'Tenant created successfully!');
    }

    public function show(Tenant $tenant)
    {
        $tenant->load(['currentPlan', 'subscriptions', 'payments', 'invoices', 'activities']);
        
        // Get usage statistics
        $usageStats = $this->getUsageStats($tenant);

        return view('super-admin.tenants.show', compact('tenant', 'usageStats'));
    }
    
    private function getUsageStats(Tenant $tenant)
    {
        $ordersUsed = \App\Models\Order::where('tenant_id', $tenant->id)->whereMonth('created_at', now()->month)->count();
        $productsUsed = \App\Models\Product::where('tenant_id', $tenant->id)->count();
        $usersUsed = \App\Models\User::where('tenant_id', $tenant->id)->count();
        
        return [
            'orders' => [
                'used' => $ordersUsed,
                'limit' => $tenant->max_orders ?? 0,
                'percentage' => $tenant->max_orders > 0 ? min(100, ($ordersUsed / $tenant->max_orders) * 100) : 0,
            ],
            'products' => [
                'used' => $productsUsed,
                'limit' => $tenant->max_products ?? 0,
                'percentage' => $tenant->max_products > 0 ? min(100, ($productsUsed / $tenant->max_products) * 100) : 0,
            ],
            'users' => [
                'used' => $usersUsed,
                'limit' => $tenant->max_users ?? 0,
                'percentage' => $tenant->max_users > 0 ? min(100, ($usersUsed / $tenant->max_users) * 100) : 0,
            ],
        ];
    }

    public function edit(Tenant $tenant)
    {
        $plans = SubscriptionPlan::all();
        return view('super-admin.tenants.edit', compact('tenant', 'plans'));
    }

    public function update(Request $request, Tenant $tenant)
    {
        $validated = $request->validate([
            'business_name' => 'required|string|max:255',
            'business_email' => 'required|email|unique:tenants,business_email,' . $tenant->id,
            'business_phone' => 'nullable|string',
            'business_address' => 'nullable|string',
            'owner_name' => 'required|string',
            'owner_email' => 'required|email',
            'owner_phone' => 'required|string',
            'status' => 'required|in:pending,active,suspended,cancelled,trial',
            'subscription_ends_at' => 'nullable|date',
            'current_plan_id' => 'required|exists:subscription_plans,id',
            'max_orders' => 'required|integer|min:0',
            'max_products' => 'required|integer|min:0',
            'max_users' => 'required|integer|min:1',
        ]);

        // Convert subscription_ends_at to null if empty, otherwise parse the datetime
        if (empty($validated['subscription_ends_at'])) {
            $validated['subscription_ends_at'] = null;
        } else {
            // Ensure proper datetime format for database
            $validated['subscription_ends_at'] = \Carbon\Carbon::parse($validated['subscription_ends_at']);
        }

        $tenant->update($validated);
        $tenant->logActivity('updated', 'Tenant information updated by super admin');

        return redirect()->route('super.tenants.show', $tenant)
            ->with('success', 'Tenant updated successfully!');
    }

    public function destroy(Tenant $tenant)
    {
        // Delete all tenant data (users, orders, etc.)
        \App\Models\User::where('tenant_id', $tenant->id)->delete();
        \App\Models\Order::where('tenant_id', $tenant->id)->delete();
        \App\Models\Product::where('tenant_id', $tenant->id)->delete();
        // Add other tenant-specific data deletions as needed
        
        // Delete tenant record
        $tenant->delete();

        return redirect()->route('super.tenants.index')
            ->with('success', 'Tenant deleted successfully!');
    }

    public function approve(Tenant $tenant)
    {
        $tenant->update([
            'status' => 'trial',
            'is_verified' => true,
            'verified_at' => now(),
            'trial_ends_at' => now()->addDays($tenant->currentPlan->trial_days ?? 14),
        ]);

        $tenant->logActivity('approved', 'Tenant approved and activated by super admin');

        return back()->with('success', 'Tenant approved and trial started!');
    }

    public function suspend(Tenant $tenant)
    {
        $tenant->update(['status' => 'suspended']);
        $tenant->logActivity('suspended', 'Tenant suspended by super admin');

        return back()->with('success', 'Tenant suspended!');
    }

    public function activate(Tenant $tenant)
    {
        $tenant->update(['status' => 'active']);
        $tenant->logActivity('activated', 'Tenant activated by super admin');

        return back()->with('success', 'Tenant activated!');
    }

    /**
     * Bulk Actions
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:approve,suspend,activate,delete',
            'tenant_ids' => 'required|array|min:1',
            'tenant_ids.*' => 'exists:tenants,id'
        ]);

        $tenantIds = $request->tenant_ids;
        $action = $request->action;
        $count = 0;

        foreach ($tenantIds as $tenantId) {
            $tenant = Tenant::find($tenantId);
            if ($tenant) {
                switch ($action) {
                    case 'approve':
                        if ($tenant->status === 'pending') {
                            $tenant->update(['status' => 'trial']);
                            $tenant->logActivity('approved', 'Bulk approved by super admin');
                            $count++;
                        }
                        break;
                    case 'suspend':
                        if ($tenant->status !== 'suspended') {
                            $tenant->update(['status' => 'suspended']);
                            $tenant->logActivity('suspended', 'Bulk suspended by super admin');
                            $count++;
                        }
                        break;
                    case 'activate':
                        if ($tenant->status !== 'active') {
                            $tenant->update(['status' => 'active']);
                            $tenant->logActivity('activated', 'Bulk activated by super admin');
                            $count++;
                        }
                        break;
                    case 'delete':
                        // Delete tenant data
                        \App\Models\User::where('tenant_id', $tenant->id)->delete();
                        \App\Models\Product::where('tenant_id', $tenant->id)->delete();
                        \App\Models\Order::where('tenant_id', $tenant->id)->delete();
                        $tenant->delete();
                        $count++;
                        break;
                }
            }
        }

        return back()->with('success', "{$count} tenant(s) {$action}d successfully!");
    }

    /**
     * Export tenants to CSV
     */
    public function export(Request $request)
    {
        $query = Tenant::with('currentPlan');

        // Apply same filters as index
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('business_name', 'like', "%{$search}%")
                  ->orWhere('business_email', 'like', "%{$search}%")
                  ->orWhere('tenant_id', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('plan')) {
            $query->where('current_plan_id', $request->plan);
        }

        $tenants = $query->get();

        $filename = 'tenants_' . date('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($tenants) {
            $file = fopen('php://output', 'w');
            
            // Header row
            fputcsv($file, [
                'Tenant ID',
                'Business Name',
                'Email',
                'Phone',
                'Subdomain',
                'Plan',
                'Status',
                'Created At',
                'Trial Ends',
            ]);

            // Data rows
            foreach ($tenants as $tenant) {
                fputcsv($file, [
                    $tenant->tenant_id,
                    $tenant->business_name,
                    $tenant->business_email,
                    $tenant->business_phone,
                    $tenant->subdomain,
                    $tenant->currentPlan->name ?? 'N/A',
                    ucfirst($tenant->status),
                    $tenant->created_at->format('Y-m-d H:i:s'),
                    $tenant->trial_ends_at ? $tenant->trial_ends_at->format('Y-m-d') : 'N/A',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Tenant Analytics Dashboard
     */
    public function analytics(Tenant $tenant)
    {
        $tenant->load('currentPlan', 'subscriptions', 'payments');
        
        // Calculate comprehensive analytics
        $analytics = [
            // Usage Statistics
            'orders_count' => \App\Models\Order::where('tenant_id', $tenant->id)->count(),
            'orders_this_month' => \App\Models\Order::where('tenant_id', $tenant->id)
                ->whereMonth('created_at', now()->month)->count(),
            'orders_today' => \App\Models\Order::where('tenant_id', $tenant->id)
                ->whereDate('created_at', today())->count(),
            'total_revenue' => \App\Models\Order::where('tenant_id', $tenant->id)->sum('total'),
            'revenue_this_month' => \App\Models\Order::where('tenant_id', $tenant->id)
                ->whereMonth('created_at', now()->month)->sum('total'),
            
            // Product Statistics
            'products_count' => \App\Models\Product::where('tenant_id', $tenant->id)->count(),
            'active_products' => \App\Models\Product::where('tenant_id', $tenant->id)
                ->where('is_active', true)->count(),
            'low_stock_products' => \App\Models\Product::where('tenant_id', $tenant->id)
                ->where('stock', '<', 10)->count(),
            
            // User Statistics
            'users_count' => \App\Models\User::where('tenant_id', $tenant->id)->count(),
            'active_users' => \App\Models\User::where('tenant_id', $tenant->id)
                ->where('is_active', true)->count(),
            
            // Financial
            'payments_count' => $tenant->payments()->count(),
            'total_paid' => $tenant->payments()->where('status', 'completed')->sum('amount'),
            'pending_payments' => $tenant->payments()->where('status', 'pending')->count(),
            
            // Activity
            'last_login' => \App\Models\User::where('tenant_id', $tenant->id)
                ->orderBy('last_login_at', 'desc')->first()->last_login_at ?? null,
            'days_since_signup' => $tenant->created_at->diffInDays(now()),
            'trial_days_remaining' => $tenant->trial_ends_at ? $tenant->trial_ends_at->diffInDays(now()) : 0,
        ];

        // Monthly data for charts (last 6 months)
        $monthlyData = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthlyData[] = [
                'month' => $date->format('M'),
                'orders' => \App\Models\Order::where('tenant_id', $tenant->id)
                    ->whereMonth('created_at', $date->month)
                    ->whereYear('created_at', $date->year)
                    ->count(),
                'revenue' => \App\Models\Order::where('tenant_id', $tenant->id)
                    ->whereMonth('created_at', $date->month)
                    ->whereYear('created_at', $date->year)
                    ->sum('total'),
            ];
        }

        // Usage percentage
        $usageStats = $this->getUsageStats($tenant);

        return view('super-admin.tenants.analytics', compact('tenant', 'analytics', 'monthlyData', 'usageStats'));
    }

    /**
     * Health Monitor
     */
    public function health(Tenant $tenant)
    {
        $health = [
            'overall_score' => 0,
            'status' => 'healthy',
            'checks' => []
        ];

        // Check 1: Active Status
        $health['checks']['active_status'] = [
            'name' => 'Active Status',
            'status' => $tenant->status === 'active' ? 'pass' : 'warning',
            'message' => "Tenant is {$tenant->status}",
            'score' => $tenant->status === 'active' ? 25 : ($tenant->status === 'trial' ? 15 : 0),
        ];

        // Check 2: Subscription Status
        $activeSubscription = $tenant->subscriptions()->where('status', 'active')->exists();
        $health['checks']['subscription'] = [
            'name' => 'Subscription',
            'status' => $activeSubscription ? 'pass' : 'fail',
            'message' => $activeSubscription ? 'Active subscription' : 'No active subscription',
            'score' => $activeSubscription ? 25 : 0,
        ];

        // Check 3: Usage Level
        $ordersThisMonth = \App\Models\Order::where('tenant_id', $tenant->id)
            ->whereMonth('created_at', now()->month)->count();
        $usagePercentage = $tenant->max_orders > 0 ? ($ordersThisMonth / $tenant->max_orders) * 100 : 0;
        $health['checks']['usage'] = [
            'name' => 'Usage Level',
            'status' => $usagePercentage > 80 ? 'warning' : 'pass',
            'message' => "Using {$usagePercentage}% of order limit",
            'score' => $usagePercentage > 0 ? 25 : 5,
        ];

        // Check 4: Payment Status
        $recentPayments = $tenant->payments()->where('created_at', '>=', now()->subMonths(3))->count();
        $health['checks']['payments'] = [
            'name' => 'Payment History',
            'status' => $recentPayments > 0 ? 'pass' : 'warning',
            'message' => "{$recentPayments} payment(s) in last 3 months",
            'score' => $recentPayments > 0 ? 25 : 10,
        ];

        // Calculate overall score
        $health['overall_score'] = array_sum(array_column($health['checks'], 'score'));
        
        // Determine overall status
        if ($health['overall_score'] >= 80) {
            $health['status'] = 'healthy';
        } elseif ($health['overall_score'] >= 50) {
            $health['status'] = 'warning';
        } else {
            $health['status'] = 'critical';
        }

        return response()->json($health);
    }
}


