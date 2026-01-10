<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class PendingOrderController extends Controller
{
    public function index(Request $request)
    {
        // Build base query with eager loading
        $query = Order::with(['user:id,name,email,phone', 'orderItems.product:id,name'])
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

    public function show(Order $pending_order)
    {
        $pending_order->load(['user', 'orderItems.product']);
        return view('admin.pending-orders.show', compact('pending_order'));
    }

    public function edit(Order $pending_order)
    {
        $users = User::where('role', 'user')->get();
        $products = Product::where('is_active', true)->get();
        $pending_order->load(['user', 'orderItems.product']);
        return view('admin.pending-orders.edit', compact('pending_order', 'users', 'products'));
    }

    public function update(Request $request, Order $pending_order)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'shipping_address' => 'required|string',
            'payment_method' => 'required|in:cash_on_delivery,paid,bank_transfer,khalti,esewa,cod',
            'status' => 'required|in:pending,processing,completed,cancelled',
            'notes' => 'nullable|string',
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

        $pending_order->update([
            'subtotal' => $subtotal,
            'total' => $subtotal, // For now, total equals subtotal
            'status' => $validated['status'],
            'payment_status' => in_array($validated['payment_method'], ['paid', 'bank_transfer', 'khalti', 'esewa']) ? 'paid' : 'unpaid',
            'payment_method' => $validated['payment_method'],
            'shipping_address' => $validated['shipping_address'],
            'receiver_full_address' => $validated['shipping_address'],
            'receiver_name' => $validated['customer_name'],
            'receiver_phone' => $validated['customer_phone'],
            'notes' => $validated['notes'] ?? ''
        ]);
        
        // Delete existing order items
        $pending_order->orderItems()->delete();
        
        // Create new order items
        foreach ($validated['product_ids'] as $index => $productId) {
            $product = Product::find($productId);
            $quantity = $validated['quantities'][$index] ?? 1;
            $itemPrice = $product->sale_price ?? $product->price;
            $itemTotal = $itemPrice * $quantity;

            $pending_order->orderItems()->create([
                'product_id' => $product->id,
                'quantity' => $quantity,
                'price' => $itemPrice,
                'total' => $itemTotal,
            ]);
        }
        
        // Update user if exists
        if ($pending_order->user) {
            $pending_order->user->update([
                'name' => $validated['customer_name'],
                'phone' => $validated['customer_phone'],
            ]);
        }

        return redirect()->route('admin.pending-orders.index')->with('success', 'Order updated successfully.');
    }

    public function destroy(Order $pending_order)
    {
        try {
            // Check if order belongs to current tenant
            if ($pending_order->tenant_id !== auth()->user()->tenant_id) {
                \Log::warning('Pending order delete attempt - wrong tenant', [
                    'order_id' => $pending_order->id,
                    'order_tenant_id' => $pending_order->tenant_id,
                    'user_tenant_id' => auth()->user()->tenant_id
                ]);
                
                if (request()->ajax() || request()->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'You do not have permission to delete this order.'
                    ], 403);
                }
                
                return redirect()->route('admin.pending-orders.index')
                    ->with('error', 'You do not have permission to delete this order.');
            }
            
            // Check if order is still pending
            if ($pending_order->status !== 'pending') {
                $message = "Cannot delete order. Order status is '{$pending_order->status}', only pending orders can be deleted.";
                
                if (request()->ajax() || request()->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message
                    ], 422);
                }
                
                return redirect()->route('admin.pending-orders.index')->with('error', $message);
            }
            
            $orderNumber = $pending_order->order_number;
            
            // Soft delete the order (move to trash) - don't delete order items
            // Order items will be automatically handled by the relationship
            $pending_order->delete();
            
            \Log::info('Pending order moved to trash', [
                'order_id' => $pending_order->id,
                'order_number' => $orderNumber,
                'deleted_by' => auth()->user()->id
            ]);

            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true, 
                    'message' => 'Order moved to trash successfully.'
                ]);
            }

            return redirect()->route('admin.pending-orders.index')
                ->with('success', 'Order moved to trash successfully.');
                
        } catch (\Illuminate\Database\QueryException $e) {
            \Log::error('Pending order delete (DB) error: '.$e->getMessage(), [
                'order_id' => $pending_order->id,
                'error_code' => $e->getCode()
            ]);
            
            $message = 'Unable to delete order. Database error occurred.';
            if (config('app.debug')) {
                $message .= ' Error: ' . $e->getMessage();
            }
            
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message
                ], 422);
            }
            
            return redirect()->route('admin.pending-orders.index')->with('error', $message);
            
        } catch (\Throwable $e) {
            \Log::error('Pending order delete unexpected error: '.$e->getMessage(), [
                'order_id' => $pending_order->id,
                'error_type' => get_class($e),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine()
            ]);
            
            $message = 'An unexpected error occurred while deleting the order.';
            if (config('app.debug')) {
                $message .= ' Error: ' . $e->getMessage();
            }
            
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message
                ], 500);
            }
            
            return redirect()->route('admin.pending-orders.index')->with('error', $message);
        }
    }

    public function confirm(Request $request, Order $pending_order)
    {
        // Force JSON response for AJAX requests
        if ($request->ajax() || $request->wantsJson()) {
            $request->headers->set('Accept', 'application/json');
        }
        
        try {
            \Log::info('Confirm order request received', [
                'order_id' => $pending_order->id,
                'order_number' => $pending_order->order_number,
                'current_status' => $pending_order->status,
                'is_ajax' => request()->ajax(),
                'request_data' => $request->all()
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

            // Validate shipping and tax amounts
            $validated = $request->validate([
                'shipping_cost' => 'nullable|numeric|min:0',
                'tax_amount' => 'nullable|numeric|min:0',
            ], [], [
                'shipping_cost' => 'shipping cost',
                'tax_amount' => 'tax amount'
            ]);

            $shippingCost = $validated['shipping_cost'] ?? 0;
            $taxAmount = $validated['tax_amount'] ?? 0;
            
            // Ensure order items are loaded
            if (!$pending_order->relationLoaded('orderItems')) {
                $pending_order->load('orderItems');
            }
            
            // Calculate subtotal from order items
            $subtotal = $pending_order->orderItems->sum(function($item) {
                return ($item->quantity ?? 0) * ($item->price ?? 0);
            });
            
            // Calculate new total
            $newTotal = $subtotal + $shippingCost + $taxAmount;

            // Update order with shipping, tax, and recalculated total
            // Use existing columns: shipping and tax (these definitely exist)
            $updateData = [
                'status' => 'confirmed',
                'subtotal' => $subtotal,
                'shipping' => $shippingCost,
                'tax' => $taxAmount,
                'total' => $newTotal,
            ];
            
            // Try to also update shipping_cost and tax_amount if they exist
            // But don't fail if they don't exist
            $columns = Schema::getColumnListing('orders');
            if (in_array('shipping_cost', $columns)) {
                $updateData['shipping_cost'] = $shippingCost;
            }
            if (in_array('tax_amount', $columns)) {
                $updateData['tax_amount'] = $taxAmount;
            }
            
            $result = $pending_order->update($updateData);
            
            \Log::info('Order status update result', [
                'order_id' => $pending_order->id,
                'update_result' => $result,
                'new_status' => $pending_order->fresh()->status,
                'shipping_cost' => $shippingCost,
                'tax_amount' => $taxAmount,
                'new_total' => $newTotal
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
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation error confirming order', [
                'order_id' => $pending_order->id ?? 'unknown',
                'errors' => $e->errors()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error confirming order', [
                'order_id' => $pending_order->id ?? 'unknown',
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            // Always return JSON for AJAX/JSON requests
            if (request()->ajax() || request()->wantsJson() || request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error: ' . $e->getMessage(),
                    'error_details' => config('app.debug') ? [
                        'file' => $e->getFile(),
                        'line' => $e->getLine()
                    ] : []
                ], 500);
            }

            return redirect()->back()->with('error', 'Error confirming order: ' . $e->getMessage());
        }
    }

    public function reject(Order $pending_order)
    {
        try {
            // Check tenant ownership
            if ($pending_order->tenant_id !== auth()->user()->tenant_id) {
                if (request()->ajax() || request()->wantsJson() || request()->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'You do not have permission to reject this order.'
                    ], 403);
                }
                return redirect()->route('admin.pending-orders.index')
                    ->with('error', 'You do not have permission to reject this order.');
            }

            if ($pending_order->status !== 'pending') {
                $message = "Only pending orders can be rejected. Current status: {$pending_order->status}";
                
                if (request()->ajax() || request()->wantsJson() || request()->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message
                    ], 422);
                }
                
                return redirect()->route('admin.pending-orders.index')->with('error', $message);
            }

            $pending_order->update(['status' => 'rejected']);
            
            \Log::info('Order rejected successfully', ['order_id' => $pending_order->id, 'order_number' => $pending_order->order_number]);

            if (request()->ajax() || request()->wantsJson() || request()->expectsJson()) {
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
            
            if (request()->ajax() || request()->wantsJson() || request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error: ' . $e->getMessage(),
                    'error_details' => config('app.debug') ? [
                        'file' => $e->getFile(),
                        'line' => $e->getLine()
                    ] : []
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
                $orders = Order::whereIn('id', $orderIds)
                    ->where('status', 'pending')
                    ->get();
                foreach ($orders as $order) {
                    // Soft delete (move to trash) - don't delete order items
                    $order->delete();
                    $count++;
                }
                $message = "{$count} orders moved to trash.";
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
        // Get products - tenant scope is automatically applied via BelongsToTenant trait
        $products = Product::where('is_active', true)
                          ->orderBy('name')
                          ->get(['id', 'name', 'price', 'sale_price', 'is_active']);
        
        // Debug: Log product count
        \Log::info('Bulk Order Create - Products count: ' . $products->count());
        
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
            // Create or update user with phone number
            $user = User::firstOrCreate(
                ['phone' => $orderData['customer_phone']],
                [
                    'name' => $orderData['customer_name'],
                    'email' => Str::slug($orderData['customer_name']) . '@example.com',
                    'password' => bcrypt(Str::random(10)),
                    'role' => 'user',
                ]
            );
            
            // Update user name if it changed
            if ($user->name !== $orderData['customer_name']) {
                $user->update(['name' => $orderData['customer_name']]);
            }

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
                'receiver_name' => $orderData['customer_name'] ?? null,
                'receiver_phone' => $orderData['customer_phone'] ?? null,
                'billing_first_name' => $orderData['customer_name'] ?? null,
                'billing_phone' => $orderData['customer_phone'] ?? null,
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

    /**
     * Show trashed (deleted) orders
     */
    public function trash(Request $request)
    {
        // Get soft deleted orders
        $query = Order::onlyTrashed()
            ->with(['user:id,name,email,phone', 'orderItems.product:id,name'])
            ->orderBy('deleted_at', 'desc');

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

        $orders = $query->paginate(15);
        
        return view('admin.pending-orders.trash', compact('orders'));
    }

    /**
     * Restore a trashed order
     */
    public function restore($id)
    {
        try {
            $order = Order::onlyTrashed()->findOrFail($id);
            
            // Check tenant ownership
            if ($order->tenant_id !== auth()->user()->tenant_id) {
                if (request()->ajax() || request()->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'You do not have permission to restore this order.'
                    ], 403);
                }
                return redirect()->route('admin.pending-orders.trash')
                    ->with('error', 'You do not have permission to restore this order.');
            }
            
            $order->restore();
            
            \Log::info('Order restored from trash', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'restored_by' => auth()->user()->id
            ]);
            
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Order restored successfully.'
                ]);
            }
            
            return redirect()->route('admin.pending-orders.trash')
                ->with('success', 'Order restored successfully.');
                
        } catch (\Exception $e) {
            \Log::error('Error restoring order: ' . $e->getMessage(), ['order_id' => $id]);
            
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to restore order: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->route('admin.pending-orders.trash')
                ->with('error', 'Failed to restore order.');
        }
    }

    /**
     * Permanently delete an order from trash
     */
    public function forceDelete($id)
    {
        try {
            $order = Order::onlyTrashed()->findOrFail($id);
            
            // Check tenant ownership
            if ($order->tenant_id !== auth()->user()->tenant_id) {
                if (request()->ajax() || request()->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'You do not have permission to permanently delete this order.'
                    ], 403);
                }
                return redirect()->route('admin.pending-orders.trash')
                    ->with('error', 'You do not have permission to permanently delete this order.');
            }
            
            $orderNumber = $order->order_number;
            
            // Permanently delete order items first
            $order->orderItems()->forceDelete();
            
            // Permanently delete the order
            $order->forceDelete();
            
            \Log::info('Order permanently deleted from trash', [
                'order_id' => $id,
                'order_number' => $orderNumber,
                'deleted_by' => auth()->user()->id
            ]);
            
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Order permanently deleted.'
                ]);
            }
            
            return redirect()->route('admin.pending-orders.trash')
                ->with('success', 'Order permanently deleted.');
                
        } catch (\Exception $e) {
            \Log::error('Error permanently deleting order: ' . $e->getMessage(), ['order_id' => $id]);
            
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to permanently delete order: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->route('admin.pending-orders.trash')
                ->with('error', 'Failed to permanently delete order.');
        }
    }
}