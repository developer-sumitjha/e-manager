<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TenantHealthController extends Controller
{
    /**
     * Tenant Health Dashboard
     */
    public function index()
    {
        $tenants = Tenant::with('currentPlan')->get()->map(function($tenant) {
            return [
                'tenant' => $tenant,
                'health_score' => $this->calculateHealthScore($tenant),
                'status_indicator' => $this->getStatusIndicator($tenant),
                'risk_level' => $this->getRiskLevel($tenant),
            ];
        })->sortByDesc('health_score');

        $summary = [
            'healthy' => $tenants->where('health_score', '>=', 70)->count(),
            'warning' => $tenants->whereBetween('health_score', [40, 69])->count(),
            'critical' => $tenants->where('health_score', '<', 40)->count(),
            'average_score' => round($tenants->avg('health_score'), 2),
        ];

        return view('super-admin.tenant-health.index', compact('tenants', 'summary'));
    }

    /**
     * Individual Tenant Health Report
     */
    public function show(Tenant $tenant)
    {
        $health = [
            'score' => $this->calculateHealthScore($tenant),
            'metrics' => $this->getDetailedMetrics($tenant),
            'trends' => $this->getHealthTrends($tenant),
            'recommendations' => $this->getRecommendations($tenant),
            'alerts' => $this->getHealthAlerts($tenant),
        ];

        return view('super-admin.tenant-health.show', compact('tenant', 'health'));
    }

    /**
     * At-Risk Tenants
     */
    public function atRisk()
    {
        $tenants = Tenant::with('currentPlan')->get()->filter(function($tenant) {
            $score = $this->calculateHealthScore($tenant);
            return $score < 50;
        });

        return view('super-admin.tenant-health.at-risk', compact('tenants'));
    }

    /**
     * Engagement Analysis
     */
    public function engagement()
    {
        $data = [
            'daily_active' => $this->getDailyActiveUsers(30),
            'login_frequency' => $this->getLoginFrequency(),
            'feature_usage' => $this->getFeatureUsage(),
            'inactive_tenants' => $this->getInactiveTenants(),
        ];

        return view('super-admin.tenant-health.engagement', compact('data'));
    }

    /**
     * Usage Statistics
     */
    public function usage()
    {
        $data = [
            'by_plan' => $this->getUsageByPlan(),
            'by_tenant' => $this->getUsageByTenant(),
            'resource_consumption' => $this->getResourceConsumption(),
        ];

        return view('super-admin.tenant-health.usage', compact('data'));
    }

    /**
     * Churn Prediction
     */
    public function churnPrediction()
    {
        $predictions = Tenant::with('currentPlan')->get()->map(function($tenant) {
            return [
                'tenant' => $tenant,
                'churn_risk' => $this->predictChurnRisk($tenant),
                'factors' => $this->getChurnFactors($tenant),
            ];
        })->sortByDesc('churn_risk');

        return view('super-admin.tenant-health.churn-prediction', compact('predictions'));
    }

    /**
     * Send Health Alert
     */
    public function sendHealthAlert(Request $request, Tenant $tenant)
    {
        $validated = $request->validate([
            'message' => 'required|string',
            'type' => 'required|in:warning,critical',
        ]);

        // Send email/notification to tenant
        \Mail::raw($validated['message'], function ($message) use ($tenant) {
            $message->to($tenant->owner_email)
                    ->subject('Important: Platform Health Alert');
        });

        return back()->with('success', 'Health alert sent to tenant!');
    }

    // ===== PRIVATE HELPER METHODS =====

    /**
     * Calculate Overall Health Score (0-100)
     */
    private function calculateHealthScore($tenant)
    {
        $scores = [
            'subscription_health' => $this->getSubscriptionHealth($tenant) * 0.3,
            'usage_health' => $this->getUsageHealth($tenant) * 0.25,
            'payment_health' => $this->getPaymentHealth($tenant) * 0.25,
            'engagement_health' => $this->getEngagementHealth($tenant) * 0.2,
        ];

        return round(array_sum($scores), 2);
    }

    private function getSubscriptionHealth($tenant)
    {
        $score = 100;

        // Deduct if on trial
        if ($tenant->status === 'trial') {
            $score -= 20;
        }

        // Deduct if subscription expiring soon
        if ($tenant->activeSubscription && $tenant->activeSubscription->ends_at < now()->addDays(7)) {
            $score -= 30;
        }

        // Deduct if suspended
        if ($tenant->status === 'suspended') {
            $score -= 50;
        }

        return max($score, 0);
    }

    private function getUsageHealth($tenant)
    {
        $ordersCount = Order::where('tenant_id', $tenant->id)
            ->whereMonth('created_at', now()->month)
            ->count();
        
        $productsCount = Product::where('tenant_id', $tenant->id)->count();
        
        $usersCount = User::where('tenant_id', $tenant->id)->count();

        // Calculate usage percentage based on limits
        $orderUsage = $tenant->max_orders > 0 ? ($ordersCount / $tenant->max_orders) * 100 : 0;
        $productUsage = $tenant->max_products > 0 ? ($productsCount / $tenant->max_products) * 100 : 0;
        $userUsage = $tenant->max_users > 0 ? ($usersCount / $tenant->max_users) * 100 : 0;

        // Higher usage = healthier (but not over limit)
        $avgUsage = ($orderUsage + $productUsage + $userUsage) / 3;
        
        if ($avgUsage > 100) {
            return 50; // Over limit
        } elseif ($avgUsage > 80) {
            return 100; // High usage, very healthy
        } elseif ($avgUsage > 50) {
            return 80; // Good usage
        } elseif ($avgUsage > 20) {
            return 60; // Moderate usage
        } else {
            return 40; // Low usage
        }
    }

    private function getPaymentHealth($tenant)
    {
        $score = 100;

        // Check failed payments
        $failedPayments = DB::table('tenant_payments')
            ->where('tenant_id', $tenant->id)
            ->where('status', 'failed')
            ->whereMonth('created_at', now()->month)
            ->count();

        $score -= $failedPayments * 20;

        // Check if payment is overdue
        $overdueInvoices = DB::table('tenant_invoices')
            ->where('tenant_id', $tenant->id)
            ->where('status', 'pending')
            ->where('due_date', '<', now())
            ->count();

        $score -= $overdueInvoices * 15;

        return max($score, 0);
    }

    private function getEngagementHealth($tenant)
    {
        // Check last login
        $lastLogin = User::where('tenant_id', $tenant->id)
            ->max('last_login_at');

        if (!$lastLogin) {
            return 0;
        }

        $daysSinceLogin = now()->diffInDays($lastLogin);

        if ($daysSinceLogin === 0) {
            return 100;
        } elseif ($daysSinceLogin <= 2) {
            return 90;
        } elseif ($daysSinceLogin <= 7) {
            return 70;
        } elseif ($daysSinceLogin <= 14) {
            return 50;
        } elseif ($daysSinceLogin <= 30) {
            return 30;
        } else {
            return 10;
        }
    }

    private function getStatusIndicator($tenant)
    {
        $score = $this->calculateHealthScore($tenant);

        if ($score >= 70) {
            return ['label' => 'Healthy', 'color' => 'success'];
        } elseif ($score >= 40) {
            return ['label' => 'Warning', 'color' => 'warning'];
        } else {
            return ['label' => 'Critical', 'color' => 'danger'];
        }
    }

    private function getRiskLevel($tenant)
    {
        $score = $this->calculateHealthScore($tenant);

        if ($score >= 70) {
            return 'low';
        } elseif ($score >= 40) {
            return 'medium';
        } else {
            return 'high';
        }
    }

    private function getDetailedMetrics($tenant)
    {
        return [
            'subscription' => [
                'status' => $tenant->status,
                'plan' => $tenant->currentPlan->name ?? 'N/A',
                'score' => $this->getSubscriptionHealth($tenant),
            ],
            'usage' => [
                'orders' => Order::where('tenant_id', $tenant->id)->whereMonth('created_at', now()->month)->count(),
                'products' => Product::where('tenant_id', $tenant->id)->count(),
                'users' => User::where('tenant_id', $tenant->id)->count(),
                'score' => $this->getUsageHealth($tenant),
            ],
            'payment' => [
                'total_paid' => DB::table('tenant_payments')
                    ->where('tenant_id', $tenant->id)
                    ->where('status', 'completed')
                    ->sum('amount'),
                'failed_count' => DB::table('tenant_payments')
                    ->where('tenant_id', $tenant->id)
                    ->where('status', 'failed')
                    ->count(),
                'score' => $this->getPaymentHealth($tenant),
            ],
            'engagement' => [
                'last_login' => User::where('tenant_id', $tenant->id)->max('last_login_at'),
                'active_users' => User::where('tenant_id', $tenant->id)
                    ->where('last_login_at', '>', now()->subWeek())
                    ->count(),
                'score' => $this->getEngagementHealth($tenant),
            ],
        ];
    }

    private function getHealthTrends($tenant)
    {
        // Calculate health score for past 30 days
        $trends = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $trends[] = [
                'date' => $date->format('M d'),
                'score' => $this->calculateHealthScore($tenant), // Simplified - same score for now
            ];
        }
        return $trends;
    }

    private function getRecommendations($tenant)
    {
        $recommendations = [];
        $score = $this->calculateHealthScore($tenant);

        if ($score < 70) {
            if ($tenant->status === 'trial') {
                $recommendations[] = 'Reach out to encourage subscription conversion';
            }
            
            if ($this->getEngagementHealth($tenant) < 50) {
                $recommendations[] = 'Low engagement - consider sending re-engagement campaign';
            }
            
            if ($this->getUsageHealth($tenant) < 40) {
                $recommendations[] = 'Low usage - offer training or onboarding support';
            }
        }

        return $recommendations;
    }

    private function getHealthAlerts($tenant)
    {
        $alerts = [];

        if ($tenant->status === 'suspended') {
            $alerts[] = ['type' => 'danger', 'message' => 'Tenant is suspended'];
        }

        if ($tenant->activeSubscription && $tenant->activeSubscription->ends_at < now()->addDays(3)) {
            $alerts[] = ['type' => 'warning', 'message' => 'Subscription expiring soon'];
        }

        $failedPayments = DB::table('tenant_payments')
            ->where('tenant_id', $tenant->id)
            ->where('status', 'failed')
            ->count();

        if ($failedPayments > 0) {
            $alerts[] = ['type' => 'warning', 'message' => "$failedPayments failed payment(s)"];
        }

        return $alerts;
    }

    private function getDailyActiveUsers($days)
    {
        $data = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $data[] = [
                'date' => $date->format('M d'),
                'count' => User::whereDate('last_login_at', $date)->distinct('tenant_id')->count('tenant_id'),
            ];
        }
        return $data;
    }

    private function getLoginFrequency()
    {
        return DB::table('users')
            ->select(DB::raw('tenant_id, COUNT(*) as login_count'))
            ->whereNotNull('last_login_at')
            ->where('last_login_at', '>', now()->subMonth())
            ->groupBy('tenant_id')
            ->orderBy('login_count', 'desc')
            ->limit(10)
            ->get();
    }

    private function getFeatureUsage()
    {
        return [
            'orders_created' => Order::whereMonth('created_at', now()->month)->count(),
            'products_added' => Product::whereMonth('created_at', now()->month)->count(),
        ];
    }

    private function getInactiveTenants()
    {
        return Tenant::whereDoesntHave('users', function($query) {
            $query->where('last_login_at', '>', now()->subMonth());
        })->count();
    }

    private function getUsageByPlan()
    {
        return DB::table('tenants')
            ->join('subscription_plans', 'tenants.current_plan_id', '=', 'subscription_plans.id')
            ->select('subscription_plans.name', DB::raw('AVG(tenants.max_orders) as avg_usage'))
            ->groupBy('subscription_plans.name')
            ->get();
    }

    private function getUsageByTenant()
    {
        return Tenant::with('currentPlan')->get()->map(function($tenant) {
            return [
                'name' => $tenant->business_name,
                'orders' => Order::where('tenant_id', $tenant->id)->count(),
                'products' => Product::where('tenant_id', $tenant->id)->count(),
            ];
        });
    }

    private function getResourceConsumption()
    {
        return [
            'database' => 'N/A', // Calculate actual DB usage per tenant
            'storage' => 'N/A', // Calculate file storage per tenant
        ];
    }

    private function predictChurnRisk($tenant)
    {
        $score = $this->calculateHealthScore($tenant);
        
        // Convert health score to churn risk (inverse relationship)
        $churnRisk = 100 - $score;
        
        return round($churnRisk, 2);
    }

    private function getChurnFactors($tenant)
    {
        $factors = [];

        if ($this->getEngagementHealth($tenant) < 50) {
            $factors[] = 'Low engagement';
        }

        if ($this->getUsageHealth($tenant) < 40) {
            $factors[] = 'Low feature usage';
        }

        if ($this->getPaymentHealth($tenant) < 70) {
            $factors[] = 'Payment issues';
        }

        return $factors;
    }
}






