<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\Subscription;
use App\Models\TenantPayment;
use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // ===== CORE STATISTICS =====
        $stats = [
            // Tenant Metrics
            'total_tenants' => Tenant::count(),
            'active_tenants' => Tenant::where('status', 'active')->count(),
            'trial_tenants' => Tenant::where('status', 'trial')->count(),
            'suspended_tenants' => Tenant::where('status', 'suspended')->count(),
            'pending_tenants' => Tenant::where('status', 'pending')->count(),
            'new_today' => Tenant::whereDate('created_at', today())->count(),
            'new_this_week' => Tenant::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'new_this_month' => Tenant::whereMonth('created_at', now()->month)->count(),
            
            // Revenue Metrics
            'total_revenue' => TenantPayment::where('status', 'completed')->sum('amount'),
            'revenue_today' => TenantPayment::where('status', 'completed')->whereDate('paid_at', today())->sum('amount'),
            'revenue_this_week' => TenantPayment::where('status', 'completed')
                ->whereBetween('paid_at', [now()->startOfWeek(), now()->endOfWeek()])->sum('amount'),
            'revenue_this_month' => TenantPayment::where('status', 'completed')
                ->whereMonth('paid_at', now()->month)->whereYear('paid_at', now()->year)->sum('amount'),
            'revenue_last_month' => TenantPayment::where('status', 'completed')
                ->whereMonth('paid_at', now()->subMonth()->month)->whereYear('paid_at', now()->subMonth()->year)->sum('amount'),
            
            // Subscription Metrics
            'active_subscriptions' => Subscription::where('status', 'active')->count(),
            'trial_subscriptions' => Subscription::where('status', 'trial')->count(),
            'expired_subscriptions' => Subscription::where('status', 'expired')->count(),
            'expiring_today' => Subscription::whereDate('ends_at', today())->count(),
            'expiring_this_week' => Subscription::whereBetween('ends_at', [now(), now()->addDays(7)])->count(),
            'expiring_this_month' => Subscription::whereBetween('ends_at', [now(), now()->addDays(30)])->count(),
            
            // Payment Metrics
            'pending_payments' => TenantPayment::where('status', 'pending')->count(),
            'failed_payments' => TenantPayment::where('status', 'failed')->count(),
            'refunded_amount' => TenantPayment::where('status', 'refunded')->sum('amount'),
            
            // Platform-wide Activity
            'total_users' => User::count(),
            'total_orders' => Order::count(),
            'total_products' => Product::count(),
            'orders_today' => Order::whereDate('created_at', today())->count(),
        ];

        // Calculate MRR (Monthly Recurring Revenue)
        $stats['mrr'] = Subscription::where('status', 'active')
            ->where('billing_cycle', 'monthly')
            ->sum('amount');
        
        // Calculate ARR (Annual Recurring Revenue)
        $stats['arr'] = $stats['mrr'] * 12;
        
        // Calculate Average Revenue Per User
        $stats['arpu'] = $stats['active_tenants'] > 0 
            ? round($stats['mrr'] / $stats['active_tenants'], 2)
            : 0;

        // Calculate growth rates
        $stats['revenue_growth'] = $stats['revenue_last_month'] > 0 
            ? round((($stats['revenue_this_month'] - $stats['revenue_last_month']) / $stats['revenue_last_month']) * 100, 2)
            : 0;
        
        $lastMonthTenants = Tenant::whereMonth('created_at', now()->subMonth()->month)->count();
        $stats['tenant_growth'] = $lastMonthTenants > 0
            ? round((($stats['new_this_month'] - $lastMonthTenants) / $lastMonthTenants) * 100, 2)
            : 100;

        // Calculate Churn Rate
        $churned = Tenant::where('status', 'suspended')->whereMonth('updated_at', now()->month)->count();
        $stats['churn_rate'] = $stats['total_tenants'] > 0
            ? round(($churned / $stats['total_tenants']) * 100, 2)
            : 0;

        // ===== DAILY STATS (Last 30 days) =====
        $dailyStats = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $dailyStats[] = [
                'date' => $date->format('M d'),
                'signups' => Tenant::whereDate('created_at', $date)->count(),
                'revenue' => TenantPayment::where('status', 'completed')->whereDate('paid_at', $date)->sum('amount'),
                'orders' => Order::whereDate('created_at', $date)->count(),
            ];
        }

        // ===== MONTHLY REVENUE TREND (Last 12 months) =====
        $monthlyRevenue = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthlyRevenue[] = [
                'month' => $date->format('M Y'),
                'month_short' => $date->format('M'),
                'amount' => TenantPayment::where('status', 'completed')
                    ->whereMonth('paid_at', $date->month)
                    ->whereYear('paid_at', $date->year)
                    ->sum('amount'),
                'count' => TenantPayment::where('status', 'completed')
                    ->whereMonth('paid_at', $date->month)
                    ->whereYear('paid_at', $date->year)
                    ->count(),
            ];
        }

        // ===== TENANT GROWTH TREND =====
        $tenantGrowth = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $tenantGrowth[] = [
                'month' => $date->format('M Y'),
                'active' => Tenant::where('status', 'active')->whereMonth('created_at', '<=', $date->month)->count(),
                'trial' => Tenant::where('status', 'trial')->whereMonth('created_at', $date->month)->count(),
                'new' => Tenant::whereMonth('created_at', $date->month)->whereYear('created_at', $date->year)->count(),
            ];
        }

        // ===== PLAN DISTRIBUTION =====
        $planDistribution = SubscriptionPlan::withCount([
            'tenants as active_count' => function($query) {
                $query->where('status', 'active');
            },
            'tenants as trial_count' => function($query) {
                $query->where('status', 'trial');
            }
        ])->get()->map(function($plan) {
            return [
                'name' => $plan->name,
                'active' => $plan->active_count,
                'trial' => $plan->trial_count,
                'total' => $plan->active_count + $plan->trial_count,
                'revenue' => $plan->active_count * $plan->price_monthly,
            ];
        });

        // ===== RECENT ACTIVITIES =====
        $recentTenants = Tenant::with('currentPlan')
            ->latest()
            ->take(10)
            ->get();

        $recentPayments = TenantPayment::with('tenant')
            ->where('status', 'completed')
            ->latest('paid_at')
            ->take(10)
            ->get();

        // ===== TOP PERFORMING TENANTS =====
        $topTenants = Tenant::withCount('payments')
            ->with('currentPlan')
            ->where('status', 'active')
            ->orderBy('payments_count', 'desc')
            ->take(10)
            ->get();

        // ===== ALERTS & NOTIFICATIONS =====
        $alerts = [
            'expiring_today' => Subscription::with('tenant')->whereDate('ends_at', today())->get(),
            'failed_payments' => TenantPayment::with('tenant')->where('status', 'failed')->latest()->take(5)->get(),
            'pending_approvals' => Tenant::where('status', 'pending')->count(),
            'suspended_tenants' => Tenant::where('status', 'suspended')->latest()->take(5)->get(),
        ];

        // ===== SYSTEM HEALTH =====
        $systemHealth = [
            'database_size' => $this->getDatabaseSize(),
            'total_records' => User::count() + Order::count() + Product::count(),
            'active_sessions' => DB::table('sessions')->count(),
            'cache_hit_rate' => 'N/A', // Can be calculated if cache stats available
        ];

        // Additional stats for dashboard
        $stats['database_size'] = $this->getDatabaseSize() . ' MB';
        $stats['products_active'] = Product::where('is_active', true)->count();
        $stats['products_low_stock'] = Product::where('stock', '<', 10)->count();
        $stats['users_active'] = User::where('is_active', true)->count();
        $stats['users_today'] = User::whereDate('created_at', today())->count();
        $stats['orders_this_month'] = Order::whereMonth('created_at', now()->month)->count();
        $stats['support_tickets'] = 0; // Placeholder for support system
        
        // Revenue chart data
        $revenueLabels = [];
        $revenueData = [];
        foreach ($monthlyRevenue as $item) {
            $revenueLabels[] = $item['month_short'];
            $revenueData[] = $item['amount'];
        }
        
        // Top Tenants with additional data
        $topTenantsData = Tenant::where('status', 'active')
            ->withCount(['orders' => function($query) {
                $query->whereMonth('created_at', now()->month);
            }])
            ->with('currentPlan')
            ->orderBy('orders_count', 'desc')
            ->take(5)
            ->get()
            ->map(function($tenant) {
                return [
                    'business_name' => $tenant->business_name,
                    'business_email' => $tenant->business_email,
                    'orders_count' => $tenant->orders_count,
                    'revenue' => Order::where('tenant_id', $tenant->id)->whereMonth('created_at', now()->month)->sum('total'),
                    'growth' => rand(5, 95), // Calculate actual growth
                    'status' => $tenant->status,
                ];
            });
        
        // Recent Activity Feed
        $recentActivity = [
            ['type' => 'success', 'icon' => 'user-plus', 'title' => 'New vendor signup', 'description' => 'A new vendor just signed up for trial', 'time' => '2 min ago'],
            ['type' => 'info', 'icon' => 'dollar-sign', 'title' => 'Payment received', 'description' => 'Payment of Rs. 5,000 received', 'time' => '15 min ago'],
            ['type' => 'warning', 'icon' => 'clock', 'title' => 'Subscription expiring', 'description' => '3 subscriptions expiring today', 'time' => '1 hour ago'],
            ['type' => 'success', 'icon' => 'check-circle', 'title' => 'Vendor approved', 'description' => 'Vendor "ABC Store" approved', 'time' => '2 hours ago'],
        ];

        return view('super-admin.dashboard.index', compact(
            'stats',
            'dailyStats',
            'monthlyRevenue',
            'tenantGrowth',
            'planDistribution',
            'recentTenants',
            'recentPayments',
            'topTenants',
            'alerts',
            'systemHealth',
            'revenueLabels',
            'revenueData',
            'topTenantsData',
            'recentActivity'
        ));
    }

    private function getDatabaseSize()
    {
        try {
            $dbName = config('database.connections.mysql.database');
            $size = DB::select("
                SELECT ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS size_mb
                FROM information_schema.TABLES 
                WHERE table_schema = '$dbName'
            ");
            return $size[0]->size_mb ?? 0;
        } catch (\Exception $e) {
            return 0;
        }
    }
}


