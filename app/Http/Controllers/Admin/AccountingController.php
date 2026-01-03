<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\Account;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;

class AccountingController extends Controller
{
    public function index(Request $request)
    {
        $activeTab = $request->get('tab', 'dashboard');
        
        // Calculate financial metrics for current month
        $currentMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        
        // Total Revenue (from delivered orders)
        $totalRevenue = Order::where('status', 'completed')
            ->where('payment_status', 'paid')
            ->whereBetween('created_at', [$currentMonth, $endOfMonth])
            ->sum('total');
        
        // Total Expenses (from transactions with type 'expense')
        $totalExpenses = Transaction::where('type', 'expense')
            ->whereBetween('created_at', [$currentMonth, $endOfMonth])
            ->sum('amount');
        
        // Net Income
        $netIncome = $totalRevenue - $totalExpenses;
        
        // Cash Balance (sum of all cash transactions)
        $cashBalance = Transaction::where('account_type', 'cash')
            ->sum('amount');
        
        // Profit margin calculation
        $profitMargin = $totalRevenue > 0 ? round(($netIncome / $totalRevenue) * 100, 2) : 0;
        
        // Recent transactions
        $recentTransactions = Transaction::with('account')
            ->latest()
            ->take(10)
            ->get();
        
        // Order-Sales Sync Status
        $totalOrders = Order::count();
        $syncedOrders = Order::where('status', 'completed')->count();
        $syncPercentage = $totalOrders > 0 ? round(($syncedOrders / $totalOrders) * 100, 2) : 0;
        
        // Monthly revenue trend (last 6 months)
        $monthlyRevenue = Order::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('YEAR(created_at) as year'),
                DB::raw('SUM(total) as revenue')
            )
            ->where('status', 'completed')
            ->where('payment_status', 'paid')
            ->where('created_at', '>=', Carbon::now()->subMonths(6))
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();
        
        $metrics = [
            'total_revenue' => $totalRevenue,
            'total_expenses' => $totalExpenses,
            'net_income' => $netIncome,
            'cash_balance' => $cashBalance,
            'profit_margin' => $profitMargin,
            'sync_percentage' => $syncPercentage,
            'synced_orders' => $syncedOrders,
            'total_orders' => $totalOrders,
        ];
        
