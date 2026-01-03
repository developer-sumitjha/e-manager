<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class GaaubesiService
{
    protected $baseUrl;
    protected $token;
    protected $environment;
    protected $timeout;
    protected $enableLogging;

    public function __construct()
    {
        // Try to load vendor-specific settings first
        $settings = \App\Models\GaaubesiSetting::getForCurrentTenant();
        
        if ($settings && $settings->api_token) {
            // Use vendor-specific settings
            $this->baseUrl = $settings->api_url;
            $this->token = $settings->api_token;
        } else {
            // Fallback to default config (for testing or if not configured)
            $this->environment = config('gaaubesi.environment', 'testing');
            $envConfig = config("gaaubesi.{$this->environment}");
            
            $this->baseUrl = $envConfig['base_url'] ?? 'https://api.gaaubesi.com';
            $this->token = $envConfig['token'] ?? '';
        }
        
        $this->timeout = config('gaaubesi.timeout', 30);
        $this->enableLogging = config('gaaubesi.enable_logging', true);
    }

    /**
     * Get HTTP client with authorization headers
     */
    protected function getClient()
    {
        return Http::timeout($this->timeout)
            ->withHeaders([
                'Authorization' => "Token {$this->token}",
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ]);
    }

    /**
     * Log API request/response if logging is enabled
     */
    protected function logApi($method, $endpoint, $data = null, $response = null)
    {
        if ($this->enableLogging) {
            Log::channel('daily')->info("Gaaubesi API {$method}: {$endpoint}", [
                'request' => $data,
                'response' => $response,
            ]);
        }
    }

    /**
     * Create an order in Gaaubesi system
     * 
     * @param array $orderData
     * @return array
     */
    public function createOrder(array $orderData)
    {
        try {
            $endpoint = $this->baseUrl . config('gaaubesi.endpoints.create_order');
            
            $payload = [
                'branch' => $orderData['branch'] ?? config('gaaubesi.default_branch'),
                'destination_branch' => $orderData['destination_branch'] ?? config('gaaubesi.default_branch'),
                'receiver_name' => $orderData['receiver_name'],
                'receiver_address' => $orderData['receiver_address'],
                'receiver_number' => $orderData['receiver_number'],
                'cod_charge' => $orderData['cod_charge'],
                'Package_access' => $orderData['package_access'] ?? "Can't Open",
                'delivery_type' => $orderData['delivery_type'] ?? 'Drop Off',
                'remarks' => $orderData['remarks'] ?? '',
                'package_type' => $orderData['package_type'] ?? '',
                'order_contact_name' => $orderData['order_contact_name'] ?? '',
                'order_contact_number' => $orderData['order_contact_number'] ?? '',
            ];

            $response = $this->getClient()->post($endpoint, $payload);
            $result = $response->json();

            $this->logApi('POST', 'create_order', $payload, $result);

            if ($response->successful() && isset($result['success']) && $result['success']) {
                return [
                    'success' => true,
                    'order_id' => $result['order_id'],
                    'message' => $result['message'] ?? 'Order created successfully',
                ];
            }

            return [
                'success' => false,
                'message' => $result['message'] ?? 'Failed to create order',
                'errors' => $result,
            ];

        } catch (Exception $e) {
            Log::error('Gaaubesi Create Order Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'API request failed: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Get order details
     * 
     * @param int $orderId
     * @return array
     */
    public function getOrderDetail($orderId)
    {
        try {
            $endpoint = $this->baseUrl . config('gaaubesi.endpoints.order_detail');
            
            $response = $this->getClient()->get($endpoint, [
                'order_id' => $orderId,
            ]);
            
            $result = $response->json();
            $this->logApi('GET', 'order_detail', ['order_id' => $orderId], $result);

            if ($response->successful() && isset($result['success']) && $result['success']) {
                return [
                    'success' => true,
                    'data' => $result,
                ];
            }

            return [
                'success' => false,
                'message' => $result['message'] ?? 'Failed to fetch order details',
                'errors' => $result,
            ];

        } catch (Exception $e) {
            Log::error('Gaaubesi Get Order Detail Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'API request failed: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Get order status
     * 
     * @param int $orderId
     * @return array
     */
    public function getOrderStatus($orderId)
    {
        try {
            $endpoint = $this->baseUrl . config('gaaubesi.endpoints.order_status');
            
            $response = $this->getClient()->get($endpoint, [
                'order_id' => $orderId,
            ]);
            
            $result = $response->json();
            $this->logApi('GET', 'order_status', ['order_id' => $orderId], $result);

            if ($response->successful() && isset($result['success'])) {
                return [
                    'success' => true,
                    'status' => $result['status'] ?? [],
                ];
            }

            return [
                'success' => false,
                'message' => 'Failed to fetch order status',
                'errors' => $result,
            ];

        } catch (Exception $e) {
            Log::error('Gaaubesi Get Order Status Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'API request failed: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Get order comments list
     * 
     * @param int $orderId
     * @return array
     */
    public function getOrderComments($orderId)
    {
        try {
            $endpoint = $this->baseUrl . config('gaaubesi.endpoints.comment_list');
            
            $response = $this->getClient()->get($endpoint, [
                'order_id' => $orderId,
            ]);
            
            $result = $response->json();
            $this->logApi('GET', 'comment_list', ['order_id' => $orderId], $result);

            if ($response->successful() && isset($result['success'])) {
                return [
                    'success' => true,
                    'comments' => $result['comments'] ?? [],
                ];
            }

            return [
                'success' => false,
                'message' => 'Failed to fetch comments',
                'errors' => $result,
            ];

        } catch (Exception $e) {
            Log::error('Gaaubesi Get Order Comments Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'API request failed: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Post a comment on an order
     * 
     * @param int $orderId
     * @param string $comment
     * @return array
     */
    public function postOrderComment($orderId, $comment)
    {
        try {
            $endpoint = $this->baseUrl . config('gaaubesi.endpoints.comment_create');
            
            $payload = [
                'order' => (string)$orderId,
                'comments' => $comment,
            ];

            $response = $this->getClient()->post($endpoint, $payload);
            $result = $response->json();

            $this->logApi('POST', 'comment_create', $payload, $result);

            if ($response->successful() && isset($result['success']) && $result['success']) {
                return [
                    'success' => true,
                    'message' => $result['message'] ?? 'Comment posted successfully',
                ];
            }

            return [
                'success' => false,
                'message' => $result['message'] ?? 'Failed to post comment',
                'errors' => $result,
            ];

        } catch (Exception $e) {
            Log::error('Gaaubesi Post Comment Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'API request failed: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Get locations and their delivery rates
     * 
     * @return array
     */
    public function getLocationsData()
    {
        try {
            $endpoint = $this->baseUrl . config('gaaubesi.endpoints.locations_data');
            
            $response = $this->getClient()->get($endpoint);
            $result = $response->json();

            $this->logApi('GET', 'locations_data', null, $result);

            if ($response->successful()) {
                // Extract location names from the response (keys of the associative array)
                $locations = is_array($result) ? array_keys($result) : [];
                
                return [
                    'success' => true,
                    'locations' => $locations,
                ];
            }

            return [
                'success' => false,
                'message' => 'Failed to fetch locations data',
            ];

        } catch (Exception $e) {
            Log::error('Gaaubesi Get Locations Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'API request failed: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Get print label URL
     * 
     * @param int $orderId
     * @return string
     */
    public function getPrintLabelUrl($orderId)
    {
        $endpoint = $this->baseUrl . config('gaaubesi.endpoints.print_label');
        return $endpoint . '?order_id=' . $orderId;
    }

    /**
     * Get print label binary URL
     * 
     * @param int $orderId
     * @return string
     */
    public function getPrintLabelBinaryUrl($orderId)
    {
        $endpoint = $this->baseUrl . config('gaaubesi.endpoints.print_label_binary');
        return $endpoint . '?order_id=' . $orderId;
    }

    /**
     * Download label as binary (PDF)
     * 
     * @param int $orderId
     * @return array
     */
    public function downloadLabel($orderId)
    {
        try {
            $endpoint = $this->baseUrl . config('gaaubesi.endpoints.print_label_binary');
            
            $response = $this->getClient()->get($endpoint, [
                'order_id' => $orderId,
            ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'content' => $response->body(),
                    'content_type' => $response->header('Content-Type'),
                ];
            }

            return [
                'success' => false,
                'message' => 'Failed to download label',
            ];

        } catch (Exception $e) {
            Log::error('Gaaubesi Download Label Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'API request failed: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Get package access options
     * 
     * @return array
     */
    public function getPackageAccessOptions()
    {
        return config('gaaubesi.package_access_options', ["Can't Open", "Can Open"]);
    }

    /**
     * Get delivery type options
     * 
     * @return array
     */
    public function getDeliveryTypeOptions()
    {
        return config('gaaubesi.delivery_type_options', ['Pickup', 'Drop Off']);
    }

    /**
     * Validate if environment is production
     * 
     * @return bool
     */
    public function isProduction()
    {
        return $this->environment === 'production';
    }

    /**
     * Get current environment
     * 
     * @return string
     */
    public function getEnvironment()
    {
        return $this->environment;
    }

    /**
     * Get service stations for a specific location
     */
    public function getServiceStations($location)
    {
        try {
            // For now, return mock data since the API endpoint might not exist
            // In production, this would call the actual Gaaubesi API
            
            $mockStations = [
                [
                    'name' => 'Gaaubesi ' . $location . ' Branch',
                    'type' => 'Main Branch',
                    'address' => 'Main Road, ' . $location . ', Nepal',
                    'phone' => '+977-1-1234567',
                    'hours' => '9:00 AM - 6:00 PM',
                    'services' => ['Parcel Delivery', 'COD Collection', 'Package Pickup'],
                    'location' => $location . ', Nepal'
                ],
                [
                    'name' => 'Gaaubesi ' . $location . ' Service Center',
                    'type' => 'Service Center',
                    'address' => 'Service Road, ' . $location . ', Nepal',
                    'phone' => '+977-1-1234568',
                    'hours' => '8:00 AM - 8:00 PM',
                    'services' => ['Customer Service', 'Package Tracking', 'Complaint Handling'],
                    'location' => $location . ', Nepal'
                ]
            ];

            return [
                'success' => true,
                'stations' => $mockStations,
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error fetching service stations: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Test API connection
     */
    public function testConnection()
    {
        try {
            // Test with a simple API call
            $response = $this->getLocationsData();
            
            return [
                'success' => $response['success'],
                'message' => $response['success'] ? 'Connection successful' : 'Connection failed',
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Connection test failed: ' . $e->getMessage(),
            ];
        }
    }
}


