<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\Order;
use App\Models\Product;
use App\Models\TenantPayment;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $dateRange = $request->get('date_range', '30_days');
        $tenantId = $request->get('tenant_id', 'all');
        
        // Core Statistics
        $stats = $this->getCoreStats($dateRange, $tenantId);
        
        // Revenue Analytics
        $revenueAnalytics = $this->getRevenueAnalytics($dateRange, $tenantId);
        
        // Tenant Analytics
        $tenantAnalytics = $this->getTenantAnalytics($dateRange, $tenantId);
        
        // Order Analytics
        $orderAnalytics = $this->getOrderAnalytics($dateRange, $tenantId);
        
        // Product Analytics
        $productAnalytics = $this->getProductAnalytics($dateRange, $tenantId);
        
        // User Analytics
        $userAnalytics = $this->getUserAnalytics($dateRange, $tenantId);
        
        // Growth Metrics
        $growthMetrics = $this->getGrowthMetrics($dateRange, $tenantId);
        
        // Top Performers
        $topPerformers = $this->getTopPerformers($dateRange, $tenantId);
        
        // Geographic Analytics
        $geographicAnalytics = $this->getGeographicAnalytics($dateRange, $tenantId);
        
        // Time-based Analytics
        $timeBasedAnalytics = $this->getTimeBasedAnalytics($dateRange, $tenantId);
        
        // Filter Options
        $filterOptions = $this->getFilterOptions();
        
        return view('super-admin.analytics.index', compact(
            'stats',
            'revenueAnalytics',
            'tenantAnalytics',
            'orderAnalytics',
            'productAnalytics',
            'userAnalytics',
            'growthMetrics',
            'topPerformers',
            'geographicAnalytics',
            'timeBasedAnalytics',
            'filterOptions',
            'dateRange',
            'tenantId'
        ));
    }

    public function getChartData(Request $request)
    {
        $chartType = $request->get('chart_type');
        $dateRange = $request->get('date_range', '30_days');
        $tenantId = $request->get('tenant_id', 'all');
        
        $data = [];
        
        switch ($chartType) {
            case 'revenue_trend':
                $data = $this->getRevenueTrendData($dateRange, $tenantId);
                break;
            case 'tenant_growth':
                $data = $this->getTenantGrowthData($dateRange, $tenantId);
                break;
            case 'order_volume':
                $data = $this->getOrderVolumeData($dateRange, $tenantId);
                break;
            case 'product_performance':
                $data = $this->getProductPerformanceData($dateRange, $tenantId);
                break;
            case 'user_activity':
                $data = $this->getUserActivityData($dateRange, $tenantId);
                break;
            case 'payment_methods':
                $data = $this->getPaymentMethodsData($dateRange, $tenantId);
                break;
            case 'subscription_analytics':
                $data = $this->getSubscriptionAnalyticsData($dateRange, $tenantId);
                break;
        }
        
        return response()->json([
            'success' => true,
            'data' => $data,
            'timestamp' => now()->toISOString()
        ]);
    }

    private function getCoreStats($dateRange, $tenantId)
    {
        $cacheKey = "analytics_core_stats_{$dateRange}_{$tenantId}";
        
        return Cache::remember($cacheKey, 300, function() use ($dateRange, $tenantId) {
            $dateFilter = $this->getDateFilter($dateRange);
            
            $query = $this->applyTenantFilter(DB::table('tenants'), $tenantId);
            $totalTenants = $query->count();
            
            $query = $this->applyTenantFilter(DB::table('orders'), $tenantId);
            if ($dateFilter) {
                $query->where('created_at', '>=', $dateFilter);
            }
            $totalOrders = $query->count();
            
            $query = $this->applyTenantFilter(DB::table('products'), $tenantId);
            $totalProducts = $query->count();
            
            $query = $this->applyTenantFilter(DB::table('tenant_payments'), $tenantId);
            if ($dateFilter) {
                $query->where('paid_at', '>=', $dateFilter);
            }
            $totalRevenue = $query->where('status', 'completed')->sum('amount');
            
            $query = $this->applyTenantFilter(DB::table('users'), $tenantId);
            if ($dateFilter) {
                $query->where('created_at', '>=', $dateFilter);
            }
            $totalUsers = $query->count();
            
            return [
                'total_tenants' => $totalTenants,
                'total_orders' => $totalOrders,
                'total_products' => $totalProducts,
                'total_revenue' => $totalRevenue,
                'total_users' => $totalUsers,
                'average_order_value' => $totalOrders > 0 ? round($totalRevenue / $totalOrders, 2) : 0,
                'revenue_per_tenant' => $totalTenants > 0 ? round($totalRevenue / $totalTenants, 2) : 0,
            ];
        });
    }

    private function getRevenueAnalytics($dateRange, $tenantId)
    {
        $cacheKey = "analytics_revenue_{$dateRange}_{$tenantId}";
        
        return Cache::remember($cacheKey, 300, function() use ($dateRange, $tenantId) {
            $dateFilter = $this->getDateFilter($dateRange);
            
            // Daily revenue for the period
            $dailyRevenue = [];
            $startDate = $dateFilter ?: now()->subDays(30);
            $endDate = now();
            
            for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
                $query = $this->applyTenantFilter(DB::table('tenant_payments'), $tenantId);
                $revenue = $query->where('status', 'completed')
                    ->whereDate('paid_at', $date)
                    ->sum('amount');
                
                $dailyRevenue[] = [
                    'date' => $date->format('Y-m-d'),
                    'revenue' => $revenue,
                    'formatted_date' => $date->format('M d')
                ];
            }
            
            // Revenue by payment method
            $query = $this->applyTenantFilter(DB::table('tenant_payments'), $tenantId);
            if ($dateFilter) {
                $query->where('paid_at', '>=', $dateFilter);
            }
            $revenueByMethod = $query->where('status', 'completed')
                ->select('payment_method', DB::raw('SUM(amount) as total'))
                ->groupBy('payment_method')
                ->get();
            
            // Monthly revenue trend
            $monthlyRevenue = [];
            for ($i = 11; $i >= 0; $i--) {
                $date = now()->subMonths($i);
                $query = $this->applyTenantFilter(DB::table('tenant_payments'), $tenantId);
                $revenue = $query->where('status', 'completed')
                    ->whereMonth('paid_at', $date->month)
                    ->whereYear('paid_at', $date->year)
                    ->sum('amount');
                
                $monthlyRevenue[] = [
                    'month' => $date->format('M Y'),
                    'revenue' => $revenue
                ];
            }
            
            return [
                'daily_revenue' => $dailyRevenue,
                'revenue_by_method' => $revenueByMethod,
                'monthly_revenue' => $monthlyRevenue,
                'total_revenue' => array_sum(array_column($dailyRevenue, 'revenue')),
                'average_daily_revenue' => count($dailyRevenue) > 0 ? array_sum(array_column($dailyRevenue, 'revenue')) / count($dailyRevenue) : 0,
            ];
        });
    }

    private function getTenantAnalytics($dateRange, $tenantId)
    {
        $cacheKey = "analytics_tenants_{$dateRange}_{$tenantId}";
        
        return Cache::remember($cacheKey, 300, function() use ($dateRange, $tenantId) {
            $dateFilter = $this->getDateFilter($dateRange);
            
            // Tenant status distribution
            $statusDistribution = Tenant::select('status', DB::raw('COUNT(*) as count'))
                ->groupBy('status')
                ->get();
            
            // Tenant growth over time
            $tenantGrowth = [];
            $startDate = $dateFilter ?: now()->subDays(30);
            $endDate = now();
            
            for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
                $count = Tenant::whereDate('created_at', '<=', $date)->count();
                $tenantGrowth[] = [
                    'date' => $date->format('Y-m-d'),
                    'count' => $count,
                    'formatted_date' => $date->format('M d')
                ];
            }
            
            // Top performing tenants by revenue
            $topTenants = Tenant::withCount(['payments' => function($query) use ($dateFilter) {
                if ($dateFilter) {
                    $query->where('paid_at', '>=', $dateFilter);
                }
                $query->where('status', 'completed');
            }])
            ->withSum(['payments' => function($query) use ($dateFilter) {
                if ($dateFilter) {
                    $query->where('paid_at', '>=', $dateFilter);
                }
                $query->where('status', 'completed');
            }], 'amount')
            ->orderBy('payments_sum_amount', 'desc')
            ->limit(10)
            ->get();
            
            return [
                'status_distribution' => $statusDistribution,
                'tenant_growth' => $tenantGrowth,
                'top_tenants' => $topTenants,
            'total_tenants' => Tenant::count(),
                'active_tenants' => Tenant::where('status', 'active')->count(),
                'new_tenants' => $dateFilter ? Tenant::where('created_at', '>=', $dateFilter)->count() : 0,
            ];
        });
    }

    private function getOrderAnalytics($dateRange, $tenantId)
    {
        $cacheKey = "analytics_orders_{$dateRange}_{$tenantId}";
        
        return Cache::remember($cacheKey, 300, function() use ($dateRange, $tenantId) {
            $dateFilter = $this->getDateFilter($dateRange);
            
            // Order status distribution
            $statusDistribution = Order::select('status', DB::raw('COUNT(*) as count'))
                ->when($dateFilter, function($query) use ($dateFilter) {
                    return $query->where('created_at', '>=', $dateFilter);
                })
                ->groupBy('status')
                ->get();
            
            // Daily order volume
            $dailyOrders = [];
            $startDate = $dateFilter ?: now()->subDays(30);
            $endDate = now();
            
            for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
                $query = $this->applyTenantFilter(DB::table('orders'), $tenantId);
                $count = $query->whereDate('created_at', $date)->count();
                
                $dailyOrders[] = [
                    'date' => $date->format('Y-m-d'),
                    'count' => $count,
                    'formatted_date' => $date->format('M d')
                ];
            }
            
            // Average order value over time
            $avgOrderValue = [];
            for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
                $query = $this->applyTenantFilter(DB::table('orders'), $tenantId);
                $orders = $query->whereDate('created_at', $date)->get();
                $avgValue = $orders->count() > 0 ? $orders->avg('total') : 0;
                
                $avgOrderValue[] = [
                    'date' => $date->format('Y-m-d'),
                    'avg_value' => round($avgValue, 2),
                    'formatted_date' => $date->format('M d')
                ];
            }
            
            return [
                'status_distribution' => $statusDistribution,
                'daily_orders' => $dailyOrders,
                'avg_order_value' => $avgOrderValue,
                'total_orders' => $this->applyTenantFilter(Order::query(), $tenantId)
                    ->when($dateFilter, function($query) use ($dateFilter) {
                        return $query->where('created_at', '>=', $dateFilter);
                    })
                    ->count(),
                'completed_orders' => $this->applyTenantFilter(Order::query(), $tenantId)
                    ->where('status', 'completed')
                    ->when($dateFilter, function($query) use ($dateFilter) {
                        return $query->where('created_at', '>=', $dateFilter);
                    })
                    ->count(),
            ];
        });
    }

    private function getProductAnalytics($dateRange, $tenantId)
    {
        $cacheKey = "analytics_products_{$dateRange}_{$tenantId}";
        
        return Cache::remember($cacheKey, 300, function() use ($dateRange, $tenantId) {
            $dateFilter = $this->getDateFilter($dateRange);
            
            // Top selling products
            $topProducts = Product::withCount(['orderItems' => function($query) use ($dateFilter) {
                if ($dateFilter) {
                    $query->whereHas('order', function($q) use ($dateFilter) {
                        $q->where('created_at', '>=', $dateFilter);
                    });
                }
            }])
            ->withSum(['orderItems' => function($query) use ($dateFilter) {
                if ($dateFilter) {
                    $query->whereHas('order', function($q) use ($dateFilter) {
                        $q->where('created_at', '>=', $dateFilter);
                    });
                }
            }], 'total')
            ->orderBy('order_items_count', 'desc')
            ->limit(10)
            ->get();
            
            // Product categories performance
            $categoryPerformance = DB::table('products')
                ->join('categories', 'products.category_id', '=', 'categories.id')
                ->join('order_items', 'products.id', '=', 'order_items.product_id')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->when($dateFilter, function($query) use ($dateFilter) {
                    return $query->where('orders.created_at', '>=', $dateFilter);
                })
                ->select('categories.name', DB::raw('COUNT(DISTINCT products.id) as product_count'), DB::raw('SUM(order_items.total) as revenue'))
                ->groupBy('categories.id', 'categories.name')
                ->orderBy('revenue', 'desc')
                ->get();
            
            return [
                'top_products' => $topProducts,
                'category_performance' => $categoryPerformance,
                'total_products' => $this->applyTenantFilter(Product::query(), $tenantId)->count(),
                'active_products' => $this->applyTenantFilter(Product::query(), $tenantId)->where('is_active', true)->count(),
                'low_stock_products' => $this->applyTenantFilter(Product::query(), $tenantId)->where('stock', '<', 10)->count(),
            ];
        });
    }

    private function getUserAnalytics($dateRange, $tenantId)
    {
        $cacheKey = "analytics_users_{$dateRange}_{$tenantId}";
        
        return Cache::remember($cacheKey, 300, function() use ($dateRange, $tenantId) {
            $dateFilter = $this->getDateFilter($dateRange);
            
            // User role distribution
            $roleDistribution = User::select('role', DB::raw('COUNT(*) as count'))
                ->when($dateFilter, function($query) use ($dateFilter) {
                    return $query->where('created_at', '>=', $dateFilter);
                })
                ->groupBy('role')
                ->get();
            
            // User registration trend
            $userRegistration = [];
            $startDate = $dateFilter ?: now()->subDays(30);
            $endDate = now();
            
            for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
                $query = $this->applyTenantFilter(DB::table('users'), $tenantId);
                $count = $query->whereDate('created_at', $date)->count();
                
                $userRegistration[] = [
                    'date' => $date->format('Y-m-d'),
                    'count' => $count,
                    'formatted_date' => $date->format('M d')
                ];
            }
            
            return [
                'role_distribution' => $roleDistribution,
                'user_registration' => $userRegistration,
                'total_users' => $this->applyTenantFilter(User::query(), $tenantId)->count(),
                'new_users' => $dateFilter ? $this->applyTenantFilter(User::query(), $tenantId)->where('created_at', '>=', $dateFilter)->count() : 0,
                'active_users' => $this->applyTenantFilter(User::query(), $tenantId)->where('is_active', true)->count(),
            ];
        });
    }

    private function getGrowthMetrics($dateRange, $tenantId)
    {
        $cacheKey = "analytics_growth_{$dateRange}_{$tenantId}";
        
        return Cache::remember($cacheKey, 300, function() use ($dateRange, $tenantId) {
            $dateFilter = $this->getDateFilter($dateRange);
            $previousPeriodFilter = $this->getPreviousPeriodFilter($dateRange);
            
            // Current period metrics
            $currentTenants = $dateFilter ? Tenant::where('created_at', '>=', $dateFilter)->count() : Tenant::count();
            $currentRevenue = $this->applyTenantFilter(TenantPayment::query(), $tenantId)
                ->where('status', 'completed')
                ->when($dateFilter, function($query) use ($dateFilter) {
                    return $query->where('paid_at', '>=', $dateFilter);
                })
                ->sum('amount');
            
            // Previous period metrics
            $previousTenants = $previousPeriodFilter ? Tenant::whereBetween('created_at', $previousPeriodFilter)->count() : 0;
            $previousRevenue = $this->applyTenantFilter(TenantPayment::query(), $tenantId)
                ->where('status', 'completed')
                ->when($previousPeriodFilter, function($query) use ($previousPeriodFilter) {
                    return $query->whereBetween('paid_at', $previousPeriodFilter);
                })
                ->sum('amount');
            
            // Calculate growth rates
            $tenantGrowthRate = $previousTenants > 0 ? (($currentTenants - $previousTenants) / $previousTenants) * 100 : 0;
            $revenueGrowthRate = $previousRevenue > 0 ? (($currentRevenue - $previousRevenue) / $previousRevenue) * 100 : 0;
            
            return [
                'tenant_growth_rate' => round($tenantGrowthRate, 2),
                'revenue_growth_rate' => round($revenueGrowthRate, 2),
                'current_tenants' => $currentTenants,
                'current_revenue' => $currentRevenue,
                'previous_tenants' => $previousTenants,
                'previous_revenue' => $previousRevenue,
            ];
        });
    }

    private function getTopPerformers($dateRange, $tenantId)
    {
        $cacheKey = "analytics_top_performers_{$dateRange}_{$tenantId}";
        
        return Cache::remember($cacheKey, 300, function() use ($dateRange, $tenantId) {
            $dateFilter = $this->getDateFilter($dateRange);
            
            // Top tenants by revenue
            $topTenantsByRevenue = Tenant::withSum(['payments' => function($query) use ($dateFilter) {
                if ($dateFilter) {
                    $query->where('paid_at', '>=', $dateFilter);
                }
                $query->where('status', 'completed');
            }], 'amount')
            ->orderBy('payments_sum_amount', 'desc')
            ->limit(5)
            ->get();
            
            // Top products by sales
            $topProductsBySales = Product::withCount(['orderItems' => function($query) use ($dateFilter) {
                if ($dateFilter) {
                    $query->whereHas('order', function($q) use ($dateFilter) {
                        $q->where('created_at', '>=', $dateFilter);
                    });
                }
            }])
            ->orderBy('order_items_count', 'desc')
            ->limit(5)
            ->get();
            
            return [
                'top_tenants_by_revenue' => $topTenantsByRevenue,
                'top_products_by_sales' => $topProductsBySales,
            ];
        });
    }

    private function getGeographicAnalytics($dateRange, $tenantId)
    {
        $cacheKey = "analytics_geographic_{$dateRange}_{$tenantId}";
        
        return Cache::remember($cacheKey, 300, function() use ($dateRange, $tenantId) {
            // This would require geographic data in the database
            // For now, return placeholder data
            return [
                'countries' => [],
                'cities' => [],
                'regions' => [],
            ];
        });
    }

    private function getTimeBasedAnalytics($dateRange, $tenantId)
    {
        $cacheKey = "analytics_time_based_{$dateRange}_{$tenantId}";
        
        return Cache::remember($cacheKey, 300, function() use ($dateRange, $tenantId) {
            $dateFilter = $this->getDateFilter($dateRange);
            
            // Hourly activity (for today)
            $hourlyActivity = [];
            for ($hour = 0; $hour < 24; $hour++) {
                $query = $this->applyTenantFilter(DB::table('orders'), $tenantId);
                $count = $query->whereDate('created_at', today())
                    ->whereRaw('HOUR(created_at) = ?', [$hour])
                    ->count();
                
                $hourlyActivity[] = [
                    'hour' => $hour,
                    'count' => $count,
                    'formatted_hour' => sprintf('%02d:00', $hour)
                ];
            }
            
            // Weekly activity
            $weeklyActivity = [];
            for ($week = 0; $week < 12; $week++) {
                $startOfWeek = now()->subWeeks($week)->startOfWeek();
                $endOfWeek = now()->subWeeks($week)->endOfWeek();
                
                $query = $this->applyTenantFilter(DB::table('orders'), $tenantId);
                $count = $query->whereBetween('created_at', [$startOfWeek, $endOfWeek])->count();
                
                $weeklyActivity[] = [
                    'week' => $week,
                    'count' => $count,
                    'formatted_week' => $startOfWeek->format('M d') . ' - ' . $endOfWeek->format('M d')
                ];
            }
            
            return [
                'hourly_activity' => $hourlyActivity,
                'weekly_activity' => $weeklyActivity,
            ];
        });
    }

    private function getFilterOptions()
    {
        return [
            'date_ranges' => [
                '7_days' => 'Last 7 Days',
                '30_days' => 'Last 30 Days',
                '90_days' => 'Last 90 Days',
                '1_year' => 'Last Year',
                'all_time' => 'All Time'
            ],
            'tenants' => Tenant::select('id', 'business_name')->get()->pluck('business_name', 'id')->toArray()
        ];
    }

    private function getDateFilter($dateRange)
    {
        switch ($dateRange) {
            case '7_days':
                return now()->subDays(7);
            case '30_days':
                return now()->subDays(30);
            case '90_days':
                return now()->subDays(90);
            case '1_year':
                return now()->subYear();
            case 'all_time':
            default:
                return null;
        }
    }

    private function getPreviousPeriodFilter($dateRange)
    {
        switch ($dateRange) {
            case '7_days':
                return [now()->subDays(14), now()->subDays(7)];
            case '30_days':
                return [now()->subDays(60), now()->subDays(30)];
            case '90_days':
                return [now()->subDays(180), now()->subDays(90)];
            case '1_year':
                return [now()->subYears(2), now()->subYear()];
            default:
                return null;
        }
    }

    private function applyTenantFilter($query, $tenantId)
    {
        if ($tenantId !== 'all' && $tenantId) {
            return $query->where('tenant_id', $tenantId);
        }
        return $query;
    }

    // Chart data methods
    private function getRevenueTrendData($dateRange, $tenantId)
    {
        $revenueAnalytics = $this->getRevenueAnalytics($dateRange, $tenantId);
        return $revenueAnalytics['daily_revenue'];
    }

    private function getTenantGrowthData($dateRange, $tenantId)
    {
        $tenantAnalytics = $this->getTenantAnalytics($dateRange, $tenantId);
        return $tenantAnalytics['tenant_growth'];
    }

    private function getOrderVolumeData($dateRange, $tenantId)
    {
        $orderAnalytics = $this->getOrderAnalytics($dateRange, $tenantId);
        return $orderAnalytics['daily_orders'];
    }

    private function getProductPerformanceData($dateRange, $tenantId)
    {
        $productAnalytics = $this->getProductAnalytics($dateRange, $tenantId);
        return $productAnalytics['top_products'];
    }

    private function getUserActivityData($dateRange, $tenantId)
    {
        $userAnalytics = $this->getUserAnalytics($dateRange, $tenantId);
        return $userAnalytics['user_registration'];
    }

    private function getPaymentMethodsData($dateRange, $tenantId)
    {
        $revenueAnalytics = $this->getRevenueAnalytics($dateRange, $tenantId);
        return $revenueAnalytics['revenue_by_method'];
    }

    private function getSubscriptionAnalyticsData($dateRange, $tenantId)
    {
        $cacheKey = "analytics_subscriptions_{$dateRange}_{$tenantId}";
        
        return Cache::remember($cacheKey, 300, function() use ($dateRange, $tenantId) {
            $dateFilter = $this->getDateFilter($dateRange);
            
            $subscriptionStats = Subscription::select('status', DB::raw('COUNT(*) as count'))
                ->when($dateFilter, function($query) use ($dateFilter) {
                    return $query->where('created_at', '>=', $dateFilter);
                })
                ->groupBy('status')
                ->get();
            
            return $subscriptionStats;
        });
    }
}



