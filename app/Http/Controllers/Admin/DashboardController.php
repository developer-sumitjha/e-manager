<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index()
    {
        // Get tenant ID if using multi-tenancy
        $tenantId = auth()->user()->tenant_id ?? null;
        $cacheKey = 'dashboard_stats_' . ($tenantId ?? 'global');

        // Cache dashboard data for 5 minutes
        $dashboardData = Cache::remember($cacheKey, 300, function() use ($tenantId) {
            return $this->getDashboardData($tenantId);
        });

        return view('admin.dashboard.index', $dashboardData);
    }

    private function getDashboardData($tenantId)
    {
        // Build base queries with tenant scope
        $ordersQuery = Order::query();
        $productsQuery = Product::query();
        $usersQuery = User::where('role', '!=', 'super_admin');

        if ($tenantId) {
            $ordersQuery->where('tenant_id', $tenantId);
            $productsQuery->where('tenant_id', $tenantId);
            $usersQuery->where('tenant_id', $tenantId);
        }

        // Get all stats in a single query using raw SQL for better performance
        $statsQuery = "
            SELECT 
                (SELECT COUNT(*) FROM products WHERE tenant_id = ? OR ? IS NULL) as total_products,
                (SELECT COUNT(*) FROM categories WHERE tenant_id = ? OR ? IS NULL) as total_categories,
                (SELECT COUNT(*) FROM orders WHERE tenant_id = ? OR ? IS NULL) as total_orders,
                (SELECT COALESCE(SUM(total), 0) FROM orders WHERE tenant_id = ? OR ? IS NULL) as total_revenue,
                (SELECT COUNT(*) FROM users WHERE role != 'super_admin' AND (tenant_id = ? OR ? IS NULL)) as total_customers,
                (SELECT COUNT(*) FROM orders WHERE status = 'pending' AND (tenant_id = ? OR ? IS NULL)) as pending_orders,
                (SELECT COUNT(*) FROM orders WHERE status = 'processing' AND (tenant_id = ? OR ? IS NULL)) as processing_orders,
                (SELECT COUNT(*) FROM orders WHERE status = 'completed' AND (tenant_id = ? OR ? IS NULL)) as completed_orders,
                (SELECT COUNT(*) FROM orders WHERE status = 'cancelled' AND (tenant_id = ? OR ? IS NULL)) as cancelled_orders,
                (SELECT COUNT(*) FROM orders WHERE DATE(created_at) = CURDATE() AND (tenant_id = ? OR ? IS NULL)) as orders_today,
                (SELECT COALESCE(SUM(total), 0) FROM orders WHERE DATE(created_at) = CURDATE() AND (tenant_id = ? OR ? IS NULL)) as revenue_today,
                (SELECT COUNT(*) FROM orders WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY) AND (tenant_id = ? OR ? IS NULL)) as orders_this_week,
                (SELECT COUNT(*) FROM orders WHERE MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE()) AND (tenant_id = ? OR ? IS NULL)) as orders_this_month,
                (SELECT COALESCE(SUM(total), 0) FROM orders WHERE MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE()) AND (tenant_id = ? OR ? IS NULL)) as revenue_this_month
        ";

        $stats = DB::selectOne($statsQuery, array_fill(0, 28, $tenantId));

        // Convert to array and format
        $stats = [
            'total_products' => (int) $stats->total_products,
            'total_categories' => (int) $stats->total_categories,
            'total_orders' => (int) $stats->total_orders,
            'total_revenue' => (float) $stats->total_revenue,
            'total_customers' => (int) $stats->total_customers,
            'pending_orders' => (int) $stats->pending_orders,
            'processing_orders' => (int) $stats->processing_orders,
            'completed_orders' => (int) $stats->completed_orders,
            'cancelled_orders' => (int) $stats->cancelled_orders,
            'orders_today' => (int) $stats->orders_today,
            'revenue_today' => (float) $stats->revenue_today,
            'orders_this_week' => (int) $stats->orders_this_week,
            'orders_this_month' => (int) $stats->orders_this_month,
            'revenue_this_month' => (float) $stats->revenue_this_month,
        ];

        // Calculate trends
        $stats['orders_trend'] = $this->calculateTrend($stats['orders_this_month'], $this->getLastMonthOrders($tenantId));
        $stats['revenue_trend'] = $this->calculateTrend($stats['revenue_this_month'], $this->getLastMonthRevenue($tenantId));
        $stats['customers_trend'] = $this->calculateTrend($stats['total_customers'], $this->getLastMonthCustomers($tenantId));
        $stats['products_trend'] = 0; // No trend for products as it's cumulative

        // Order status breakdown for charts
        $order_status_breakdown = [
            'pending' => $stats['pending_orders'],
            'processing' => $stats['processing_orders'],
            'completed' => $stats['completed_orders'],
            'cancelled' => $stats['cancelled_orders'],
        ];

        // Recent orders with optimized query
        $recent_orders = Order::select([
                'id', 'order_number', 'receiver_name', 'billing_first_name', 'billing_last_name', 
                'billing_email', 'total', 'status', 'created_at', 'user_id'
            ])
            ->with(['user:id,name,email', 'orderItems:id,order_id,product_id,quantity'])
            ->when($tenantId, function($q) use ($tenantId) {
                $q->where('tenant_id', $tenantId);
            })
            ->latest()
            ->take(10)
            ->get()
            ->map(function($order) {
                return (object)[
                    'id' => $order->id,
                    'order_number' => $order->order_number ?? 'ORD-' . str_pad($order->id, 6, '0', STR_PAD_LEFT),
                    'billing_first_name' => $order->billing_first_name ?? $order->receiver_name ?? $order->user->name ?? 'Guest',
                    'billing_last_name' => $order->billing_last_name ?? '',
                    'billing_email' => $order->billing_email ?? $order->user->email ?? '',
                    'total' => $order->total,
                    'status' => $order->status,
                    'created_at' => $order->created_at,
                ];
            });

        // Top products with sales count
        $top_products = Product::select([
                'id', 'name', 'price', 'primary_image_url', 'images', 'tenant_id'
            ])
            ->selectRaw('(SELECT COUNT(*) FROM order_items oi JOIN orders o ON oi.order_id = o.id WHERE oi.product_id = products.id AND o.status = "completed" AND (o.tenant_id = ? OR ? IS NULL)) as sales_count', [$tenantId, $tenantId])
            ->when($tenantId, function($q) use ($tenantId) {
                $q->where('tenant_id', $tenantId);
            })
            ->where('is_active', true)
            ->orderByDesc('sales_count')
            ->take(5)
            ->get()
            ->map(function($product) {
                // Set primary_image_url from images array if not set
                if (!$product->primary_image_url && $product->images) {
                    $images = is_string($product->images) ? json_decode($product->images, true) : $product->images;
                    if (is_array($images) && count($images) > 0) {
                        $product->primary_image_url = $images[0];
                    }
                }
                return $product;
            });

        // Sales series for charts (last 7 days)
        $sales_series = $this->getSalesSeries($tenantId);

        // Monthly revenue for chart (last 6 months) - optimized
        $monthly_revenue = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthly_revenue[] = [
                'month' => $date->format('M'),
                'revenue' => Order::when($tenantId, function($q) use ($tenantId) {
                        $q->where('tenant_id', $tenantId);
                    })
                    ->whereMonth('created_at', $date->month)
                    ->whereYear('created_at', $date->year)
                    ->sum('total')
            ];
        }

        // Low stock products
        $low_stock_products = Product::select(['id', 'name', 'stock', 'price'])
            ->when($tenantId, function($q) use ($tenantId) {
                $q->where('tenant_id', $tenantId);
            })
            ->where('stock', '<', 10)
            ->where('stock', '>', 0)
            ->orderBy('stock', 'asc')
            ->take(5)
            ->get();

        return [
            'stats' => $stats,
            'recent_orders' => $recent_orders,
            'top_products' => $top_products,
            'monthly_revenue' => $monthly_revenue,
            'low_stock_products' => $low_stock_products,
            'order_status_breakdown' => $order_status_breakdown,
            'sales_series' => $sales_series,
        ];
    }

    private function getSalesSeries($tenantId)
    {
        // Get sales data for the last 7 days
        $salesData = DB::table('orders')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total) as revenue'))
            ->when($tenantId, function($q) use ($tenantId) {
                $q->where('tenant_id', $tenantId);
            })
            ->where('created_at', '>=', now()->subDays(7))
            ->where('status', '!=', 'cancelled')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Fill missing days with 0
        $labels = [];
        $data = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $labels[] = now()->subDays($i)->format('D');
            
            $dayData = $salesData->where('date', $date)->first();
            $data[] = $dayData ? (float) $dayData->revenue : 0;
        }

        return [
            'labels' => $labels,
            'data' => $data,
        ];
    }

    // Clear dashboard cache when data changes
    public function clearCache()
    {
        $tenantId = auth()->user()->tenant_id ?? null;
        $cacheKey = 'dashboard_stats_' . ($tenantId ?? 'global');
        
        Cache::forget($cacheKey);
        
        return response()->json(['message' => 'Dashboard cache cleared successfully']);
    }

    // Calculate percentage trend
    private function calculateTrend($current, $previous)
    {
        if ($previous == 0) {
            return $current > 0 ? 100 : 0;
        }
        return round((($current - $previous) / $previous) * 100, 1);
    }

    // Get last month's orders count
    private function getLastMonthOrders($tenantId)
    {
        return Order::when($tenantId, function($q) use ($tenantId) {
                $q->where('tenant_id', $tenantId);
            })
            ->whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->count();
    }

    // Get last month's revenue
    private function getLastMonthRevenue($tenantId)
    {
        return Order::when($tenantId, function($q) use ($tenantId) {
                $q->where('tenant_id', $tenantId);
            })
            ->whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->sum('total');
    }

    // Get last month's customers count
    private function getLastMonthCustomers($tenantId)
    {
        return User::where('role', '!=', 'super_admin')
            ->when($tenantId, function($q) use ($tenantId) {
                $q->where('tenant_id', $tenantId);
            })
            ->whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->count();
    }
}