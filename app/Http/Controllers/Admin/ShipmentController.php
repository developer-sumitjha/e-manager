<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Shipment;
use App\Models\DeliveryMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ShipmentController extends Controller
{
    public function index()
    {
        // Get shipment statistics
        $totalShipments = Shipment::count();
        $manualDeliveries = Shipment::where('delivery_method', 'manual')->count();
        $logisticsShipments = Shipment::where('delivery_method', 'logistics')->count();
        
        // Calculate delivery rate (completed deliveries / total shipments)
        $completedShipments = Shipment::where('status', 'delivered')->count();
        $deliveryRate = $totalShipments > 0 ? round(($completedShipments / $totalShipments) * 100) : 95;
        
        // Get confirmed orders awaiting shipment allotment
        $confirmedOrders = Order::where('status', 'confirmed')
                                ->whereDoesntHave('shipment')
                                ->with(['user', 'orderItems.product'])
                                ->latest()
                                ->get();
        
        // Get recent shipments
        $recentShipments = Shipment::with(['order.user'])
                                   ->latest()
                                   ->take(10)
                                   ->get();
        
        return view('admin.shipments.index', compact(
            'totalShipments', 
            'manualDeliveries', 
            'logisticsShipments', 
            'deliveryRate',
            'confirmedOrders',
            'recentShipments'
        ));
    }

    public function create()
    {
        $confirmedOrders = Order::where('status', 'confirmed')
                                ->whereDoesntHave('shipment')
                                ->with(['user', 'orderItems.product'])
                                ->get();
        
        $deliveryMethods = [
            'manual' => 'Manual Delivery',
            'logistics' => 'Third-party Logistics'
        ];
        
        return view('admin.shipments.create', compact('confirmedOrders', 'deliveryMethods'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'delivery_method' => 'required|in:manual,logistics',
            'tracking_number' => 'nullable|string|max:255',
            'estimated_delivery_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'delivery_agent_name' => 'nullable|string|max:255',
            'delivery_agent_phone' => 'nullable|string|max:20',
            'logistics_company' => 'nullable|string|max:255',
        ]);

        // Generate tracking number if not provided
        if (empty($validated['tracking_number'])) {
            $validated['tracking_number'] = 'SHIP-' . strtoupper(Str::random(8));
        }

        // Create shipment
        $shipment = Shipment::create([
            'order_id' => $validated['order_id'],
            'delivery_method' => $validated['delivery_method'],
            'tracking_number' => $validated['tracking_number'],
            'status' => 'pending',
            'estimated_delivery_date' => $validated['estimated_delivery_date'],
            'notes' => $validated['notes'],
            'delivery_agent_name' => $validated['delivery_agent_name'],
            'delivery_agent_phone' => $validated['delivery_agent_phone'],
            'logistics_company' => $validated['logistics_company'],
            'created_by' => auth()->id()
        ]);

        // Update order status to processing
        $order = Order::find($validated['order_id']);
        $order->update(['status' => 'processing']);

        return redirect()->route('admin.shipments.index')->with('success', 'Shipment created successfully.');
    }

    public function show(Shipment $shipment)
    {
        $shipment->load(['order.user', 'order.orderItems.product']);
        return view('admin.shipments.show', compact('shipment'));
    }

    public function edit(Shipment $shipment)
    {
        $deliveryMethods = [
            'manual' => 'Manual Delivery',
            'logistics' => 'Third-party Logistics'
        ];
        
        $shipment->load(['order.user']);
        return view('admin.shipments.edit', compact('shipment', 'deliveryMethods'));
    }

    public function update(Request $request, Shipment $shipment)
    {
        $validated = $request->validate([
            'delivery_method' => 'required|in:manual,logistics',
            'tracking_number' => 'required|string|max:255',
            'status' => 'required|in:pending,shipped,in_transit,delivered,returned',
            'estimated_delivery_date' => 'nullable|date',
            'actual_delivery_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'delivery_agent_name' => 'nullable|string|max:255',
            'delivery_agent_phone' => 'nullable|string|max:20',
            'logistics_company' => 'nullable|string|max:255',
        ]);

        $shipment->update($validated);

        // Update order status based on shipment status
        $order = $shipment->order;
        if ($validated['status'] === 'delivered') {
            $order->update(['status' => 'completed']);
        } elseif ($validated['status'] === 'shipped') {
            $order->update(['status' => 'shipped']);
        }

        return redirect()->route('admin.shipments.index')->with('success', 'Shipment updated successfully.');
    }

    public function destroy(Shipment $shipment)
    {
        // Update order status back to confirmed
        $order = $shipment->order;
        $order->update(['status' => 'confirmed']);
        
        $shipment->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Shipment deleted successfully.']);
        }

        return redirect()->route('admin.shipments.index')->with('success', 'Shipment deleted successfully.');
    }

    public function allotShipments(Request $request)
    {
        $validated = $request->validate([
            'order_ids' => 'required|array|min:1',
            'order_ids.*' => 'exists:orders,id',
            'delivery_method' => 'required|in:manual,logistics',
            'logistics_company' => 'nullable|string|max:255',
            'delivery_agent_name' => 'nullable|string|max:255',
            'delivery_agent_phone' => 'nullable|string|max:20',
            'estimated_delivery_date' => 'nullable|date',
        ]);

        $createdCount = 0;

        foreach ($validated['order_ids'] as $orderId) {
            // Check if shipment already exists for this order
            if (!Shipment::where('order_id', $orderId)->exists()) {
                Shipment::create([
                    'order_id' => $orderId,
                    'delivery_method' => $validated['delivery_method'],
                    'tracking_number' => 'SHIP-' . strtoupper(Str::random(8)),
                    'status' => 'pending',
                    'estimated_delivery_date' => $validated['estimated_delivery_date'] ?? null,
                    'delivery_agent_name' => $validated['delivery_agent_name'] ?? null,
                    'delivery_agent_phone' => $validated['delivery_agent_phone'] ?? null,
                    'logistics_company' => $validated['logistics_company'] ?? null,
                    'created_by' => auth()->id()
                ]);

                // Update order status to processing
                Order::find($orderId)->update(['status' => 'processing']);
                $createdCount++;
            }
        }

        return redirect()->route('admin.shipments.index')->with('success', "{$createdCount} shipments created successfully.");
    }

    public function updateStatus(Request $request, Shipment $shipment)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,shipped,in_transit,delivered,returned'
        ]);

        $shipment->update($validated);

        // Update order status based on shipment status
        $order = $shipment->order;
        if ($validated['status'] === 'delivered') {
            $order->update(['status' => 'completed']);
            $shipment->update(['actual_delivery_date' => now()]);
        } elseif ($validated['status'] === 'shipped') {
            $order->update(['status' => 'shipped']);
        }

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Shipment status updated successfully.',
                'status' => $shipment->status
            ]);
        }

        return redirect()->back()->with('success', 'Shipment status updated successfully.');
    }
}