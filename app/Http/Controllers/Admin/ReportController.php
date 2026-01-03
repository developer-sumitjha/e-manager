<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Date range (default to last 30 days)
        $dateFrom = $request->input('date_from', now()->subDays(30)->format('Y-m-d'));
        $dateTo = $request->input('date_to', now()->format('Y-m-d'));

        // Sales Overview
        $totalSales = Order::whereBetween('created_at', [$dateFrom, $dateTo])
            ->where('payment_status', 'paid')
            ->sum('total');

        $totalOrders = Order::whereBetween('created_at', [$dateFrom, $dateTo])->count();

        $completedOrders = Order::whereBetween('created_at', [$dateFrom, $dateTo])
            ->where('status', 'completed')
            ->count();

        $pendingOrders = Order::whereBetween('created_at', [$dateFrom, $dateTo])
            ->where('status', 'pending')
            ->count();

        $averageOrderValue = $totalOrders > 0 ? $totalSales / $totalOrders : 0;

        // Top Selling Products (ONLY_FULL_GROUP_BY safe)
        $topProducts = Product::query()
            ->select('products.id', 'products.name', 'products.sku', 'products.price', 'products.category_id', 'products.tenant_id')
            ->selectRaw('SUM(order_items.quantity) as total_sold')
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereBetween('orders.created_at', [$dateFrom, $dateTo])
            ->groupBy('products.id', 'products.name', 'products.sku', 'products.price', 'products.category_id', 'products.tenant_id')
            ->orderByDesc('total_sold')
            ->limit(10)
            ->get();

        // Sales by Category
        $salesByCategory = Category::select('categories.name', DB::raw('SUM(order_items.total) as total_sales'))
            ->join('products', 'categories.id', '=', 'products.category_id')
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereBetween('orders.created_at', [$dateFrom, $dateTo])
            ->where('orders.payment_status', 'paid')
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('total_sales', 'desc')
            ->get();

        // Daily Sales (last 7 days)
        $dailySales = Order::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as orders'),
                DB::raw('SUM(total) as revenue')
            )
            ->whereBetween('created_at', [now()->subDays(7), now()])
            ->where('payment_status', 'paid')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        // Top Customers (ONLY_FULL_GROUP_BY safe)
        $topCustomers = User::query()
            ->select('users.id', 'users.name', 'users.email', 'users.phone', 'users.tenant_id')
            ->selectRaw('COUNT(orders.id) as order_count, SUM(orders.total) as total_spent')
            ->join('orders', 'users.id', '=', 'orders.user_id')
            ->whereBetween('orders.created_at', [$dateFrom, $dateTo])
            ->where('orders.payment_status', 'paid')
            ->groupBy('users.id', 'users.name', 'users.email', 'users.phone', 'users.tenant_id')
            ->orderByDesc('total_spent')
            ->limit(10)
            ->get();

        // Low Stock Products
        $lowStockProducts = Product::where('stock', '<', 20)->where('stock', '>', 0)->orderBy('stock', 'asc')->limit(10)->get();

        $outOfStock = Product::where('stock', 0)->count();

        return view('admin.reports.index', compact(
            'totalSales', 'totalOrders', 'completedOrders', 'pendingOrders',
            'averageOrderValue', 'topProducts', 'salesByCategory', 'dailySales',
            'topCustomers', 'lowStockProducts', 'outOfStock', 'dateFrom', 'dateTo'
        ));
    }

    public function export(Request $request)
    {
        $dateFrom = $request->input('date_from', now()->subDays(30)->format('Y-m-d'));
        $dateTo = $request->input('date_to', now()->format('Y-m-d'));

        $orders = Order::with(['user', 'orderItems.product'])
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->get();

        $filename = 'orders_' . $dateFrom . '_to_' . $dateTo . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($orders) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Order Number', 'Customer', 'Email', 'Total', 'Status', 'Payment Status', 'Date']);

            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->order_number,
                    $order->user->name,
                    $order->user->email,
                    $order->total,
                    $order->status,
                    $order->payment_status,
                    $order->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
