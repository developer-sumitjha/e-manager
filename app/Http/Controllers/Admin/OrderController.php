<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        // Build base query with eager loading
        $query = Order::with(['user:id,name,email,phone', 'orderItems.product:id,name']);

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

        // Status filter for processed orders
        if ($request->has('status') && $request->status != '' && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        // Exclude pending and rejected orders (confirmed orders only)
        $query->whereNotIn('status', ['pending', 'rejected']);

        // Payment status filter
        if ($request->has('payment_status') && $request->payment_status != '') {
            $query->where('payment_status', $request->payment_status);
        }

        // Date range filter
        if ($request->has('date_from') && $request->date_from != '') {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to != '') {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->latest()->paginate(10);
        
        // Get counts for filter tabs using a single query with conditional aggregation
        // Cache the counts for 5 minutes to improve performance
        $counts = Cache::remember('order_counts_' . md5(serialize($request->only(['search', 'payment_status', 'date_from', 'date_to']))), 300, function() {
            return Order::selectRaw('
                COUNT(*) as all_orders,
                SUM(CASE WHEN status = "confirmed" THEN 1 ELSE 0 END) as confirmed,
                SUM(CASE WHEN status = "processing" THEN 1 ELSE 0 END) as processing,
                SUM(CASE WHEN status = "shipped" THEN 1 ELSE 0 END) as shipped,
                SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as delivered,
                SUM(CASE WHEN status = "cancelled" THEN 1 ELSE 0 END) as cancelled
            ')
            ->whereNotIn('status', ['pending', 'rejected'])
            ->first();
        });
        
        return view('admin.orders.index', [
            'orders' => $orders,
            'allOrdersCount' => $counts->all_orders ?? 0,
            'confirmedCount' => $counts->confirmed ?? 0,
            'processingCount' => $counts->processing ?? 0,
            'shippedCount' => $counts->shipped ?? 0,
            'deliveredCount' => $counts->delivered ?? 0,
            'cancelledCount' => $counts->cancelled ?? 0,
        ]);
    }

    public function create()
    {
        // Load products for the dropdown (tenant scope is automatically applied)
        $products = Product::orderBy('name')
            ->get(['id', 'name', 'sku', 'price', 'sale_price']);
        
        return view('admin.orders.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'receiver_name' => 'required|string|max:255',
            'receiver_phone' => 'required|string|max:20',
            'receiver_city' => 'required|string|max:255',
            'receiver_area' => 'nullable|string|max:255',
            'receiver_full_address' => 'required|string',
            'payment_method' => 'required|in:cod,online,bank_transfer',
            'payment_status' => 'required|in:unpaid,paid,refunded',
            'status' => 'required|in:pending,confirmed,processing,shipped,completed,cancelled',
            'delivery_type' => 'nullable|in:standard,express',
            'notes' => 'nullable|string',
            'delivery_instructions' => 'nullable|string',
            'subtotal' => 'required|numeric|min:0',
            'shipping' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'nullable|exists:products,id',
            'items.*.product_name' => 'required|string|max:255',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);
        
        try {
            \DB::beginTransaction();
            
            // Get tenant_id from authenticated user
            $tenantId = auth()->user()->tenant_id;
            
            // Find or create customer user for manual orders
            // Check both phone and tenant_id to avoid matching users from other vendors
            $user = User::firstOrCreate(
                [
                    'phone' => $validated['receiver_phone'],
                    'tenant_id' => $tenantId,
                ],
                [
                    'name' => $validated['receiver_name'],
                    'email' => strtolower(str_replace(' ', '', $validated['receiver_name'])) . '@guest.local',
                    'password' => Hash::make(Str::random(16)),
                    'role' => 'customer',
                    'tenant_id' => $tenantId,
                    'is_active' => true,
                ]
            );
            
            // Update user name if it changed
            if ($user->name !== $validated['receiver_name']) {
                $user->update(['name' => $validated['receiver_name']]);
            }
            
            // Generate order number
            $lastOrder = Order::latest()->first();
            $nextNumber = $lastOrder ? intval(substr($lastOrder->order_number, 4)) + 1 : 1;
            $orderNumber = 'ORD-' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
            
            // Create order
            $order = Order::create([
                'tenant_id' => $tenantId,
                'user_id' => $user->id,
                'order_number' => $orderNumber,
                'subtotal' => $validated['subtotal'],
                'tax' => $validated['tax'] ?? 0,
                'shipping' => $validated['shipping'] ?? 0,
                'total' => $validated['total'],
                'status' => $validated['status'],
                'payment_status' => $validated['payment_status'],
                'payment_method' => $validated['payment_method'],
                'notes' => $validated['notes'] ?? null,
                'is_manual' => true,
                'created_by' => auth()->id(),
                // Shipping details
                'receiver_name' => $validated['receiver_name'],
                'receiver_phone' => $validated['receiver_phone'],
                'receiver_city' => $validated['receiver_city'],
                'receiver_area' => $validated['receiver_area'] ?? null,
                'receiver_full_address' => $validated['receiver_full_address'],
                'delivery_type' => $validated['delivery_type'] ?? 'standard',
                'delivery_instructions' => $validated['delivery_instructions'] ?? null,
            ]);
            
            // Create order items
            foreach ($validated['items'] as $item) {
                \App\Models\OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'] ?? null, // Use product_id if provided
                    'product_name' => $item['product_name'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total' => $item['quantity'] * $item['price'],
                ]);
            }
            
            \DB::commit();
            
            return redirect()
                ->route('admin.orders.show', $order)
                ->with('success', 'Order created successfully!');
                
        } catch (\Exception $e) {
            \DB::rollBack();
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to create order: ' . $e->getMessage());
        }
    }

    public function show(Order $order)
    {
        $order->load('user', 'orderItems.product');
        return view('admin.orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        // Load products for the dropdown (tenant scope is automatically applied)
        $products = Product::orderBy('name')->get();
        $order->load('orderItems.product');
        return view('admin.orders.edit', compact('order', 'products'));
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,processing,shipped,completed,cancelled',
            'payment_status' => 'required|in:unpaid,paid,refunded',
            'payment_method' => 'nullable|in:cod,online,bank_transfer,khalti,esewa,cash_on_delivery,paid',
            'delivery_type' => 'nullable|in:standard,express',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'shipping_address' => 'required|string',
            'shipping_cost' => 'nullable|numeric|min:0',
            'tax_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'delivery_instructions' => 'nullable|string',
            'product_ids' => 'required|array|min:1',
            'product_ids.*' => 'required|exists:products,id',
            'quantities' => 'required|array|min:1',
            'quantities.*' => 'required|integer|min:1',
        ]);

        // Calculate subtotal from products
        $subtotal = 0;
        foreach ($validated['product_ids'] as $index => $productId) {
            $product = Product::find($productId);
            $quantity = $validated['quantities'][$index] ?? 1;
            $itemPrice = $product->sale_price ?? $product->price;
            $subtotal += $itemPrice * $quantity;
        }

        // Get shipping and tax amounts
        $shippingCost = $validated['shipping_cost'] ?? 0;
        $taxAmount = $validated['tax_amount'] ?? 0;
        $total = $subtotal + $shippingCost + $taxAmount;

        // Update order with customer details and recalculated totals
        $order->update([
            'status' => $validated['status'],
            'payment_status' => $validated['payment_status'],
            'payment_method' => $validated['payment_method'] ?? $order->payment_method,
            'delivery_type' => $validated['delivery_type'] ?? $order->delivery_type,
            'receiver_name' => $validated['customer_name'],
            'receiver_phone' => $validated['customer_phone'],
            'receiver_full_address' => $validated['shipping_address'],
            'shipping_address' => $validated['shipping_address'],
            'subtotal' => $subtotal,
            'shipping_cost' => $shippingCost,
            'tax_amount' => $taxAmount,
            'shipping' => $shippingCost, // Legacy field
            'tax' => $taxAmount, // Legacy field
            'total' => $total,
            'notes' => $validated['notes'] ?? $order->notes,
            'delivery_instructions' => $validated['delivery_instructions'] ?? $order->delivery_instructions,
        ]);
        
        // Delete existing order items
        $order->orderItems()->delete();
        
        // Create new order items
        foreach ($validated['product_ids'] as $index => $productId) {
            $product = Product::find($productId);
            $quantity = $validated['quantities'][$index] ?? 1;
            $itemPrice = $product->sale_price ?? $product->price;
            $itemTotal = $itemPrice * $quantity;

            $order->orderItems()->create([
                'product_id' => $product->id,
                'product_name' => $product->name,
                'quantity' => $quantity,
                'price' => $itemPrice,
                'total' => $itemTotal,
            ]);
        }
        
        // Update user if exists
        if ($order->user) {
            $order->user->update([
                'name' => $validated['customer_name'],
                'phone' => $validated['customer_phone'],
            ]);
        }
        
        // Clear storefront cache when order status changes
        Cache::forget("categories_{$order->tenant_id}");

        return redirect()->route('admin.orders.show', $order)->with('success', 'Order updated successfully.');
    }

    public function destroy(Order $order)
    {
        // Soft delete the order - don't delete order items
        // Order items will remain in the database and will be available when order is restored
        $order->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Order moved to trash successfully.']);
        }

        return redirect()->route('admin.orders.index')->with('success', 'Order moved to trash successfully.');
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:confirm,process,ship,deliver,cancel,delete',
            'order_ids' => 'required|array|min:1',
            'order_ids.*' => 'exists:orders,id'
        ]);

        $action = $request->action;
        $orderIds = $request->order_ids;
        $count = 0;

        switch ($action) {
            case 'confirm':
                $count = Order::whereIn('id', $orderIds)->update(['status' => 'confirmed']);
                $message = "{$count} orders confirmed.";
                break;
                
            case 'process':
                $count = Order::whereIn('id', $orderIds)->update(['status' => 'processing']);
                $message = "{$count} orders moved to processing.";
                break;
                
            case 'ship':
                $count = Order::whereIn('id', $orderIds)->update(['status' => 'shipped']);
                $message = "{$count} orders shipped.";
                break;
                
            case 'deliver':
                $count = Order::whereIn('id', $orderIds)->update(['status' => 'completed']);
                $message = "{$count} orders delivered.";
                break;
                
            case 'cancel':
                $count = Order::whereIn('id', $orderIds)->update(['status' => 'cancelled']);
                $message = "{$count} orders cancelled.";
                break;
                
            case 'delete':
                $orders = Order::whereIn('id', $orderIds)->get();
                foreach ($orders as $order) {
                    $order->orderItems()->delete();
                    $order->delete();
                    $count++;
                }
                $message = "{$count} orders deleted.";
                break;
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'count' => $count
        ]);
    }
    
    /**
     * Update order status via AJAX
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,processing,shipped,completed,cancelled',
            'payment_status' => 'required|in:unpaid,paid,refunded',
        ]);
        
        $order->update([
            'status' => $request->status,
            'payment_status' => $request->payment_status,
        ]);
        
        // Clear storefront cache
        Cache::forget("categories_{$order->tenant_id}");
        
        return response()->json([
            'success' => true,
            'message' => 'Order status updated successfully',
            'order' => $order->fresh()
        ]);
    }
    
    /**
     * Export order to PDF
     */
    public function export(Order $order)
    {
        $order->load(['user', 'orderItems.product']);
        
        // For now, return a simple view that can be printed
        return view('admin.orders.export', compact('order'));
    }
}
