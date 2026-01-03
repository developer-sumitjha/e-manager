<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\Subscription;
use App\Models\TenantPayment;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportsController extends Controller
{
    /**
     * Reports Dashboard
     */
    public function index()
    {
        $availableReports = [
            'revenue' => 'Revenue & Financial Reports',
            'tenants' => 'Tenant Analytics Reports',
            'subscriptions' => 'Subscription Reports',
            'activity' => 'Platform Activity Reports',
            'custom' => 'Custom Report Builder',
        ];

        return view('super-admin.reports.index', compact('availableReports'));
    }

    /**
     * Revenue Report
     */
    public function revenue(Request $request)
    {
        $period = $request->input('period', 'monthly'); // daily, weekly, monthly, yearly
        $startDate = $request->input('start_date', now()->subMonths(12));
        $endDate = $request->input('end_date', now());

        $data = [
            'summary' => $this->getRevenueSummary($startDate, $endDate),
            'breakdown' => $this->getRevenueBreakdown($period, $startDate, $endDate),
            'by_plan' => $this->getRevenueByPlan($startDate, $endDate),
            'payment_methods' => $this->getRevenueByPaymentMethod($startDate, $endDate),
            'trends' => $this->getRevenueTrends(),
        ];

        return view('super-admin.reports.revenue', compact('data', 'period'));
    }

    /**
     * Tenant Analytics Report
     */
    public function tenants(Request $request)
    {
        $data = [
            'growth' => $this->getTenantGrowth(),
            'churn' => $this->getChurnAnalysis(),
            'lifetime_value' => $this->getCustomerLifetimeValue(),
            'acquisition' => $this->getAcquisitionMetrics(),
            'engagement' => $this->getTenantEngagement(),
            'geographic' => $this->getGeographicDistribution(),
        ];

        return view('super-admin.reports.tenants', compact('data'));
    }

    /**
     * Subscription Report
     */
    public function subscriptions(Request $request)
    {
        $data = [
            'status_breakdown' => $this->getSubscriptionStatusBreakdown(),
            'plan_distribution' => $this->getPlanDistribution(),
            'conversion_rates' => $this->getTrialConversionRates(),
            'retention' => $this->getSubscriptionRetention(),
            'upgrades_downgrades' => $this->getUpgradesDowngrades(),
            'mrr_analysis' => $this->getMRRAnalysis(),
        ];

        return view('super-admin.reports.subscriptions', compact('data'));
    }

    /**
     * Activity Report
     */
    public function activity(Request $request)
    {
        $data = [
            'platform_activity' => $this->getPlatformActivity(),
            'user_activity' => $this->getUserActivity(),
            'order_stats' => $this->getOrderStatistics(),
            'product_stats' => $this->getProductStatistics(),
            'peak_usage_times' => $this->getPeakUsageTimes(),
        ];

        return view('super-admin.reports.activity', compact('data'));
    }

    /**
     * Export Report
     */
    public function export(Request $request)
    {
        $type = $request->input('type'); // revenue, tenants, subscriptions, etc.
        $format = $request->input('format', 'csv'); // csv, pdf, excel

        // Generate report data
        $data = $this->generateReportData($type);

        if ($format === 'csv') {
            return $this->exportToCSV($data, $type);
        } elseif ($format === 'pdf') {
            return $this->exportToPDF($data, $type);
        } elseif ($format === 'excel') {
            return $this->exportToExcel($data, $type);
        }

        return back()->with('error', 'Invalid export format');
    }

    /**
     * Custom Report Builder
     */
    public function custom()
    {
        $metrics = [
            'tenants' => ['total', 'active', 'trial', 'suspended', 'new'],
            'revenue' => ['total', 'monthly', 'mrr', 'arr', 'arpu'],
            'subscriptions' => ['active', 'trial', 'expired', 'conversion_rate'],
            'orders' => ['total', 'daily', 'monthly', 'average_value'],
        ];

        $dimensions = ['date', 'plan', 'status', 'payment_method'];

        return view('super-admin.reports.custom', compact('metrics', 'dimensions'));
    }

    /**
     * Generate Custom Report
     */
    public function generateCustom(Request $request)
    {
        $validated = $request->validate([
            'metrics' => 'required|array',
            'dimensions' => 'required|array',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $data = $this->buildCustomReport($validated);

        return view('super-admin.reports.custom-result', compact('data', 'validated'));
    }

    // ===== PRIVATE HELPER METHODS =====

    private function getRevenueSummary($startDate, $endDate)
    {
        return [
            'total' => TenantPayment::where('status', 'completed')
                ->whereBetween('paid_at', [$startDate, $endDate])
                ->sum('amount'),
            'count' => TenantPayment::where('status', 'completed')
                ->whereBetween('paid_at', [$startDate, $endDate])
                ->count(),
            'average' => TenantPayment::where('status', 'completed')
                ->whereBetween('paid_at', [$startDate, $endDate])
                ->avg('amount'),
            'refunded' => TenantPayment::where('status', 'refunded')
                ->whereBetween('paid_at', [$startDate, $endDate])
                ->sum('amount'),
        ];
    }

    private function getRevenueBreakdown($period, $startDate, $endDate)
    {
        $groupBy = match($period) {
            'daily' => 'DATE(paid_at)',
            'weekly' => 'YEARWEEK(paid_at)',
            'monthly' => 'DATE_FORMAT(paid_at, "%Y-%m")',
            'yearly' => 'YEAR(paid_at)',
            default => 'DATE_FORMAT(paid_at, "%Y-%m")',
        };

        return TenantPayment::where('status', 'completed')
            ->whereBetween('paid_at', [$startDate, $endDate])
            ->selectRaw("$groupBy as period, SUM(amount) as total, COUNT(*) as count")
            ->groupBy('period')
            ->orderBy('period')
            ->get();
    }

    private function getRevenueByPlan($startDate, $endDate)
    {
        return TenantPayment::join('subscriptions', 'tenant_payments.subscription_id', '=', 'subscriptions.id')
            ->join('subscription_plans', 'subscriptions.plan_id', '=', 'subscription_plans.id')
            ->where('tenant_payments.status', 'completed')
            ->whereBetween('tenant_payments.paid_at', [$startDate, $endDate])
            ->selectRaw('subscription_plans.name, SUM(tenant_payments.amount) as total, COUNT(*) as count')
            ->groupBy('subscription_plans.name')
            ->get();
    }

    private function getRevenueByPaymentMethod($startDate, $endDate)
    {
        return TenantPayment::where('status', 'completed')
            ->whereBetween('paid_at', [$startDate, $endDate])
            ->selectRaw('payment_method, SUM(amount) as total, COUNT(*) as count')
            ->groupBy('payment_method')
            ->get();
    }

    private function getRevenueTrends()
    {
        $last12Months = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $last12Months[] = [
                'month' => $date->format('M Y'),
                'amount' => TenantPayment::where('status', 'completed')
                    ->whereMonth('paid_at', $date->month)
                    ->whereYear('paid_at', $date->year)
                    ->sum('amount'),
            ];
        }
        return $last12Months;
    }

    private function getTenantGrowth()
    {
        $growth = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $growth[] = [
                'month' => $date->format('M Y'),
                'new' => Tenant::whereMonth('created_at', $date->month)
                    ->whereYear('created_at', $date->year)->count(),
                'active' => Tenant::where('status', 'active')
                    ->whereMonth('created_at', '<=', $date->month)->count(),
            ];
        }
        return $growth;
    }

    private function getChurnAnalysis()
    {
        $churnedThisMonth = Tenant::where('status', 'suspended')
            ->whereMonth('updated_at', now()->month)->count();
        $totalStart = Tenant::whereMonth('created_at', '<', now()->month)->count();
        
        return [
            'churned_this_month' => $churnedThisMonth,
            'churn_rate' => $totalStart > 0 ? round(($churnedThisMonth / $totalStart) * 100, 2) : 0,
            'retained' => $totalStart - $churnedThisMonth,
        ];
    }

    private function getCustomerLifetimeValue()
    {
        $avgLifetime = 12; // months (assumption)
        $arpu = Subscription::where('status', 'active')->avg('amount');
        
        return [
            'average_lifetime' => $avgLifetime,
            'arpu' => round($arpu, 2),
            'ltv' => round($arpu * $avgLifetime, 2),
        ];
    }

    private function getAcquisitionMetrics()
    {
        return [
            'total_signups' => Tenant::count(),
            'this_month' => Tenant::whereMonth('created_at', now()->month)->count(),
            'conversion_rate' => $this->calculateConversionRate(),
        ];
    }

    private function getTenantEngagement()
    {
        // This would require activity tracking
        return [
            'daily_active' => User::whereDate('last_login_at', today())->distinct('tenant_id')->count('tenant_id'),
            'weekly_active' => User::whereBetween('last_login_at', [now()->subWeek(), now()])->distinct('tenant_id')->count('tenant_id'),
            'monthly_active' => User::whereBetween('last_login_at', [now()->subMonth(), now()])->distinct('tenant_id')->count('tenant_id'),
        ];
    }

    private function getGeographicDistribution()
    {
        // This would require location data
        return Tenant::selectRaw('business_type, COUNT(*) as count')
            ->groupBy('business_type')
            ->get();
    }

    private function getSubscriptionStatusBreakdown()
    {
        return Subscription::selectRaw('status, COUNT(*) as count, SUM(amount) as revenue')
            ->groupBy('status')
            ->get();
    }

    private function getPlanDistribution()
    {
        return DB::table('subscription_plans')
            ->leftJoin('tenants', 'subscription_plans.id', '=', 'tenants.current_plan_id')
            ->selectRaw('subscription_plans.name, COUNT(tenants.id) as count, subscription_plans.price_monthly')
            ->groupBy('subscription_plans.id', 'subscription_plans.name', 'subscription_plans.price_monthly')
            ->get();
    }

    private function getTrialConversionRates()
    {
        $trials = Subscription::where('status', 'trial')->count();
        $converted = Subscription::where('status', 'active')->whereNotNull('trial_ends_at')->count();
        
        return [
            'total_trials' => $trials,
            'converted' => $converted,
            'rate' => $trials > 0 ? round(($converted / $trials) * 100, 2) : 0,
        ];
    }

    private function getSubscriptionRetention()
    {
        // Calculate retention cohorts
        return ['implementation_needed' => true];
    }

    private function getUpgradesDowngrades()
    {
        // Track plan changes
        return ['implementation_needed' => true];
    }

    private function getMRRAnalysis()
    {
        $mrr = Subscription::where('status', 'active')
            ->where('billing_cycle', 'monthly')
            ->sum('amount');

        return [
            'current_mrr' => $mrr,
            'arr' => $mrr * 12,
            'growth_rate' => 0, // Calculate from historical data
        ];
    }

    private function getPlatformActivity()
    {
        return [
            'orders_today' => Order::whereDate('created_at', today())->count(),
            'orders_this_week' => Order::whereBetween('created_at', [now()->startOfWeek(), now()])->count(),
            'orders_this_month' => Order::whereMonth('created_at', now()->month)->count(),
        ];
    }

    private function getUserActivity()
    {
        return [
            'total_users' => User::count(),
            'active_today' => User::whereDate('last_login_at', today())->count(),
            'new_this_week' => User::whereBetween('created_at', [now()->startOfWeek(), now()])->count(),
        ];
    }

    private function getOrderStatistics()
    {
        return [
            'total' => Order::count(),
            'average_value' => Order::avg('total_amount'),
            'today' => Order::whereDate('created_at', today())->count(),
        ];
    }

    private function getProductStatistics()
    {
        return [
            'total' => Product::count(),
            'per_tenant' => round(Product::count() / max(Tenant::count(), 1), 2),
        ];
    }

    private function getPeakUsageTimes()
    {
        return Order::selectRaw('HOUR(created_at) as hour, COUNT(*) as count')
            ->groupBy('hour')
            ->orderBy('count', 'desc')
            ->limit(5)
            ->get();
    }

    private function calculateConversionRate()
    {
        $signups = Tenant::count();
        $active = Tenant::where('status', 'active')->count();
        return $signups > 0 ? round(($active / $signups) * 100, 2) : 0;
    }

    private function generateReportData($type)
    {
        return match($type) {
            'revenue' => $this->getRevenueSummary(now()->subYear(), now()),
            'tenants' => $this->getTenantGrowth(),
            'subscriptions' => $this->getSubscriptionStatusBreakdown(),
            default => [],
        };
    }

    private function exportToCSV($data, $type)
    {
        $filename = "{$type}_report_" . date('Y-m-d') . ".csv";
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            foreach ($data as $row) {
                fputcsv($file, (array)$row);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportToPDF($data, $type)
    {
        // Implement PDF export using a library like DomPDF
        return back()->with('info', 'PDF export coming soon');
    }

    private function exportToExcel($data, $type)
    {
        // Implement Excel export using a library like PhpSpreadsheet
        return back()->with('info', 'Excel export coming soon');
    }

    private function buildCustomReport($params)
    {
        // Build custom report based on selected metrics and dimensions
        return ['custom_report' => 'Implementation needed'];
    }
}






