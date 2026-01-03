<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\DeliveryBoy;
use App\Models\ManualDelivery;
use App\Models\CodSettlement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ManualDeliveryController extends Controller
{
    public function index(Request $request)
    {
        $activeTab = $request->get('tab', 'overview');
        
        // Calculate metrics
        $totalDeliveries = ManualDelivery::count();
        $activeRiders = DeliveryBoy::whereIn('status', ['active', 'on_duty'])->count();
        $pendingDeliveries = ManualDelivery::whereIn('status', ['assigned', 'picked_up', 'in_transit'])->count();
        $todayDeliveries = ManualDelivery::whereDate('created_at', Carbon::today())->count();
        
        $completedDeliveries = ManualDelivery::where('status', 'delivered')->count();
        $successRate = $totalDeliveries > 0 ? round(($completedDeliveries / $totalDeliveries) * 100, 2) : 100;
        
        $pendingCodAmount = ManualDelivery::where('status', 'delivered')
            ->where('cod_collected', true)
            ->where('cod_settled', false)
            ->sum('cod_amount');
        
        // Calculate total revenue from completed deliveries
        $totalRevenue = ManualDelivery::where('status', 'delivered')
            ->sum('cod_amount');
        
        $metrics = [
            'total_deliveries' => $totalDeliveries,
            'active_riders' => $activeRiders,
            'pending' => $pendingDeliveries,
            'today' => $todayDeliveries,
            'success_rate' => $successRate,
            'pending_cod' => $pendingCodAmount,
            'revenue' => $totalRevenue
        ];
        
        // Get recent manual deliveries
        $recentDeliveries = ManualDelivery::with(['order.user', 'deliveryBoy'])
            ->latest()
            ->take(10)
            ->get();
        
        // Get active delivery boys
        $activeDeliveryBoys = DeliveryBoy::whereIn('status', ['active', 'on_duty'])
            ->withCount('manualDeliveries')
            ->get();
        
        return view('admin.manual-delivery.index', compact(
            'activeTab',
            'metrics',
            'recentDeliveries',
            'activeDeliveryBoys'
        ));
    }
    
    // All deliveries view
    public function deliveries(Request $request)
    {
        $query = ManualDelivery::with(['order.user', 'deliveryBoy']);
        
        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('order', function($q2) use ($search) {
                    $q2->where('order_number', 'like', "%{$search}%")
                       ->orWhereHas('user', function($q3) use ($search) {
                           $q3->where('name', 'like', "%{$search}%")
                              ->orWhere('phone', 'like', "%{$search}%");
                       });
                })->orWhereHas('deliveryBoy', function($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%")
                       ->orWhere('phone', 'like', "%{$search}%");
                });
            });
        }
        
        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Date filter
        if ($request->filled('date_from')) {
            $query->whereDate('assigned_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('assigned_at', '<=', $request->date_to);
        }
        
        $deliveries = $query->latest('assigned_at')->paginate(20);
        
        // Statistics
        $stats = [
            'total' => ManualDelivery::count(),
            'assigned' => ManualDelivery::where('status', 'assigned')->count(),
            'picked_up' => ManualDelivery::where('status', 'picked_up')->count(),
            'in_transit' => ManualDelivery::where('status', 'in_transit')->count(),
            'delivered' => ManualDelivery::where('status', 'delivered')->count(),
            'cancelled' => ManualDelivery::where('status', 'cancelled')->count(),
        ];
        
        return view('admin.manual-delivery.deliveries', compact('deliveries', 'stats'));
    }
    
    // Activities view
    public function activities(Request $request)
    {
        $query = \App\Models\DeliveryBoyActivity::with(['deliveryBoy', 'manualDelivery.order']);
        
        // Filter by delivery boy
        if ($request->filled('delivery_boy_id')) {
            $query->where('delivery_boy_id', $request->delivery_boy_id);
        }
        
        // Filter by activity type
        if ($request->filled('activity_type')) {
            $query->where('activity_type', $request->activity_type);
        }
        
        // Date filter
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        $activities = $query->latest()->paginate(50);
        $deliveryBoys = DeliveryBoy::all();
        
        return view('admin.manual-delivery.activities', compact('activities', 'deliveryBoys'));
    }
    
    // Performance analytics
    public function performance(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));
        
        // Daily performance data
        $dailyPerformance = ManualDelivery::select(
                DB::raw('DATE(assigned_at) as date'),
                DB::raw('COUNT(*) as total_deliveries'),
                DB::raw('SUM(CASE WHEN status = "delivered" THEN 1 ELSE 0 END) as completed'),
                DB::raw('SUM(CASE WHEN status = "cancelled" THEN 1 ELSE 0 END) as cancelled'),
                DB::raw('SUM(CASE WHEN status = "delivered" THEN cod_amount ELSE 0 END) as revenue')
            )
            ->whereBetween('assigned_at', [Carbon::parse($startDate), Carbon::parse($endDate)->endOfDay()])
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        // Delivery boy performance
        $deliveryBoyPerformance = DeliveryBoy::with(['manualDeliveries' => function($query) use ($startDate, $endDate) {
                $query->whereBetween('assigned_at', [Carbon::parse($startDate), Carbon::parse($endDate)->endOfDay()]);
            }])
            ->get()
            ->map(function($boy) {
                $deliveries = $boy->manualDeliveries;
                $boy->total_deliveries = $deliveries->count();
                $boy->completed_deliveries = $deliveries->where('status', 'delivered')->count();
                $boy->cancelled_deliveries = $deliveries->where('status', 'cancelled')->count();
                $boy->total_revenue = $deliveries->where('status', 'delivered')->sum('cod_amount');
                $boy->success_rate = $boy->total_deliveries > 0 
                    ? round(($boy->completed_deliveries / $boy->total_deliveries) * 100, 2) 
                    : 0;
                return $boy;
            })
            ->filter(function($boy) {
                return $boy->total_deliveries > 0; // Only show boys with deliveries in the period
            });
        
        // Overall statistics
        $stats = [
            'total_deliveries' => ManualDelivery::whereBetween('assigned_at', [Carbon::parse($startDate), Carbon::parse($endDate)->endOfDay()])->count(),
            'completed' => ManualDelivery::where('status', 'delivered')->whereBetween('assigned_at', [Carbon::parse($startDate), Carbon::parse($endDate)->endOfDay()])->count(),
            'cancelled' => ManualDelivery::where('status', 'cancelled')->whereBetween('assigned_at', [Carbon::parse($startDate), Carbon::parse($endDate)->endOfDay()])->count(),
            'total_revenue' => ManualDelivery::where('status', 'delivered')->whereBetween('assigned_at', [Carbon::parse($startDate), Carbon::parse($endDate)->endOfDay()])->sum('cod_amount'),
        ];
        
        return view('admin.manual-delivery.performance', compact(
            'dailyPerformance',
            'deliveryBoyPerformance',
            'stats',
            'startDate',
            'endDate'
        ));
    }
    
    // Order allocation view - shows confirmed orders ready for manual delivery
    public function allocation(Request $request)
    {
        $query = Order::with(['user', 'orderItems.product'])
            ->where('status', 'confirmed')
            ->whereDoesntHave('manualDelivery')
            ->whereDoesntHave('shipment')
            ->whereDoesntHave('gaaubesiShipment');
        
        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%")
                         ->orWhere('phone', 'like', "%{$search}%");
                  });
            });
        }
        
        $orders = $query->latest()->paginate(20);
        $deliveryBoys = DeliveryBoy::where('status', 'active')->get();
        
        return view('admin.manual-delivery.allocation', compact('orders', 'deliveryBoys'));
    }
    
    // Allocate order to delivery boy
    public function allocateOrder(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'delivery_boy_id' => 'required|exists:delivery_boys,id',
            'delivery_notes' => 'nullable|string',
        ]);
        
        $order = Order::find($validated['order_id']);
        
        // Check if order can be allocated
        if ($order->status !== 'confirmed') {
            return response()->json([
                'success' => false,
                'message' => 'Only confirmed orders can be allocated for manual delivery.'
            ], 400);
        }
        
        if ($order->manualDelivery || $order->shipment || $order->gaaubesiShipment) {
            return response()->json([
                'success' => false,
                'message' => 'This order already has a delivery assignment.'
            ], 400);
        }
        
        // Create manual delivery
        $manualDelivery = ManualDelivery::create([
            'order_id' => $order->id,
            'delivery_boy_id' => $validated['delivery_boy_id'],
            'assigned_by' => Auth::id(),
            'status' => 'assigned',
            'assigned_at' => now(),
            'cod_amount' => $order->payment_method === 'cod' ? $order->total : 0,
            'delivery_notes' => $validated['delivery_notes'] ?? null,
        ]);
        
        // Update order status to processing
        $order->update(['status' => 'processing']);
        
        // Log activity
        $deliveryBoy = DeliveryBoy::find($validated['delivery_boy_id']);
        $deliveryBoy->logActivity(
            'order_assigned',
            "Order {$order->order_number} assigned for delivery",
            $manualDelivery->id
        );
        
        return response()->json([
            'success' => true,
            'message' => 'Order allocated successfully to delivery boy.'
        ]);
    }
    
    // Bulk allocation
    public function bulkAllocate(Request $request)
    {
        $validated = $request->validate([
            'order_ids' => 'required|array|min:1',
            'order_ids.*' => 'exists:orders,id',
            'delivery_boy_id' => 'required|exists:delivery_boys,id',
        ]);
        
        $allocatedCount = 0;
        $deliveryBoy = DeliveryBoy::find($validated['delivery_boy_id']);
        
        foreach ($validated['order_ids'] as $orderId) {
            $order = Order::find($orderId);
            
            if ($order->status === 'confirmed' && !$order->manualDelivery && !$order->shipment && !$order->gaaubesiShipment) {
                $manualDelivery = ManualDelivery::create([
                    'order_id' => $order->id,
                    'delivery_boy_id' => $validated['delivery_boy_id'],
                    'assigned_by' => Auth::id(),
                    'status' => 'assigned',
                    'assigned_at' => now(),
                    'cod_amount' => $order->payment_method === 'cod' ? $order->total : 0,
                ]);
                
                $order->update(['status' => 'processing']);
                $allocatedCount++;
            }
        }
        
        return response()->json([
            'success' => true,
            'message' => "{$allocatedCount} orders allocated successfully."
        ]);
    }
    
    // Delivery boy wise list
    public function deliveryBoyWise(Request $request)
    {
        $deliveryBoys = DeliveryBoy::whereIn('status', ['active', 'on_duty'])
            ->withCount([
                'manualDeliveries',
                'activeDeliveries',
            ])
            ->with(['activeDeliveries.order.user', 'activeDeliveries.order.orderItems'])
            ->get();
        
        return view('admin.manual-delivery.delivery-boy-wise', compact('deliveryBoys'));
    }
    
    // Show deliveries for a specific delivery boy
    public function deliveryBoyDeliveries(DeliveryBoy $deliveryBoy, Request $request)
    {
        $query = $deliveryBoy->manualDeliveries()->with(['order.user', 'order.orderItems.product']);
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $deliveries = $query->latest('assigned_at')->paginate(20);
        
        return view('admin.manual-delivery.boy-deliveries', compact('deliveryBoy', 'deliveries'));
    }
    
    // Update delivery status (admin side)
    public function updateDeliveryStatus(Request $request, ManualDelivery $manualDelivery)
    {
        $validated = $request->validate([
            'status' => 'required|in:assigned,picked_up,in_transit,delivered,cancelled',
            'notes' => 'nullable|string',
            'cancellation_reason' => 'required_if:status,cancelled',
        ]);
        
        $oldStatus = $manualDelivery->status;
        $manualDelivery->status = $validated['status'];
        
        if ($validated['status'] === 'picked_up' && !$manualDelivery->picked_up_at) {
            $manualDelivery->picked_up_at = now();
        }
        
        if ($validated['status'] === 'delivered') {
            $manualDelivery->delivered_at = now();
            $manualDelivery->order->update(['status' => 'completed']);
        }
        
        if ($validated['status'] === 'cancelled') {
            $manualDelivery->cancelled_at = now();
            $manualDelivery->cancellation_reason = $validated['cancellation_reason'];
            $manualDelivery->order->update(['status' => 'cancelled']);
        }
        
        if (isset($validated['notes'])) {
            $manualDelivery->delivery_notes = $validated['notes'];
        }
        
        $manualDelivery->save();
        
        // Update delivery boy stats
        $manualDelivery->deliveryBoy->updateStats();
        
        return response()->json([
            'success' => true,
            'message' => 'Delivery status updated successfully.'
        ]);
    }
    
    // COD Settlement views and actions
    public function codSettlements(Request $request)
    {
        $query = CodSettlement::with(['deliveryBoy', 'settledBy']);
        
        // Filter by delivery boy
        if ($request->filled('delivery_boy_id')) {
            $query->where('delivery_boy_id', $request->delivery_boy_id);
        }
        
        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('settled_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('settled_at', '<=', $request->date_to);
        }
        
        $settlements = $query->latest('settled_at')->paginate(20);
        $deliveryBoys = DeliveryBoy::all();
        
        // Get pending COD by delivery boy
        $pendingCod = DeliveryBoy::query()
            ->select('delivery_boys.id', 'delivery_boys.name', 'delivery_boys.phone', 'delivery_boys.tenant_id')
            ->selectRaw('SUM(manual_deliveries.cod_amount) as pending_amount')
            ->selectRaw('COUNT(manual_deliveries.id) as pending_orders')
            ->join('manual_deliveries', 'delivery_boys.id', '=', 'manual_deliveries.delivery_boy_id')
            ->where('manual_deliveries.status', 'delivered')
            ->where('manual_deliveries.cod_collected', true)
            ->where('manual_deliveries.cod_settled', false)
            ->groupBy('delivery_boys.id', 'delivery_boys.name', 'delivery_boys.phone', 'delivery_boys.tenant_id')
            ->having('pending_amount', '>', 0)
            ->get();
        
        return view('admin.manual-delivery.cod-settlements', compact('settlements', 'deliveryBoys', 'pendingCod'));
    }
    
    // Create COD settlement
    public function createCodSettlement(DeliveryBoy $deliveryBoy)
    {
        $pendingDeliveries = $deliveryBoy->manualDeliveries()
            ->with(['order.user'])
            ->where('status', 'delivered')
            ->where('cod_collected', true)
            ->where('cod_settled', false)
            ->get();
        
        $totalAmount = $pendingDeliveries->sum('cod_amount');
        
        return view('admin.manual-delivery.create-settlement', compact('deliveryBoy', 'pendingDeliveries', 'totalAmount'));
    }
    
    // Store COD settlement
    public function storeCodSettlement(Request $request, DeliveryBoy $deliveryBoy)
    {
        $validated = $request->validate([
            'delivery_ids' => 'required|array|min:1',
            'delivery_ids.*' => 'exists:manual_deliveries,id',
            'payment_method' => 'required|in:cash,bank_transfer,cheque,mobile_wallet',
            'transaction_reference' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);
        
        $deliveries = ManualDelivery::whereIn('id', $validated['delivery_ids'])
            ->where('delivery_boy_id', $deliveryBoy->id)
            ->where('status', 'delivered')
            ->where('cod_collected', true)
            ->where('cod_settled', false)
            ->get();
        
        if ($deliveries->isEmpty()) {
            return back()->with('error', 'No valid deliveries found for settlement.');
        }
        
        $totalAmount = $deliveries->sum('cod_amount');
        $orderIds = $deliveries->pluck('order_id')->toArray();
        
        // Generate settlement ID
        $lastSettlement = CodSettlement::latest()->first();
        $nextNumber = $lastSettlement ? intval(substr($lastSettlement->settlement_id, 4)) + 1 : 1;
        $settlementId = 'SET-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
        
        // Create settlement
        $settlement = CodSettlement::create([
            'settlement_id' => $settlementId,
            'delivery_boy_id' => $deliveryBoy->id,
            'settled_by' => Auth::id(),
            'total_amount' => $totalAmount,
            'total_orders' => $deliveries->count(),
            'order_ids' => json_encode($orderIds),
            'payment_method' => $validated['payment_method'],
            'transaction_reference' => $validated['transaction_reference'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'settled_at' => now(),
        ]);
        
        // Mark deliveries as settled
        foreach ($deliveries as $delivery) {
            $delivery->update([
                'cod_settled' => true,
                'cod_settled_at' => now(),
            ]);
        }
        
        // Update delivery boy stats
        $deliveryBoy->updateStats();
        
        // Log activity
        $deliveryBoy->logActivity(
            'cod_settled',
            "COD settlement of ₨{$totalAmount} for {$deliveries->count()} orders",
            null,
            ['settlement_id' => $settlementId, 'amount' => $totalAmount]
        );
        
        return redirect()->route('admin.manual-delivery.cod-settlements')
            ->with('success', "COD settlement of ₨{$totalAmount} created successfully.");
    }
    
    // Analytics and statements
    public function deliveryBoyAnalytics(DeliveryBoy $deliveryBoy, Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->subMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));
        
        // Get deliveries for the period
        $deliveries = $deliveryBoy->manualDeliveries()
            ->whereBetween('assigned_at', [Carbon::parse($startDate), Carbon::parse($endDate)->endOfDay()])
            ->get();
        
        // Statistics
        $stats = [
            'total_assigned' => $deliveries->count(),
            'delivered' => $deliveries->where('status', 'delivered')->count(),
            'cancelled' => $deliveries->where('status', 'cancelled')->count(),
            'in_progress' => $deliveries->whereIn('status', ['assigned', 'picked_up', 'in_transit'])->count(),
            'total_cod_collected' => $deliveries->where('cod_collected', true)->sum('cod_amount'),
            'pending_settlement' => $deliveries->where('cod_collected', true)->where('cod_settled', false)->sum('cod_amount'),
            'settled_amount' => $deliveries->where('cod_settled', true)->sum('cod_amount'),
        ];
        
        // Daily performance
        $dailyPerformance = $deliveries->groupBy(function($delivery) {
            return $delivery->assigned_at->format('Y-m-d');
        })->map(function($dayDeliveries) {
            return [
                'total' => $dayDeliveries->count(),
                'delivered' => $dayDeliveries->where('status', 'delivered')->count(),
                'cancelled' => $dayDeliveries->where('status', 'cancelled')->count(),
            ];
        });
        
        // Recent deliveries
        $recentDeliveries = $deliveryBoy->manualDeliveries()
            ->with(['order.user'])
            ->latest('assigned_at')
            ->limit(20)
            ->get();
        
        return view('admin.manual-delivery.boy-analytics', compact(
            'deliveryBoy',
            'stats',
            'dailyPerformance',
            'recentDeliveries',
            'startDate',
            'endDate'
        ));
    }
    
    // Delivery boys management
    public function deliveryBoys(Request $request)
    {
        $query = DeliveryBoy::withCount([
            'manualDeliveries',
            'completedDeliveries',
        ]);
        
        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('delivery_boy_id', 'like', "%{$search}%");
            });
        }
        
        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Zone filter
        if ($request->filled('zone')) {
            $query->where('zone', $request->zone);
        }
        
        $deliveryBoys = $query->latest()->paginate(20);
        
        return view('admin.manual-delivery.delivery-boys', compact('deliveryBoys'));
    }
    
    // Store new delivery boy
    public function storeDeliveryBoy(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|unique:delivery_boys,phone',
            'email' => 'nullable|email|unique:delivery_boys,email',
            'password' => 'required|string|min:6',
            'zone' => 'required|in:north,south,east,west,central',
            'cnic' => 'nullable|string',
            'license_number' => 'nullable|string',
            'address' => 'nullable|string',
            'vehicle_type' => 'nullable|in:motorcycle,bicycle,car,van',
            'vehicle_number' => 'nullable|string',
        ]);
        
        // Generate delivery boy ID
        $lastBoy = DeliveryBoy::latest()->first();
        $nextNumber = $lastBoy ? intval(substr($lastBoy->delivery_boy_id, 2)) + 1 : 1;
        $deliveryBoyId = 'DB' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
        
        $validated['delivery_boy_id'] = $deliveryBoyId;
        $validated['password'] = \Hash::make($validated['password']);
        $validated['status'] = 'active';
        
        $deliveryBoy = DeliveryBoy::create($validated);

        // Also create a delivery boy user (role: delivery_boy) if email provided and not used in users table
        if (!empty($validated['email'])) {
            $emailInUsers = \App\Models\User::where('email', $validated['email'])->exists();
            if (!$emailInUsers) {
                \App\Models\User::create([
                    'tenant_id' => Auth::user()->tenant_id,
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'phone' => $validated['phone'],
                    'password' => \Hash::make($request->input('password')),
                    'role' => 'delivery_boy',
                    'permissions' => [],
                    'is_active' => true,
                ]);
            }
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Delivery boy added successfully.',
            'delivery_boy_id' => $deliveryBoy->id,
        ]);
    }
    
    // Update delivery boy
    public function updateDeliveryBoy(DeliveryBoy $deliveryBoy, Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|unique:delivery_boys,phone,' . $deliveryBoy->id,
            'email' => 'nullable|email|unique:delivery_boys,email,' . $deliveryBoy->id,
            'password' => 'nullable|string|min:6',
            'zone' => 'required|in:north,south,east,west,central',
            'cnic' => 'nullable|string',
            'license_number' => 'nullable|string',
            'address' => 'nullable|string',
            'vehicle_type' => 'nullable|in:motorcycle,bicycle,car,van',
            'vehicle_number' => 'nullable|string',
        ]);
        
        // Only update password if provided
        if (!empty($validated['password'])) {
            $validated['password'] = \Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }
        
        $deliveryBoy->update($validated);

        // Sync user record if email provided and user exists
        if (!empty($validated['email'])) {
            $user = \App\Models\User::where('email', $validated['email'])->first();
            if ($user) {
                $user->update([
                    'name' => $validated['name'],
                    'phone' => $validated['phone'],
                    // keep role as delivery_boy
                ]);
            }
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Delivery boy updated successfully.'
        ]);
    }
    
    // Update delivery boy status
    public function updateDeliveryBoyStatus(DeliveryBoy $deliveryBoy, Request $request)
    {
        $validated = $request->validate([
            'status' => 'required|in:active,inactive,on_duty,off_duty',
        ]);
        
        $deliveryBoy->update(['status' => $validated['status']]);
        
        return response()->json([
            'success' => true,
            'message' => 'Delivery boy status updated successfully.'
        ]);
    }
}