        return view('admin.accounting.index', compact(
            'activeTab',
            'metrics',
            'recentTransactions',
            'monthlyRevenue'
        ));
    }
    
    public function accounts(Request $request)
    {
        $query = Account::query();
        
        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('account_number', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        // Type filter
        if ($request->has('type') && $request->type != '') {
            $query->where('type', $request->type);
        }
        
        $accounts = $query->latest()->paginate(15);
        
        return view('admin.accounting.accounts', compact('accounts'));
    }
    
    public function sales(Request $request)
    {
        $query = Order::with(['user', 'orderItems.product'])
            ->where('status', 'completed')
            ->where('payment_status', 'paid');
        
        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }
        
        // Date range filter
        if ($request->has('date_from') && $request->date_from != '') {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to != '') {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        $sales = $query->latest()->paginate(15);
        
        return view('admin.accounting.sales', compact('sales'));
    }
    
    public function purchases(Request $request)
    {
        $query = Transaction::with('account')
            ->where('type', 'purchase');
        
        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('reference', 'like', "%{$search}%");
            });
        }
        
        // Date range filter
        if ($request->has('date_from') && $request->date_from != '') {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to != '') {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        $purchases = $query->latest()->paginate(15);
        
        return view('admin.accounting.purchases', compact('purchases'));
    }
    
    public function expenses(Request $request)
    {
        $query = Transaction::with('account')
            ->where('type', 'expense');
        
        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('reference', 'like', "%{$search}%");
            });
        }
        
        // Date range filter
        if ($request->has('date_from') && $request->date_from != '') {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to != '') {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        $expenses = $query->latest()->paginate(15);
        
        return view('admin.accounting.expenses', compact('expenses'));
    }
    
    public function ledger(Request $request)
    {
        $query = Transaction::with('account');
        
        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('reference', 'like', "%{$search}%")
                  ->orWhereHas('account', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }
        
        // Account filter
        if ($request->has('account_id') && $request->account_id != '') {
            $query->where('account_id', $request->account_id);
        }
        
        // Date range filter
        if ($request->has('date_from') && $request->date_from != '') {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to != '') {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        $transactions = $query->latest()->paginate(20);
        $accounts = Account::orderBy('name')->get();
        
        return view('admin.accounting.ledger', compact('transactions', 'accounts'));
    }
    
    public function payments(Request $request)
    {
        $query = Payment::with(['order.user']);
        
        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('payment_number', 'like', "%{$search}%")
                  ->orWhereHas('order', function($q) use ($search) {
                      $q->where('order_number', 'like', "%{$search}%")
                        ->orWhereHas('user', function($q) use ($search) {
                            $q->where('name', 'like', "%{$search}%")
                              ->orWhere('email', 'like', "%{$search}%");
                        });
                  });
            });
        }
        
        // Status filter
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        // Date range filter
        if ($request->has('date_from') && $request->date_from != '') {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to != '') {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        $payments = $query->latest()->paginate(15);
        
        return view('admin.accounting.payments', compact('payments'));
    }
    
    public function reports(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));
        
        // Financial Summary
        $totalRevenue = Order::where('status', 'completed')
            ->where('payment_status', 'paid')
            ->whereBetween('created_at', [Carbon::parse($startDate), Carbon::parse($endDate)->endOfDay()])
            ->sum('total');
        
        $totalExpenses = Transaction::where('type', 'expense')
            ->whereBetween('created_at', [Carbon::parse($startDate), Carbon::parse($endDate)->endOfDay()])
            ->sum('amount');
        
        $totalPurchases = Transaction::where('type', 'purchase')
            ->whereBetween('created_at', [Carbon::parse($startDate), Carbon::parse($endDate)->endOfDay()])
            ->sum('amount');
        
        $netIncome = $totalRevenue - $totalExpenses - $totalPurchases;
        
        // Daily revenue trend
        $dailyRevenue = Order::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total) as revenue')
            )
            ->where('status', 'completed')
            ->where('payment_status', 'paid')
            ->whereBetween('created_at', [Carbon::parse($startDate), Carbon::parse($endDate)->endOfDay()])
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        // Top selling products
        $topProducts = DB::table('order_items')
            ->select('products.name', DB::raw('SUM(order_items.quantity) as total_sold'), DB::raw('SUM(order_items.total) as total_revenue'))
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', 'completed')
            ->where('orders.payment_status', 'paid')
            ->whereBetween('orders.created_at', [Carbon::parse($startDate), Carbon::parse($endDate)->endOfDay()])
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_revenue')
            ->take(10)
            ->get();
        
        return view('admin.accounting.reports', compact(
            'totalRevenue',
            'totalExpenses',
            'totalPurchases',
            'netIncome',
            'dailyRevenue',
            'topProducts',
            'startDate',
            'endDate'
        ));
    }
    
    public function exportReports(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));
        $format = $request->get('format', 'csv');
        
        // Get financial data
        $orders = Order::with(['user', 'orderItems.product'])
            ->whereBetween('created_at', [Carbon::parse($startDate), Carbon::parse($endDate)->endOfDay()])
            ->get();
        
        $transactions = Transaction::with('account')
            ->whereBetween('created_at', [Carbon::parse($startDate), Carbon::parse($endDate)->endOfDay()])
            ->get();
        
        if ($format === 'csv') {
            $filename = 'accounting_report_' . $startDate . '_' . $endDate . '.csv';
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];
            
            $callback = function() use ($orders, $transactions) {
                $file = fopen('php://output', 'w');
                
                // Export orders
                fputcsv($file, ['ORDERS REPORT']);
                fputcsv($file, [
                    'Order ID', 'Order Number', 'Customer Name', 'Customer Email',
                    'Total Amount', 'Status', 'Payment Status', 'Payment Method', 'Created At'
                ]);
                
                foreach ($orders as $order) {
                    fputcsv($file, [
                        $order->id,
                        $order->order_number,
                        $order->user->name ?? 'N/A',
                        $order->user->email ?? 'N/A',
                        $order->total,
                        $order->status,
                        $order->payment_status,
                        $order->payment_method ?? 'N/A',
                        $order->created_at
                    ]);
                }
                
                fputcsv($file, []); // Empty line
                
                // Export transactions
                fputcsv($file, ['TRANSACTIONS REPORT']);
                fputcsv($file, [
                    'Transaction ID', 'Account', 'Type', 'Amount', 'Description', 'Reference', 'Created At'
                ]);
                
                foreach ($transactions as $transaction) {
                    fputcsv($file, [
                        $transaction->id,
                        $transaction->account->name ?? 'N/A',
                        $transaction->type,
                        $transaction->amount,
                        $transaction->description,
                        $transaction->reference ?? 'N/A',
                        $transaction->created_at
                    ]);
                }
                
                fclose($file);
            };
            
            return Response::stream($callback, 200, $headers);
        }
        
        return redirect()->back()->with('error', 'Export format not supported.');
    }
    
    public function quickEntry(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:income,expense,purchase',
            'account_id' => 'required|exists:accounts,id',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'required|string|max:255',
            'reference' => 'nullable|string|max:100',
            'date' => 'required|date',
        ]);
        
        Transaction::create([
            'account_id' => $validated['account_id'],
            'type' => $validated['type'],
            'amount' => $validated['amount'],
            'description' => $validated['description'],
            'reference' => $validated['reference'],
            'transaction_date' => $validated['date'],
        ]);
        
        return redirect()->back()->with('success', 'Transaction added successfully.');
    }
    
    // Account CRUD Methods
    public function createAccount()
    {
        return view('admin.accounting.accounts-create');
    }
    
    public function storeAccount(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:asset,liability,equity,revenue,expense',
            'code' => 'nullable|string|max:50|unique:accounts,code',
            'description' => 'nullable|string',
            'opening_balance' => 'nullable|numeric',
        ]);
        
        Account::create($validated);
        
        return redirect()->route('admin.accounting.accounts')->with('success', 'Account created successfully.');
    }
    
    public function editAccount(Account $account)
    {
        return view('admin.accounting.accounts-edit', compact('account'));
    }
    
    public function updateAccount(Request $request, Account $account)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:asset,liability,equity,revenue,expense',
            'code' => 'nullable|string|max:50|unique:accounts,code,' . $account->id,
            'description' => 'nullable|string',
        ]);
        
        $account->update($validated);
        
        return redirect()->route('admin.accounting.accounts')->with('success', 'Account updated successfully.');
    }
    
    public function destroyAccount(Account $account)
    {
        try {
            // Check if account has transactions
            if ($account->transactions()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete account with existing transactions. Please transfer or delete transactions first.'
                ], 400);
            }
            
            $account->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Account deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete account: ' . $e->getMessage()
            ], 500);
        }
    }
    
    // Invoice CRUD Methods
    public function createInvoice()
    {
        $customers = \App\Models\User::where('role', 'customer')->get();
        return view('admin.accounting.invoice-create', compact('customers'));
    }
    
    public function storeInvoice(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:users,id',
            'invoice_number' => 'required|string|unique:invoices,invoice_number',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date',
            'items' => 'required|array',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);
        
        $subtotal = 0;
        foreach ($validated['items'] as $item) {
            $subtotal += $item['quantity'] * $item['price'];
        }
        
        $invoice = \App\Models\Invoice::create([
            'customer_id' => $validated['customer_id'],
            'invoice_number' => $validated['invoice_number'],
            'invoice_date' => $validated['invoice_date'],
            'due_date' => $validated['due_date'],
            'subtotal' => $subtotal,
            'tax' => $subtotal * 0.1, // 10% tax
            'total' => $subtotal * 1.1,
            'notes' => $validated['notes'] ?? null,
            'status' => 'pending',
        ]);
        
        return redirect()->route('admin.accounting.sales')->with('success', 'Invoice created successfully.');
    }
    
    public function showInvoice(\App\Models\Invoice $invoice)
    {
        $invoice->load('customer');
        return view('admin.accounting.invoice-show', compact('invoice'));
    }
    
    public function editInvoice(\App\Models\Invoice $invoice)
    {
        $customers = \App\Models\User::where('role', 'customer')->get();
        return view('admin.accounting.invoice-edit', compact('invoice', 'customers'));
    }
    
    public function updateInvoice(Request $request, \App\Models\Invoice $invoice)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,paid,cancelled',
            'notes' => 'nullable|string',
        ]);
        
        $invoice->update($validated);
        
        return redirect()->route('admin.accounting.sales')->with('success', 'Invoice updated successfully.');
    }
    
    public function destroyInvoice(\App\Models\Invoice $invoice)
    {
        $invoice->delete();
        
        return redirect()->route('admin.accounting.sales')->with('success', 'Invoice deleted successfully.');
    }
    
    // Purchase CRUD Methods
    public function createPurchase()
    {
        $accounts = Account::where('type', 'expense')->get();
        return view('admin.accounting.purchase-create', compact('accounts'));
    }
    
    public function storePurchase(Request $request)
    {
        $validated = $request->validate([
            'vendor_name' => 'required|string|max:255',
            'purchase_date' => 'required|date',
            'account_id' => 'required|exists:accounts,id',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'required|string',
            'reference' => 'nullable|string|max:100',
        ]);
        
        Transaction::create([
            'account_id' => $validated['account_id'],
            'type' => 'purchase',
            'amount' => $validated['amount'],
            'description' => $validated['description'] . ' - Vendor: ' . $validated['vendor_name'],
            'reference' => $validated['reference'],
            'transaction_date' => $validated['purchase_date'],
        ]);
        
        return redirect()->route('admin.accounting.purchases')->with('success', 'Purchase recorded successfully.');
    }
    
    public function editPurchase(Transaction $purchase)
    {
        $accounts = Account::where('type', 'expense')->get();
        return view('admin.accounting.purchase-edit', compact('purchase', 'accounts'));
    }
    
    public function updatePurchase(Request $request, Transaction $purchase)
    {
        $validated = $request->validate([
            'account_id' => 'required|exists:accounts,id',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'required|string',
            'reference' => 'nullable|string|max:100',
            'transaction_date' => 'required|date',
        ]);
        
        $purchase->update($validated);
        
        return redirect()->route('admin.accounting.purchases')->with('success', 'Purchase updated successfully.');
    }
    
    public function destroyPurchase(Transaction $purchase)
    {
        $purchase->delete();
        
        return redirect()->route('admin.accounting.purchases')->with('success', 'Purchase deleted successfully.');
    }
    
    // Expense Methods
    public function createExpense()
    {
        $accounts = Account::where('type', 'expense')->get();
        return view('admin.accounting.expense-create', compact('accounts'));
    }
    
    public function storeExpense(Request $request)
    {
        $validated = $request->validate([
            'account_id' => 'required|exists:accounts,id',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'required|string',
            'reference' => 'nullable|string|max:100',
            'transaction_date' => 'required|date',
        ]);
        
        Transaction::create([
            'account_id' => $validated['account_id'],
            'type' => 'expense',
            'amount' => $validated['amount'],
            'description' => $validated['description'],
            'reference' => $validated['reference'],
            'transaction_date' => $validated['transaction_date'],
        ]);
        
        return redirect()->route('admin.accounting.expenses')->with('success', 'Expense recorded successfully.');
    }
    
    public function editExpense(Transaction $expense)
    {
        $accounts = Account::where('type', 'expense')->get();
        return view('admin.accounting.expense-edit', compact('expense', 'accounts'));
    }
    
    public function updateExpense(Request $request, Transaction $expense)
    {
        $validated = $request->validate([
            'account_id' => 'required|exists:accounts,id',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'required|string',
            'reference' => 'nullable|string|max:100',
            'transaction_date' => 'required|date',
        ]);
        
        $expense->update($validated);
        
        return redirect()->route('admin.accounting.expenses')->with('success', 'Expense updated successfully.');
    }
    
    public function destroyExpense(Transaction $expense)
    {
        $expense->delete();
        
        return redirect()->route('admin.accounting.expenses')->with('success', 'Expense deleted successfully.');
    }
    
    // Payment Methods
    public function storePayment(Request $request)
    {
        $validated = $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|string',
            'payment_date' => 'required|date',
            'reference' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);
        
        \App\Models\Payment::create($validated);
        
        // Update invoice status if fully paid
        $invoice = \App\Models\Invoice::find($validated['invoice_id']);
        $totalPaid = $invoice->payments()->sum('amount');
        if ($totalPaid >= $invoice->total) {
            $invoice->update(['status' => 'paid']);
        }
        
        return redirect()->route('admin.accounting.payments')->with('success', 'Payment recorded successfully.');
    }
}

