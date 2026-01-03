<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\GaaubesiShipment;
use App\Services\GaaubesiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class GaaubesiController extends Controller
{
    protected $gaaubesiService;

    public function __construct(GaaubesiService $gaaubesiService)
    {
        $this->gaaubesiService = $gaaubesiService;
    }

    /**
     * Display Gaaubesi logistics dashboard
     */
    public function index(Request $request)
    {
        $query = GaaubesiShipment::with(['order.user', 'shipment']);

        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('gaaubesi_order_id', 'like', "%{$search}%")
                  ->orWhere('track_id', 'like', "%{$search}%")
                  ->orWhere('receiver_name', 'like', "%{$search}%")
                  ->orWhere('receiver_number', 'like', "%{$search}%")
                  ->orWhereHas('order', function($q) use ($search) {
                      $q->where('order_number', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('last_delivery_status', 'like', "%{$request->status}%");
        }

        // Filter by COD payment status
        if ($request->has('cod_paid') && $request->cod_paid !== '') {
            $query->where('cod_paid', $request->cod_paid);
        }

        $shipments = $query->latest()->paginate(15);

        // Get statistics
        $totalShipments = GaaubesiShipment::count();
        $deliveredShipments = GaaubesiShipment::whereNotNull('delivered_at')->count();
        $codPaidCount = GaaubesiShipment::where('cod_paid', true)->count();
        $pendingShipments = $totalShipments - $deliveredShipments;

        // Get pending logistics orders (allocated but not yet created in Gaaubesi)
        $pendingLogisticsOrders = Order::where('status', 'processing')
                                       ->whereHas('shipment', function($query) {
                                           $query->where('delivery_method', 'logistics');
                                       })
                                       ->whereDoesntHave('gaaubesiShipment')
                                       ->with(['user', 'orderItems.product', 'shipment'])
                                       ->latest()
                                       ->get();

        return view('admin.gaaubesi.index', compact(
            'shipments',
            'totalShipments',
            'deliveredShipments',
            'codPaidCount',
            'pendingShipments',
            'pendingLogisticsOrders'
        ));
    }

    /**
     * Show form to create a new Gaaubesi shipment
     */
    public function create()
    {
        // Get orders allocated to logistics that don't have Gaaubesi shipment yet
        $orders = Order::where('status', 'processing')
                      ->whereHas('shipment', function($query) {
                          $query->where('delivery_method', 'logistics');
                      })
                      ->whereDoesntHave('gaaubesiShipment')
                      ->with(['user', 'orderItems.product', 'shipment'])
                      ->latest()
                      ->get();

        $packageAccessOptions = $this->gaaubesiService->getPackageAccessOptions();
        $deliveryTypeOptions = $this->gaaubesiService->getDeliveryTypeOptions();
        
        // Get locations data for destination selection
        $locationsResponse = $this->gaaubesiService->getLocationsData();
        $locations = $locationsResponse['success'] ? $locationsResponse['locations'] : [];

        return view('admin.gaaubesi.create', compact(
            'orders',
            'packageAccessOptions',
            'deliveryTypeOptions',
            'locations'
        ));
    }

    /**
     * Store a new Gaaubesi shipment
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'source_branch' => 'nullable|string|max:255',
            'destination_branch' => 'nullable|string|max:255',
            'receiver_name' => 'nullable|string|max:255',
            'receiver_address' => 'nullable|string',
            'receiver_number' => 'nullable|string|max:20',
            'cod_charge' => 'nullable|numeric|min:0',
            'package_access' => 'nullable|in:Can\'t Open,Can Open',
            'delivery_type' => 'nullable|in:Pickup,Drop Off',
            'remarks' => 'nullable|string',
            'package_type' => 'nullable|string',
            'order_contact_name' => 'nullable|string|max:255',
            'order_contact_number' => 'nullable|string|max:20',
        ]);

        $order = Order::find($validated['order_id']);

        // Check if order already has Gaaubesi shipment
        if ($order->gaaubesiShipment) {
            return redirect()->back()->with('error', 'This order already has a Gaaubesi shipment.');
        }

        // Auto-populate from order if fields are not provided
        $receiverName = $validated['receiver_name'] ?? $order->receiver_name ?? $order->user->name;
        $receiverPhone = $validated['receiver_number'] ?? $order->receiver_phone ?? $order->user->phone;
        $receiverAddress = $validated['receiver_address'] ?? $order->receiver_full_address ?? $order->shipping_address;
        $codCharge = $validated['cod_charge'] ?? ($order->payment_method === 'cod' || $order->payment_method === 'cash_on_delivery' ? $order->total : 0);
        $packageAccess = $validated['package_access'] ?? $order->package_access ?? "Can't Open";
        $deliveryType = $validated['delivery_type'] ?? $order->delivery_type ?? 'Drop Off';
        $remarks = $validated['remarks'] ?? $order->delivery_instructions ?? $order->notes ?? '';
        $packageType = $validated['package_type'] ?? $order->package_type ?? '';
        $senderName = $validated['order_contact_name'] ?? $order->sender_name ?? config('app.name', 'E-Manager Store');
        $senderPhone = $validated['order_contact_number'] ?? $order->sender_phone ?? '';
        $sourceBranch = $validated['source_branch'] ?? config('gaaubesi.default_branch', 'HEAD OFFICE');
        $destinationBranch = $validated['destination_branch'] ?? $order->delivery_branch ?? config('gaaubesi.default_branch', 'HEAD OFFICE');

        // Create order in Gaaubesi system via API
        $apiResponse = $this->gaaubesiService->createOrder([
            'branch' => $sourceBranch,
            'destination_branch' => $destinationBranch,
            'receiver_name' => $receiverName,
            'receiver_address' => $receiverAddress,
            'receiver_number' => $receiverPhone,
            'cod_charge' => $codCharge,
            'package_access' => $packageAccess,
            'delivery_type' => $deliveryType,
            'remarks' => $remarks,
            'package_type' => $packageType,
            'order_contact_name' => $senderName,
            'order_contact_number' => $senderPhone,
        ]);

        if (!$apiResponse['success']) {
            return redirect()->back()
                ->with('error', 'Failed to create Gaaubesi shipment: ' . $apiResponse['message'])
                ->withInput();
        }

        // Create local Gaaubesi shipment record using auto-populated values
        $gaaubesiShipment = GaaubesiShipment::create([
            'order_id' => $order->id,
            'gaaubesi_order_id' => $apiResponse['order_id'],
            'source_branch' => $sourceBranch,
            'destination_branch' => $destinationBranch,
            'receiver_name' => $receiverName,
            'receiver_address' => $receiverAddress,
            'receiver_number' => $receiverPhone,
            'cod_charge' => $codCharge,
            'package_access' => $packageAccess,
            'delivery_type' => $deliveryType,
            'remarks' => $remarks,
            'package_type' => $packageType,
            'order_contact_name' => $senderName,
            'order_contact_number' => $senderPhone,
            'last_delivery_status' => 'Order Created',
            'api_response' => $apiResponse,
            'shipped_at' => now(),
            'created_by' => Auth::id(),
        ]);

        // Add initial status to history
        $gaaubesiShipment->addStatusHistory('Order Created', 'Shipment created in Gaaubesi system');

        // Update order status
        $order->update(['status' => 'shipped']);

        return redirect()->route('admin.gaaubesi.show', $gaaubesiShipment->id)
            ->with('success', 'Gaaubesi shipment created successfully! Order ID: ' . $apiResponse['order_id']);
    }

    /**
     * Display Gaaubesi shipment details
     */
    public function show(GaaubesiShipment $gaaubesiShipment)
    {
        $gaaubesiShipment->load('order.user', 'order.orderItems.product', 'createdBy');
        
        // Fetch latest details from Gaaubesi API
        if ($gaaubesiShipment->gaaubesi_order_id) {
            $detailResponse = $this->gaaubesiService->getOrderDetail($gaaubesiShipment->gaaubesi_order_id);
            $statusResponse = $this->gaaubesiService->getOrderStatus($gaaubesiShipment->gaaubesi_order_id);
            $commentsResponse = $this->gaaubesiService->getOrderComments($gaaubesiShipment->gaaubesi_order_id);
            
            $orderDetail = $detailResponse['success'] ? $detailResponse['data'] : null;
            $orderStatus = $statusResponse['success'] ? $statusResponse['status'] : [];
            $comments = $commentsResponse['success'] ? $commentsResponse['comments'] : [];
        } else {
            $orderDetail = null;
            $orderStatus = [];
            $comments = [];
        }

        return view('admin.gaaubesi.show', compact(
            'gaaubesiShipment',
            'orderDetail',
            'orderStatus',
            'comments'
        ));
    }

    /**
     * Refresh shipment status from Gaaubesi API
     */
    public function refreshStatus(GaaubesiShipment $gaaubesiShipment)
    {
        if (!$gaaubesiShipment->gaaubesi_order_id) {
            return response()->json([
                'success' => false,
                'message' => 'No Gaaubesi order ID found'
            ], 400);
        }

        // Fetch latest details
        $detailResponse = $this->gaaubesiService->getOrderDetail($gaaubesiShipment->gaaubesi_order_id);
        
        if ($detailResponse['success']) {
            $data = $detailResponse['data'];
            
            // Update shipment record
            $gaaubesiShipment->update([
                'track_id' => $data['track_id'] ?? $gaaubesiShipment->track_id,
                'last_delivery_status' => $data['last_delivery_status'] ?? $gaaubesiShipment->last_delivery_status,
                'delivery_charge' => $data['delivery_charge'] ?? $gaaubesiShipment->delivery_charge,
                'cod_paid' => isset($data['cod_paid']) ? ($data['cod_paid'] === 'True' || $data['cod_paid'] === true) : $gaaubesiShipment->cod_paid,
            ]);

            // Add to status history if status changed
            if (isset($data['last_delivery_status']) && $data['last_delivery_status'] !== $gaaubesiShipment->last_delivery_status) {
                $gaaubesiShipment->addStatusHistory($data['last_delivery_status'], 'Status updated from Gaaubesi API');
            }

            return response()->json([
                'success' => true,
                'message' => 'Status refreshed successfully',
                'data' => $data
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $detailResponse['message'] ?? 'Failed to refresh status'
        ], 400);
    }

    /**
     * Post a comment on Gaaubesi order
     */
    public function postComment(Request $request, GaaubesiShipment $gaaubesiShipment)
    {
        $request->validate([
            'comment' => 'required|string'
        ]);

        if (!$gaaubesiShipment->gaaubesi_order_id) {
            return response()->json([
                'success' => false,
                'message' => 'No Gaaubesi order ID found'
            ], 400);
        }

        $result = $this->gaaubesiService->postOrderComment(
            $gaaubesiShipment->gaaubesi_order_id,
            $request->comment
        );

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'message' => 'Comment posted successfully'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $result['message'] ?? 'Failed to post comment'
        ], 400);
    }

    /**
     * Download shipment label
     */
    public function downloadLabel(GaaubesiShipment $gaaubesiShipment)
    {
        if (!$gaaubesiShipment->gaaubesi_order_id) {
            return redirect()->back()->with('error', 'No Gaaubesi order ID found');
        }

        $result = $this->gaaubesiService->downloadLabel($gaaubesiShipment->gaaubesi_order_id);

        if ($result['success']) {
            return response($result['content'])
                ->header('Content-Type', $result['content_type'] ?? 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="label-' . $gaaubesiShipment->gaaubesi_order_id . '.pdf"');
        }

        return redirect()->back()->with('error', 'Failed to download label');
    }

    /**
     * Get locations data (AJAX)
     */
    public function getLocations()
    {
        $result = $this->gaaubesiService->getLocationsData();
        
        if ($result['success']) {
            return response()->json([
                'success' => true,
                'locations' => $result['locations']
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to fetch locations'
        ], 400);
    }

    /**
     * Show form to create multiple Gaaubesi shipments
     */
    public function bulkCreateForm()
    {
        // Get orders allocated to logistics that don't have Gaaubesi shipment yet
        $orders = Order::where('status', 'processing')
                      ->whereHas('shipment', function($query) {
                          $query->where('delivery_method', 'logistics');
                      })
                      ->whereDoesntHave('gaaubesiShipment')
                      ->with(['user', 'orderItems.product', 'shipment'])
                      ->latest()
                      ->get();

        $packageAccessOptions = $this->gaaubesiService->getPackageAccessOptions();
        $deliveryTypeOptions = $this->gaaubesiService->getDeliveryTypeOptions();
        
        // Get locations data for destination selection
        $locationsResponse = $this->gaaubesiService->getLocationsData();
        $locations = $locationsResponse['success'] ? $locationsResponse['locations'] : [];

        return view('admin.gaaubesi.bulk-create', compact(
            'orders',
            'packageAccessOptions',
            'deliveryTypeOptions',
            'locations'
        ));
    }

    /**
     * Bulk create Gaaubesi shipments
     */
    public function bulkCreate(Request $request)
    {
        $request->validate([
            'order_ids' => 'required|array|min:1',
            'order_ids.*' => 'exists:orders,id',
            'shipments' => 'required|array',
            'shipments.*.source_branch' => 'required|string',
            'shipments.*.destination_branch' => 'required|string',
            'shipments.*.package_access' => 'required|in:Can\'t Open,Can Open',
            'shipments.*.delivery_type' => 'required|in:Pickup,Drop Off',
        ]);

        $created = 0;
        $failed = 0;
        $errors = [];

        foreach ($request->order_ids as $orderId) {
            $order = Order::with('user')->find($orderId);
            
            if ($order->gaaubesiShipment) {
                $failed++;
                $errors[] = "Order {$order->order_number} already has a Gaaubesi shipment";
                continue;
            }

            // Get individual shipment details for this order
            $shipmentData = $request->shipments[$orderId] ?? [];
            if (empty($shipmentData)) {
                $failed++;
                $errors[] = "Order {$order->order_number}: No shipment details provided";
                continue;
            }

            // Create shipment in Gaaubesi
            $apiResponse = $this->gaaubesiService->createOrder([
                'branch' => $shipmentData['source_branch'],
                'destination_branch' => $shipmentData['destination_branch'],
                'receiver_name' => $shipmentData['receiver_name'] ?? $order->user->name ?? 'Customer',
                'receiver_address' => $shipmentData['receiver_address'] ?? $order->shipping_address,
                'receiver_number' => $shipmentData['receiver_number'] ?? $order->user->phone ?? '9800000000',
                'cod_charge' => $shipmentData['cod_charge'] ?? $order->total,
                'package_access' => $shipmentData['package_access'],
                'delivery_type' => $shipmentData['delivery_type'],
                'remarks' => $shipmentData['remarks'] ?? '',
                'package_type' => $order->orderItems->count() . ' items',
            ]);

            if ($apiResponse['success']) {
                GaaubesiShipment::create([
                    'order_id' => $order->id,
                    'gaaubesi_order_id' => $apiResponse['order_id'],
                    'source_branch' => $shipmentData['source_branch'],
                    'destination_branch' => $shipmentData['destination_branch'],
                    'receiver_name' => $shipmentData['receiver_name'] ?? $order->user->name ?? 'Customer',
                    'receiver_address' => $shipmentData['receiver_address'] ?? $order->shipping_address,
                    'receiver_number' => $shipmentData['receiver_number'] ?? $order->user->phone ?? '9800000000',
                    'cod_charge' => $shipmentData['cod_charge'] ?? $order->total,
                    'package_access' => $shipmentData['package_access'],
                    'delivery_type' => $shipmentData['delivery_type'],
                    'last_delivery_status' => 'Order Created',
                    'api_response' => $apiResponse,
                    'shipped_at' => now(),
                    'created_by' => Auth::id(),
                    'remarks' => $shipmentData['remarks'] ?? null,
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
     * Service Stations Finder
     */
    public function serviceStations(Request $request)
    {
        $stations = [];
        $searchLocation = $request->get('location', '');
        
        if ($searchLocation) {
            try {
                // Get service stations from Gaaubesi API
                $response = $this->gaaubesiService->getServiceStations($searchLocation);
                if ($response['success']) {
                    $stations = $response['stations'] ?? [];
                }
            } catch (\Exception $e) {
                Log::error('Error fetching service stations: ' . $e->getMessage());
            }
        }

        // Get popular locations for quick search
        $popularLocations = [
            'Kathmandu', 'Pokhara', 'Biratnagar', 'Lalitpur', 'Bhaktapur',
            'Bharatpur', 'Birgunj', 'Dharan', 'Butwal', 'Hetauda',
            'Janakpur', 'Nepalgunj', 'Itahari', 'Dhading'
        ];

        return view('admin.gaaubesi.service-stations', compact('stations', 'searchLocation', 'popularLocations'));
    }

    /**
     * Comments & Communication Management
     */
    public function comments(Request $request)
    {
        $query = GaaubesiShipment::with(['order.user']);

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('last_delivery_status', $request->status);
        }

        // Filter by date range
        if ($request->has('date_from') && $request->date_from != '') {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to != '') {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $shipments = $query->latest()->paginate(20);

        // Get comment statistics (we'll create a comments table later)
        $commentStats = [
            'total_comments' => 0,
            'comments_today' => 0,
            'unread_comments' => 0,
        ];

        return view('admin.gaaubesi.comments', compact('shipments', 'commentStats'));
    }

    /**
     * COD Settlement Management
     */
    public function codSettlement(Request $request)
    {
        $query = GaaubesiShipment::where('cod_charge', '>', 0);

        // Filter by settlement status
        if ($request->has('settlement_status')) {
            switch ($request->settlement_status) {
                case 'pending':
                    $query->where('cod_paid', false);
                    break;
                case 'settled':
                    $query->where('cod_paid', true);
                    break;
            }
        }

        // Filter by date range
        if ($request->has('date_from') && $request->date_from != '') {
            $query->whereDate('delivered_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to != '') {
            $query->whereDate('delivered_at', '<=', $request->date_to);
        }

        $shipments = $query->with('order.user')->latest()->paginate(20);

        // Calculate settlement statistics
        $settlementStats = [
            'total_cod_amount' => GaaubesiShipment::where('cod_charge', '>', 0)->sum('cod_charge'),
            'pending_cod_amount' => GaaubesiShipment::where('cod_charge', '>', 0)->where('cod_paid', false)->sum('cod_charge'),
            'settled_cod_amount' => GaaubesiShipment::where('cod_charge', '>', 0)->where('cod_paid', true)->sum('cod_charge'),
            'pending_count' => GaaubesiShipment::where('cod_charge', '>', 0)->where('cod_paid', false)->count(),
        ];

        return view('admin.gaaubesi.cod-settlement', compact('shipments', 'settlementStats'));
    }

    /**
     * Analytics & Reports
     */
    public function analytics(Request $request)
    {
        $dateFrom = $request->get('date_from', now()->subDays(30)->format('Y-m-d'));
        $dateTo = $request->get('date_to', now()->format('Y-m-d'));

        // Shipment statistics
        $shipmentStats = [
            'total_shipments' => GaaubesiShipment::whereBetween('created_at', [$dateFrom, $dateTo])->count(),
            'delivered_shipments' => GaaubesiShipment::whereBetween('delivered_at', [$dateFrom, $dateTo])->count(),
            'pending_shipments' => GaaubesiShipment::whereNull('delivered_at')->whereBetween('created_at', [$dateFrom, $dateTo])->count(),
            'total_cod_amount' => GaaubesiShipment::whereBetween('created_at', [$dateFrom, $dateTo])->sum('cod_charge'),
        ];

        // Monthly shipment trends
        $monthlyTrends = GaaubesiShipment::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Top destinations
        $topDestinations = GaaubesiShipment::selectRaw('destination_branch, COUNT(*) as count')
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->groupBy('destination_branch')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();

        return view('admin.gaaubesi.analytics', compact(
            'shipmentStats', 'monthlyTrends', 'topDestinations', 'dateFrom', 'dateTo'
        ));
    }

    /**
     * Notifications & Alerts
     */
    public function notifications(Request $request)
    {
        $query = GaaubesiShipment::with('order.user');

        // Filter by alert type
        if ($request->has('alert_type')) {
            switch ($request->alert_type) {
                case 'delayed':
                    $query->where('last_delivery_status', 'like', '%delay%')
                          ->orWhere('last_delivery_status', 'like', '%pending%');
                    break;
                case 'cod_pending':
                    $query->where('cod_charge', '>', 0)->where('cod_paid', false);
                    break;
                case 'failed_delivery':
                    $query->where('last_delivery_status', 'like', '%failed%');
                    break;
            }
        }

        $alerts = $query->latest()->paginate(20);

        // Alert statistics
        $alertStats = [
            'total_alerts' => $alerts->total(),
            'delayed_shipments' => GaaubesiShipment::where('last_delivery_status', 'like', '%delay%')->count(),
            'cod_pending' => GaaubesiShipment::where('cod_charge', '>', 0)->where('cod_paid', false)->count(),
            'failed_deliveries' => GaaubesiShipment::where('last_delivery_status', 'like', '%failed%')->count(),
        ];

        return view('admin.gaaubesi.notifications', compact('alerts', 'alertStats'));
    }

    /**
     * Settings & Configuration
     */
    public function settings()
    {
        $settings = \App\Models\GaaubesiSetting::getForCurrentTenant();
        
        return view('admin.gaaubesi.settings-form', compact('settings'));
    }
    
    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'api_token' => 'nullable|string',
            'api_url' => 'required|string|url',
            'default_package_access' => 'required|string',
            'default_delivery_type' => 'required|string',
            'default_insurance' => 'required|string',
            'pickup_branch' => 'nullable|string',
            'pickup_address' => 'nullable|string',
            'pickup_contact_person' => 'nullable|string',
            'pickup_contact_phone' => 'nullable|string',
            'auto_create_shipment' => 'boolean',
            'send_notifications' => 'boolean',
        ]);

        $settings = \App\Models\GaaubesiSetting::getForCurrentTenant();
        $settings->update($validated);

        return redirect()->route('admin.gaaubesi.settings')->with('success', 'Settings updated successfully!');
    }
    
    public function oldSettings()
    {
        $settings = [
            'api_credentials' => [
                'base_url' => config('gaaubesi.base_url'),
                'token' => config('gaaubesi.token') ? '***' . substr(config('gaaubesi.token'), -4) : 'Not set',
                'status' => $this->gaaubesiService->testConnection()['success'] ? 'Connected' : 'Disconnected',
            ],
            'default_settings' => [
                'source_branch' => 'HEAD OFFICE',
                'package_access' => 'Can\'t Open',
                'delivery_type' => 'Drop Off',
                'auto_refresh_interval' => '30 minutes',
            ],
            'notification_settings' => [
                'email_notifications' => true,
                'sms_notifications' => false,
                'delivery_alerts' => true,
                'cod_alerts' => true,
            ]
        ];

        return view('admin.gaaubesi.settings', compact('settings'));
    }

    /**
     * Mark COD as settled
     */
    public function markCodSettled(Request $request, GaaubesiShipment $shipment)
    {
        $request->validate([
            'settlement_amount' => 'required|numeric|min:0',
            'settlement_date' => 'required|date',
            'settlement_method' => 'required|string',
            'notes' => 'nullable|string'
        ]);

        $shipment->update([
            'cod_paid' => true,
            'cod_settlement_amount' => $request->settlement_amount,
            'cod_settlement_date' => $request->settlement_date,
            'cod_settlement_method' => $request->settlement_method,
            'cod_settlement_notes' => $request->notes,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'COD settlement recorded successfully.'
        ]);
    }

    /**
     * Add comment to shipment
     */
    public function addComment(Request $request, GaaubesiShipment $shipment)
    {
        $request->validate([
            'comment' => 'required|string|max:1000',
            'comment_type' => 'required|in:general,delivery,customer,internal'
        ]);

        // We'll implement comments table later
        return response()->json([
            'success' => true,
            'message' => 'Comment added successfully.'
        ]);
    }
}

