<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\TenantPayment;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FinancialController extends Controller
{
    public function index()
    {
        // Calculate financial statistics
        $stats = [
            'total_revenue' => TenantPayment::where('status', 'completed')->sum('amount'),
            'revenue_this_month' => TenantPayment::where('status', 'completed')
                ->whereMonth('paid_at', now()->month)
                ->whereYear('paid_at', now()->year)
                ->sum('amount'),
            'mrr' => Subscription::where('status', 'active')->sum('amount'),
            'arr' => Subscription::where('status', 'active')->sum('amount') * 12,
        ];

        // Revenue trend for last 6 months
        $revenueLabels = [];
        $revenueData = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $revenueLabels[] = $date->format('M Y');
            $revenueData[] = TenantPayment::where('status', 'completed')
                ->whereMonth('paid_at', $date->month)
                ->whereYear('paid_at', $date->year)
                ->sum('amount');
        }

        // Recent payments
        $recentPayments = TenantPayment::with('tenant')
            ->where('status', 'completed')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('super-admin.financial.index', compact(
            'stats',
            'revenueLabels',
            'revenueData',
            'recentPayments'
        ));
    }
}
