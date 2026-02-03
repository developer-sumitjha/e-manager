<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\PathaoShipment;
use App\Services\PathaoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PathaoController extends Controller
{
    protected $pathaoService;

    public function __construct(PathaoService $pathaoService)
    {
        $this->pathaoService = $pathaoService;
    }

    /**
     * Display Pathao logistics dashboard
     */
    public function index(Request $request)
    {
        try {
            $query = PathaoShipment::with(['order.user', 'shipment']);

            // Search
            if ($request->has('search') && $request->search != '') {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('pathao_order_id', 'like', "%{$search}%")
                      ->orWhere('consignment_id', 'like', "%{$search}%")
                      ->orWhere('tracking_id', 'like', "%{$search}%")
                      ->orWhere('recipient_name', 'like', "%{$search}%")
                      ->orWhere('recipient_phone', 'like', "%{$search}%")
                      ->orWhereHas('order', function($q) use ($search) {
                          $q->where('order_number', 'like', "%{$search}%");
                      });
                });
            }

            // Filter by status
            if ($request->has('status') && $request->status != '') {
                $query->where('status', 'like', "%{$request->status}%");
            }

            // Filter by COD collected status
            if ($request->has('cod_collected') && $request->cod_collected !== '') {
                $query->where('cod_collected', $request->cod_collected);
            }

            $shipments = $query->latest()->paginate(15);

            // Get statistics with error handling
            try {
                $totalShipments = PathaoShipment::count();
                $deliveredShipments = PathaoShipment::whereNotNull('delivered_at')->count();
                $codCollectedCount = PathaoShipment::where('cod_collected', true)->count();
                $pendingShipments = $totalShipments - $deliveredShipments;
            } catch (\Exception $e) {
                Log::error('Pathao Index: Error getting statistics', ['error' => $e->getMessage()]);
                $totalShipments = 0;
                $deliveredShipments = 0;
                $codCollectedCount = 0;
                $pendingShipments = 0;
            }

            // Get pending logistics orders (allocated but not yet created in Pathao)
            try {
                $pendingLogisticsOrders = Order::where('status', 'processing')
                                               ->whereHas('shipment', function($query) {
                                                   $query->where('delivery_method', 'logistics');
                                               })
                                               ->whereDoesntHave('pathaoShipment')
                                               ->with(['user', 'orderItems.product', 'shipment'])
                                               ->latest()
                                               ->get();
            } catch (\Exception $e) {
                Log::error('Pathao Index: Error getting pending orders', ['error' => $e->getMessage()]);
                $pendingLogisticsOrders = collect([]);
            }

            return view('admin.pathao.index', compact(
                'shipments',
                'totalShipments',
                'deliveredShipments',
                'codCollectedCount',
                'pendingShipments',
                'pendingLogisticsOrders'
            ));
        } catch (\Exception $e) {
            Log::error('Pathao Index Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            
            // Return view with empty data instead of crashing
            return view('admin.pathao.index', [
                'shipments' => collect([])->paginate(15),
                'totalShipments' => 0,
                'deliveredShipments' => 0,
                'codCollectedCount' => 0,
                'pendingShipments' => 0,
                'pendingLogisticsOrders' => collect([]),
            ])->with('error', 'An error occurred while loading the page. Please check the logs.');
        }
    }

    /**
     * Show form to create a new Pathao shipment
     */
    public function create()
    {
        // Get orders allocated to logistics that don't have Pathao shipment yet
        $orders = Order::where('status', 'processing')
                      ->whereHas('shipment', function($query) {
                          $query->where('delivery_method', 'logistics');
                      })
                      ->whereDoesntHave('pathaoShipment')
                      ->with(['user', 'orderItems.product', 'shipment'])
                      ->latest()
                      ->get();

        // Get cities for dropdown
        $citiesResponse = $this->pathaoService->getCities();
        $cities = $citiesResponse['success'] ? $citiesResponse['data'] : [];

        // Get stores
        $storesResponse = $this->pathaoService->getStores();
        $stores = $storesResponse['success'] ? $storesResponse['data'] : [];

        return view('admin.pathao.create', compact(
            'orders',
            'cities',
            'stores'
        ));
    }

    /**
     * Show form to bulk create Pathao shipments
     */
    public function bulkCreateForm()
    {
        // Get orders allocated to logistics that don't have Pathao shipment yet
        $orders = Order::where('status', 'processing')
                      ->whereHas('shipment', function($query) {
                          $query->where('delivery_method', 'logistics');
                      })
                      ->whereDoesntHave('pathaoShipment')
                      ->with(['user', 'orderItems.product', 'shipment'])
                      ->latest()
                      ->get();

        // Get cities for dropdown
        $citiesResponse = $this->pathaoService->getCities();
        $cities = $citiesResponse['success'] ? $citiesResponse['data'] : [];

        // Get stores
        $storesResponse = $this->pathaoService->getStores();
        $stores = $storesResponse['success'] ? $storesResponse['data'] : [];

        return view('admin.pathao.bulk-create', compact(
            'orders',
            'cities',
            'stores'
        ));
    }

    /**
     * Store a new Pathao shipment
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'store_id' => 'required|integer',
            'recipient_name' => 'required|string|max:255',
            'recipient_phone' => 'required|string|max:20',
            'recipient_address' => 'required|string',
            'recipient_city' => 'required|integer',
            'recipient_zone' => 'required|integer',
            'recipient_area' => 'nullable|integer',
            'item_type' => 'required|in:1,2',
            'delivery_type' => 'required|in:48,12',
            'item_weight' => 'required|numeric|min:0.5|max:10',
            'amount_to_collect' => 'nullable|numeric|min:0',
            'item_description' => 'nullable|string',
            'special_instruction' => 'nullable|string',
        ]);

        $order = Order::find($validated['order_id']);

        // Check if order already has Pathao shipment
        if ($order->pathaoShipment) {
            return redirect()->back()->with('error', 'This order already has a Pathao shipment.');
        }

        // Auto-populate from order if fields are not provided
        $recipientName = $validated['recipient_name'] ?? $order->receiver_name ?? $order->user->name;
        $recipientPhone = $validated['recipient_phone'] ?? $order->receiver_phone ?? $order->user->phone;
        $recipientAddress = $validated['recipient_address'] ?? $order->receiver_full_address ?? $order->shipping_address;
        $amountToCollect = $validated['amount_to_collect'] ?? ($order->payment_method === 'cod' || $order->payment_method === 'cash_on_delivery' ? $order->total : 0);
        $itemDescription = $validated['item_description'] ?? ($order->orderItems->count() . ' items');
        $specialInstruction = $validated['special_instruction'] ?? $order->delivery_instructions ?? $order->notes ?? '';

        // Create order in Pathao system via API
        $apiResponse = $this->pathaoService->createOrder([
            'store_id' => $validated['store_id'],
            'merchant_order_id' => $order->order_number,
            'recipient_name' => $recipientName,
            'recipient_phone' => $recipientPhone,
            'recipient_address' => $recipientAddress,
            'recipient_city' => $validated['recipient_city'],
            'recipient_zone' => $validated['recipient_zone'],
            'recipient_area' => $validated['recipient_area'] ?? null,
            'item_type' => $validated['item_type'],
            'delivery_type' => $validated['delivery_type'],
            'item_weight' => $validated['item_weight'],
            'item_quantity' => $order->orderItems->count(), // Integer, not string
            'item_description' => $itemDescription,
            'amount_to_collect' => $amountToCollect,
            'special_instruction' => $specialInstruction,
        ]);

        if (!$apiResponse['success']) {
            return redirect()->back()
                ->with('error', 'Failed to create Pathao shipment: ' . $apiResponse['message'])
                ->withInput();
        }

        // Get city and zone names for display
        $citiesResponse = $this->pathaoService->getCities();
        $cities = $citiesResponse['success'] ? collect($citiesResponse['data'])->keyBy('city_id') : collect();
        $zonesResponse = $this->pathaoService->getZones($validated['recipient_city']);
        $zones = $zonesResponse['success'] ? collect($zonesResponse['data'])->keyBy('zone_id') : collect();
        
        $cityName = $cities->get($validated['recipient_city'])['city_name'] ?? null;
        $zoneName = $zones->get($validated['recipient_zone'])['zone_name'] ?? null;
        $areaName = null;
        if ($validated['recipient_area']) {
            $areasResponse = $this->pathaoService->getAreas($validated['recipient_zone']);
            $areas = $areasResponse['success'] ? collect($areasResponse['data'])->keyBy('area_id') : collect();
            $areaName = $areas->get($validated['recipient_area'])['area_name'] ?? null;
        }

        // Create local Pathao shipment record
        $pathaoShipment = PathaoShipment::create([
            'order_id' => $order->id,
            'pathao_order_id' => $apiResponse['consignment_id'] ?? $apiResponse['order_id'],
            'consignment_id' => $apiResponse['consignment_id'] ?? null,
            'tracking_id' => $apiResponse['tracking_id'] ?? null,
            'store_id' => $validated['store_id'],
            'item_type' => $validated['item_type'],
            'delivery_type' => $validated['delivery_type'],
            'item_weight' => $validated['item_weight'],
            'recipient_name' => $recipientName,
            'recipient_phone' => $recipientPhone,
            'recipient_address' => $recipientAddress,
            'recipient_city_id' => $validated['recipient_city'],
            'recipient_city_name' => $cityName,
            'recipient_zone_id' => $validated['recipient_zone'],
            'recipient_zone_name' => $zoneName,
            'recipient_area_id' => $validated['recipient_area'] ?? null,
            'recipient_area_name' => $areaName,
            'amount' => $amountToCollect, // Database column is 'amount', but API uses 'amount_to_collect'
            'item_quantity' => (string)$order->orderItems->count(), // Store as string in DB
            'item_description' => $itemDescription,
            'special_instruction' => $specialInstruction,
            'merchant_order_id' => $order->order_number,
            'status' => 'Order Created',
            'api_response' => $apiResponse,
            'shipped_at' => now(),
            'created_by' => Auth::id(),
        ]);

        // Add initial status to history
        $pathaoShipment->addStatusHistory('Order Created', 'Shipment created in Pathao system');

        // Update order status
        $order->update(['status' => 'shipped']);

        return redirect()->route('admin.pathao.show', $pathaoShipment->id)
            ->with('success', 'Pathao shipment created successfully! Consignment ID: ' . ($apiResponse['consignment_id'] ?? $apiResponse['order_id']));
    }

    /**
     * Bulk create Pathao shipments
     */
    public function bulkCreate(Request $request)
    {
        $request->validate([
            'order_ids' => 'required|array|min:1',
            'order_ids.*' => 'exists:orders,id',
            'shipments' => 'required|array',
            'shipments.*.store_id' => 'required|integer',
            'shipments.*.recipient_city' => 'required|integer',
            'shipments.*.recipient_zone' => 'required|integer',
            'shipments.*.item_type' => 'required|in:1,2',
            'shipments.*.delivery_type' => 'required|in:48,12',
            'shipments.*.item_weight' => 'required|numeric|min:0.5|max:10',
        ]);

        $created = 0;
        $failed = 0;
        $errors = [];

        foreach ($request->order_ids as $orderId) {
            $order = Order::with('user')->find($orderId);
            
            if ($order->pathaoShipment) {
                $failed++;
                $errors[] = "Order {$order->order_number} already has a Pathao shipment";
                continue;
            }

            // Get individual shipment details for this order
            $shipmentData = $request->shipments[$orderId] ?? [];
            if (empty($shipmentData)) {
                $failed++;
                $errors[] = "Order {$order->order_number}: No shipment details provided";
                continue;
            }

            // Prepare receiver data with fallbacks
            $recipientName = $shipmentData['recipient_name'] ?? $order->receiver_name ?? $order->user->name ?? 'Customer';
            $recipientAddress = $shipmentData['recipient_address'] ?? $order->receiver_full_address ?? $order->shipping_address ?? '';
            $recipientPhone = $shipmentData['recipient_phone'] ?? $order->receiver_phone ?? $order->user->phone ?? '9800000000';
            $amountToCollect = $shipmentData['amount_to_collect'] ?? ($order->payment_method === 'cod' || $order->payment_method === 'cash_on_delivery' ? $order->total : 0);
            $itemDescription = $shipmentData['item_description'] ?? ($order->orderItems->count() . ' items');
            $specialInstruction = $shipmentData['special_instruction'] ?? $order->delivery_instructions ?? $order->notes ?? '';
            
            // Validate required fields
            if (empty($recipientName)) {
                $failed++;
                $errors[] = "Order {$order->order_number}: Recipient name is required";
                continue;
            }
            if (empty($recipientAddress)) {
                $failed++;
                $errors[] = "Order {$order->order_number}: Recipient address is required";
                continue;
            }
            if (empty($recipientPhone)) {
                $failed++;
                $errors[] = "Order {$order->order_number}: Recipient phone number is required";
                continue;
            }
            
            // Create shipment in Pathao
            $apiResponse = $this->pathaoService->createOrder([
                'store_id' => $shipmentData['store_id'],
                'merchant_order_id' => $order->order_number,
                'recipient_name' => $recipientName,
                'recipient_phone' => $recipientPhone,
                'recipient_address' => $recipientAddress,
                'recipient_city' => $shipmentData['recipient_city'],
                'recipient_zone' => $shipmentData['recipient_zone'],
                'recipient_area' => $shipmentData['recipient_area'] ?? null,
                'item_type' => $shipmentData['item_type'] ?? 2,
                'delivery_type' => $shipmentData['delivery_type'] ?? 48,
                'item_weight' => $shipmentData['item_weight'] ?? 0.5,
                'item_quantity' => $order->orderItems->count(),
                'item_description' => $itemDescription,
                'amount_to_collect' => $amountToCollect,
                'special_instruction' => $specialInstruction,
            ]);

            if ($apiResponse['success']) {
                // Get city and zone names for display
                $citiesResponse = $this->pathaoService->getCities();
                $cities = $citiesResponse['success'] ? collect($citiesResponse['data'])->keyBy('city_id') : collect();
                $zonesResponse = $this->pathaoService->getZones($shipmentData['recipient_city']);
                $zones = $zonesResponse['success'] ? collect($zonesResponse['data'])->keyBy('zone_id') : collect();
                
                $cityName = $cities->get($shipmentData['recipient_city'])['city_name'] ?? null;
                $zoneName = $zones->get($shipmentData['recipient_zone'])['zone_name'] ?? null;
                $areaName = null;
                if (!empty($shipmentData['recipient_area'])) {
                    $areasResponse = $this->pathaoService->getAreas($shipmentData['recipient_zone']);
                    $areas = $areasResponse['success'] ? collect($areasResponse['data'])->keyBy('area_id') : collect();
                    $areaName = $areas->get($shipmentData['recipient_area'])['area_name'] ?? null;
                }

                PathaoShipment::create([
                    'order_id' => $order->id,
                    'pathao_order_id' => $apiResponse['consignment_id'] ?? $apiResponse['order_id'],
                    'consignment_id' => $apiResponse['consignment_id'] ?? null,
                    'tracking_id' => $apiResponse['tracking_id'] ?? null,
                    'store_id' => $shipmentData['store_id'],
                    'item_type' => $shipmentData['item_type'] ?? 2,
                    'delivery_type' => $shipmentData['delivery_type'] ?? 48,
                    'item_weight' => $shipmentData['item_weight'] ?? 0.5,
                    'recipient_name' => $recipientName,
                    'recipient_phone' => $recipientPhone,
                    'recipient_address' => $recipientAddress,
                    'recipient_city_id' => $shipmentData['recipient_city'],
                    'recipient_city_name' => $cityName,
                    'recipient_zone_id' => $shipmentData['recipient_zone'],
                    'recipient_zone_name' => $zoneName,
                    'recipient_area_id' => $shipmentData['recipient_area'] ?? null,
                    'recipient_area_name' => $areaName,
                    'amount' => $amountToCollect,
                    'item_quantity' => (string)$order->orderItems->count(),
                    'item_description' => $itemDescription,
                    'special_instruction' => $specialInstruction,
                    'merchant_order_id' => $order->order_number,
                    'status' => 'Order Created',
                    'api_response' => $apiResponse,
                    'shipped_at' => now(),
                    'created_by' => Auth::id(),
                ]);

                $order->update(['status' => 'shipped']);
                $created++;
            } else {
                $failed++;
                $errors[] = "Order {$order->order_number}: {$apiResponse['message']}";
            }
        }

        $message = "Successfully created {$created} shipments.";
        if ($failed > 0) {
            $message .= " {$failed} failed.";
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'created' => $created,
            'failed' => $failed,
            'errors' => $errors
        ]);
    }

    /**
     * Display Pathao shipment details
     */
    public function show(PathaoShipment $pathaoShipment)
    {
        $pathaoShipment->load('order.user', 'order.orderItems.product', 'createdBy');
        
        // Fetch latest details from Pathao API
        if ($pathaoShipment->consignment_id) {
            $detailResponse = $this->pathaoService->getOrderDetail($pathaoShipment->consignment_id);
            $orderDetail = $detailResponse['success'] ? $detailResponse['data'] : null;
        } else {
            $orderDetail = null;
        }

        return view('admin.pathao.show', compact(
            'pathaoShipment',
            'orderDetail'
        ));
    }

    /**
     * Settings & Configuration
     */
    public function settings()
    {
        $settings = \App\Models\PathaoSetting::getForCurrentTenant();
        
        // Get stores if credentials are configured
        $stores = [];
        if ($settings && $settings->client_id && $settings->client_secret && 
            $settings->username && $settings->password) {
            $storesResponse = $this->pathaoService->getStores();
            if ($storesResponse['success']) {
                $stores = $storesResponse['data'];
            }
        }
        
        return view('admin.pathao.settings-form', compact('settings', 'stores'));
    }
    
    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'nullable|string',
            'client_secret' => 'nullable|string',
            'username' => 'nullable|string',
            'password' => 'nullable|string',
            'api_url' => 'required|string|url',
            'store_id' => 'nullable|integer',
            'default_item_type' => 'required|in:1,2',
            'default_delivery_type' => 'required|in:48,12',
            'default_item_weight' => 'required|numeric|min:0.5|max:10',
            'pickup_city_id' => 'nullable|integer',
            'pickup_zone_id' => 'nullable|integer',
            'pickup_area_id' => 'nullable|integer',
            'pickup_address' => 'nullable|string',
            'pickup_contact_name' => 'nullable|string',
            'pickup_contact_number' => 'nullable|string',
            'auto_create_shipment' => 'boolean',
            'send_notifications' => 'boolean',
        ]);

        // Trim string values
        if (isset($validated['client_id'])) {
            $validated['client_id'] = trim($validated['client_id']);
        }
        if (isset($validated['client_secret'])) {
            $validated['client_secret'] = trim($validated['client_secret']);
        }
        if (isset($validated['username'])) {
            $validated['username'] = trim($validated['username']);
        }
        if (isset($validated['api_url'])) {
            $validated['api_url'] = trim($validated['api_url']);
        }

        $settings = \App\Models\PathaoSetting::getForCurrentTenant();
        
        // If credentials changed, clear tokens to force re-authentication
        if ($settings->client_id !== $validated['client_id'] || 
            $settings->client_secret !== $validated['client_secret'] ||
            $settings->username !== $validated['username'] ||
            $settings->password !== $validated['password']) {
            $validated['access_token'] = null;
            $validated['refresh_token'] = null;
            $validated['token_expires_at'] = null;
        }
        
        $settings->update($validated);
        
        // Log for debugging
        Log::info('Pathao Settings Updated', [
            'has_client_id' => !empty($validated['client_id']),
            'has_client_secret' => !empty($validated['client_secret']),
            'has_username' => !empty($validated['username']),
            'has_password' => !empty($validated['password']),
            'has_api_url' => !empty($validated['api_url']),
        ]);

        return redirect()->route('admin.pathao.settings')->with('success', 'Settings updated successfully!');
    }

    /**
     * Get cities (AJAX)
     */
    public function getCities()
    {
        try {
            $result = $this->pathaoService->getCities();
            
            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'data' => $result['data']
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => $result['message'] ?? 'Failed to fetch cities'
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get zones (AJAX)
     */
    public function getZones(Request $request)
    {
        $request->validate([
            'city_id' => 'required|integer'
        ]);

        try {
            $result = $this->pathaoService->getZones($request->city_id);
            
            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'data' => $result['data']
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => $result['message'] ?? 'Failed to fetch zones'
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get areas (AJAX)
     */
    public function getAreas(Request $request)
    {
        $request->validate([
            'zone_id' => 'required|integer'
        ]);

        try {
            $result = $this->pathaoService->getAreas($request->zone_id);
            
            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'data' => $result['data']
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => $result['message'] ?? 'Failed to fetch areas'
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Test API connection
     */
    public function testConnection()
    {
        try {
            // Log the test attempt
            Log::info('Pathao Test Connection: Starting test', [
                'user_id' => auth()->id(),
                'tenant_id' => auth()->user()->tenant_id ?? null,
            ]);
            
            $result = $this->pathaoService->testConnection();
            
            // If failed, add hint about checking logs
            if (!$result['success']) {
                $result['message'] .= ' For detailed error information, please check Laravel logs at storage/logs/laravel.log';
            }
            
            return response()->json([
                'success' => $result['success'],
                'message' => $result['message'] ?? 'Connection test completed'
            ]);
        } catch (\Exception $e) {
            Log::error('Pathao Test Connection: Exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Connection test failed: ' . $e->getMessage() . ' Check Laravel logs for details.'
            ], 500);
        }
    }

    /**
     * Refresh shipment status from Pathao API
     */
    public function refreshStatus(PathaoShipment $pathaoShipment)
    {
        if (!$pathaoShipment->consignment_id) {
            return redirect()->back()->with('error', 'No Pathao consignment ID found');
        }

        $detailResponse = $this->pathaoService->getOrderDetail($pathaoShipment->consignment_id);
        
        if ($detailResponse['success'] && isset($detailResponse['data'])) {
            $data = $detailResponse['data'];
            
            $pathaoShipment->update([
                'status' => $data['status'] ?? $pathaoShipment->status,
                'status_type' => $data['status_type'] ?? null,
                'cod_collected' => $data['cod_collected'] ?? false,
                'cod_amount' => $data['cod_amount'] ?? null,
                'delivered_at' => isset($data['delivered_at']) ? $data['delivered_at'] : $pathaoShipment->delivered_at,
                'api_response' => $data,
            ]);
            
            if (isset($data['status'])) {
                $pathaoShipment->addStatusHistory($data['status'], 'Status refreshed from Pathao API');
            }
            
            return redirect()->back()->with('success', 'Status refreshed successfully');
        }

        return redirect()->back()->with('error', 'Failed to refresh status: ' . ($detailResponse['message'] ?? 'Unknown error'));
    }
}
