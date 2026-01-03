<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class PendingOrderController extends Controller
{
    public function index(Request $request)
    {
        // Build base query with eager loading
        $query = Order::with(['user:id,name,email,phone', 'orderItems:id,order_id,product_id,quantity,price,total'])
                     ->where('status', 'pending');

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by order type (manual orders)
        $query->where('is_manual', true);

        $orders = $query->latest()->paginate(10);
        
        // Get counts for stats using a single query with caching
        $stats = Cache::remember('pending_order_stats', 300, function() {
            return Order::selectRaw('
                COUNT(*) as total_pending,
                SUM(CASE WHEN is_manual = 1 THEN 1 ELSE 0 END) as manual_orders
            ')
            ->where('status', 'pending')
            ->first();
        });
        
        return view('admin.pending-orders.index', [
            'orders' => $orders,
            'totalPendingOrders' => $stats->total_pending ?? 0,
            'manualOrdersCount' => $stats->manual_orders ?? 0,
        ]);
    }

    public function create()
    {
        $users = User::where('role', 'user')->get();
        $products = Product::where('is_active', true)->get();
        return view('admin.pending-orders.create', compact('users', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'receiver_city' => 'required|string|max:255',
            'receiver_area' => 'required|string|max:255',
            'shipping_address' => 'required|string',
            'payment_method' => 'required|in:cash_on_delivery,paid,cod,bank_transfer,khalti,esewa',
            'total_amount' => 'required|numeric|min:0',
            'product_ids' => 'required|array|min:1',
            'product_ids.*' => 'required|exists:products,id',
            'quantities' => 'required|array|min:1',
            'quantities.*' => 'required|integer|min:1',
            'notes' => 'nullable|string',
            // Gaaubesi-specific fields
            'delivery_branch' => 'nullable|string|max:255',
            'package_access' => 'nullable|in:Can\'t Open,Can Open',
            'delivery_type' => 'nullable|in:Pickup,Drop Off',
            'package_type' => 'nullable|string|max:255',
            'sender_name' => 'nullable|string|max:255',
            'sender_phone' => 'nullable|string|max:20',
            'delivery_instructions' => 'nullable|string',
        ]);

        // Find or create guest user for manual orders
        $user = User::firstOrCreate(
            ['phone' => $validated['customer_phone']],
            [
                'name' => $validated['customer_name'],
                'email' => strtolower(str_replace(' ', '', $validated['customer_name'])) . '@guest.local',
                'password' => \Hash::make(\Str::random(16)),
                'role' => 'user',
            ]
        );

        // Generate order number
        $orderNumber = 'PND-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);

        // Create the order with all Gaaubesi-compliant fields
        $order = Order::create([
            'user_id' => $user->id,
            'order_number' => $orderNumber,
            'subtotal' => $validated['total_amount'],
            'tax' => 0,
            'shipping' => 0,
            'total' => $validated['total_amount'],
            'status' => 'pending',
            'payment_status' => in_array($validated['payment_method'], ['paid', 'bank_transfer', 'khalti', 'esewa']) ? 'paid' : 'unpaid',
            'payment_method' => $validated['payment_method'],
            'shipping_address' => $validated['shipping_address'],
            'notes' => $validated['notes'] ?? '',
            'is_manual' => true,
            'created_by' => auth()->id(),
            // Gaaubesi-compliant fields
            'receiver_name' => $validated['customer_name'],
            'receiver_phone' => $validated['customer_phone'],
            'receiver_city' => $validated['receiver_city'],
            'receiver_area' => $validated['receiver_area'],
            'receiver_full_address' => $validated['shipping_address'],
            'delivery_branch' => $validated['delivery_branch'] ?? 'HEAD OFFICE',
            'package_access' => $validated['package_access'] ?? "Can't Open",
            'delivery_type' => $validated['delivery_type'] ?? 'Drop Off',
            'package_type' => $validated['package_type'] ?? null,
            'sender_name' => $validated['sender_name'] ?? config('app.name', 'E-Manager Store'),
            'sender_phone' => $validated['sender_phone'] ?? null,
            'delivery_instructions' => $validated['delivery_instructions'] ?? null,
        ]);

        // Add order items
        foreach ($validated['product_ids'] as $index => $productId) {
            $product = Product::find($productId);
            $quantity = $validated['quantities'][$index];
            
            $order->orderItems()->create([
                'product_id' => $product->id,
                'product_name' => $product->name,
                'quantity' => $quantity,
                'price' => $product->price,
                'total' => $product->price * $quantity
            ]);
        }

        return redirect()->route('admin.pending-orders.index')->with('success', 'Manual order created successfully.');
    }

    public function show(Order $order)
    {
        $order->load(['user', 'orderItems.product']);
        return view('admin.pending-orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        $users = User::where('role', 'user')->get();
        $products = Product::where('is_active', true)->get();
        $order->load(['user', 'orderItems.product']);
        return view('admin.pending-orders.edit', compact('order', 'users', 'products'));
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'shipping_address' => 'required|string',
            'payment_method' => 'required|in:cash_on_delivery,paid',
            'total_amount' => 'required|numeric|min:0',
            'status' => 'required|in:pending,processing,completed,cancelled',
            'notes' => 'nullable|string'
        ]);

        $order->update([
            'total' => $validated['total_amount'],
            'status' => $validated['status'],
            'payment_status' => $validated['payment_method'] === 'paid' ? 'paid' : 'unpaid',
            'payment_method' => $validated['payment_method'],
            'shipping_address' => $validated['shipping_address'],
            'notes' => $validated['notes'] ?? ''
        ]);

        return redirect()->route('admin.pending-orders.index')->with('success', 'Order updated successfully.');
    }

    public function destroy(Order $order)
    {
        $order->orderItems()->delete();
        $order->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Order deleted successfully.']);
        }

        return redirect()->route('admin.pending-orders.index')->with('success', 'Order deleted successfully.');
    }

    public function confirm(Order $pending_order)
    {
        try {
            \Log::info('Confirm order request received', [
                'order_id' => $pending_order->id,
                'order_number' => $pending_order->order_number,
                'current_status' => $pending_order->status,
                'is_ajax' => request()->ajax()
            ]);

            if ($pending_order->status !== 'pending') {
                \Log::warning('Attempt to confirm non-pending order', [
                    'order_id' => $pending_order->id,
                    'current_status' => $pending_order->status
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Only pending orders can be confirmed.'
                ], 400);
            }

            $result = $pending_order->update(['status' => 'confirmed']);
            
            \Log::info('Order status update result', [
                'order_id' => $pending_order->id,
                'update_result' => $result,
                'new_status' => $pending_order->fresh()->status
            ]);

            \Log::info('Order confirmed successfully', ['order_id' => $pending_order->id, 'order_number' => $pending_order->order_number]);

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Order confirmed successfully.',
                    'order_id' => $pending_order->id,
                    'order_number' => $pending_order->order_number
                ]);
            }

            return redirect()->route('admin.pending-orders.index')->with('success', 'Order confirmed successfully.');
        } catch (\Exception $e) {
            \Log::error('Error confirming order', [
                'order_id' => $pending_order->id ?? 'unknown',
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error: ' . $e->getMessage(),
                    'error_details' => [
                        'file' => $e->getFile(),
                        'line' => $e->getLine()
                    ]
                ], 500);
            }

            return redirect()->back()->with('error', 'Error confirming order: ' . $e->getMessage());
        }
    }

    public function reject(Order $pending_order)
    {
        try {
            if ($pending_order->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only pending orders can be rejected.'
                ], 400);
            }

            $pending_order->update(['status' => 'rejected']);
            
            \Log::info('Order rejected successfully', ['order_id' => $pending_order->id, 'order_number' => $pending_order->order_number]);

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Order rejected successfully.'
                ]);
            }

            return redirect()->route('admin.pending-orders.index')->with('success', 'Order rejected successfully.');
        } catch (\Exception $e) {
            \Log::error('Error rejecting order', [
                'order_id' => $pending_order->id ?? 'unknown',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()->with('error', 'Error rejecting order: ' . $e->getMessage());
        }
    }

    public function rejectedOrders(Request $request)
    {
        $query = Order::with(['user', 'orderItems.product'])->where('status', 'rejected');

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

        $orders = $query->latest()->paginate(10);
        
        // Get counts for stats
        $totalRejectedOrders = Order::where('status', 'rejected')->count();
        
        return view('admin.pending-orders.rejected', compact('orders', 'totalRejectedOrders'));
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:confirm,reject,delete',
            'order_ids' => 'required|array|min:1',
            'order_ids.*' => 'exists:orders,id'
        ]);

        $action = $request->action;
        $orderIds = $request->order_ids;
        $count = 0;

        switch ($action) {
            case 'confirm':
                $count = Order::whereIn('id', $orderIds)
                            ->where('status', 'pending')
                            ->update(['status' => 'confirmed']);
                $message = "{$count} orders confirmed successfully.";
                break;
                
            case 'reject':
                $count = Order::whereIn('id', $orderIds)
                            ->where('status', 'pending')
                            ->update(['status' => 'rejected']);
                $message = "{$count} orders rejected successfully.";
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

    public function createBulk()
    {
        $users = User::where('role', 'user')->get();
        $products = Product::where('is_active', true)->get();
        return view('admin.pending-orders.create-bulk', compact('users', 'products'));
    }

    public function storeBulk(Request $request)
    {
        $validated = $request->validate([
            'orders' => 'required|array|min:1',
            'orders.*.customer_name' => 'required|string|max:255',
            'orders.*.customer_phone' => 'required|string|max:20',
            'orders.*.shipping_address' => 'nullable|string|max:255',
            'orders.*.payment_method' => 'nullable|in:cash_on_delivery,bank_transfer,khalti,esewa',
            'orders.*.products' => 'required|array|min:1',
            'orders.*.products.*.id' => 'required|exists:products,id',
            'orders.*.products.*.quantity' => 'required|integer|min:1',
            'orders.*.notes' => 'nullable|string',
            'default_notes' => 'nullable|string',
        ]);

        $createdCount = 0;
        $defaultNotes = $validated['default_notes'] ?? '';

        foreach ($validated['orders'] as $orderData) {
            // Create a dummy user if not exists
            $user = User::firstOrCreate(
                ['email' => Str::slug($orderData['customer_name']) . '@example.com'],
                [
                    'name' => $orderData['customer_name'],
                    'password' => bcrypt(Str::random(10)),
                    'role' => 'user',
                ]
            );

            $orderNumber = 'BULK-' . str_pad(Order::max('id') + 1, 4, '0', STR_PAD_LEFT);
            $subtotal = 0;

            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => $orderNumber,
                'subtotal' => 0, // Will be updated
                'tax' => 0,
                'shipping' => 0,
                'total' => 0, // Will be updated
                'status' => 'pending',
                'payment_status' => 'unpaid',
                'payment_method' => $orderData['payment_method'] ?? 'cash_on_delivery',
                'shipping_address' => $orderData['shipping_address'] ?? 'To be updated',
                'notes' => $orderData['notes'] ?? $defaultNotes,
                'is_manual' => true,
                'created_by' => Auth::id(),
            ]);

            foreach ($orderData['products'] as $productData) {
                $product = Product::find($productData['id']);
                $quantity = $productData['quantity'];
                $itemPrice = $product->sale_price ?? $product->price;
                $itemTotal = $itemPrice * $quantity;

                $order->orderItems()->create([
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $itemPrice,
                    'total' => $itemTotal,
                ]);
                $subtotal += $itemTotal;
            }

            $order->update([
                'subtotal' => $subtotal,
                'total' => $subtotal, // For simplicity, no tax/shipping for now
            ]);

            $createdCount++;
        }

        return response()->json([
            'success' => true,
            'message' => "Successfully created {$createdCount} bulk orders.",
            'count' => $createdCount
        ]);
    }
}