<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\TenantPayment;
use App\Models\Tenant;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = TenantPayment::with(['tenant', 'subscription']);

        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('method')) {
            $query->where('payment_method', $request->method);
        }

        if ($request->filled('search')) {
            $query->whereHas('tenant', function($q) use ($request) {
                $q->where('business_name', 'like', '%' . $request->search . '%')
                  ->orWhere('tenant_id', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $payments = $query->latest()->paginate(20);

        // Statistics
        $stats = [
            'total_revenue' => TenantPayment::where('status', 'completed')->sum('amount'),
            'pending_amount' => TenantPayment::where('status', 'pending')->sum('amount'),
            'this_month' => TenantPayment::where('status', 'completed')
                ->whereMonth('paid_at', now()->month)
                ->whereYear('paid_at', now()->year)
                ->sum('amount'),
            'today' => TenantPayment::where('status', 'completed')
                ->whereDate('paid_at', today())
                ->sum('amount'),
        ];

        return view('super-admin.payments.index', compact('payments', 'stats'));
    }

    public function show(TenantPayment $payment)
    {
        $payment->load(['tenant', 'subscription.plan', 'invoice']);

        return view('super-admin.payments.show', compact('payment'));
    }
}







