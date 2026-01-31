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
        // Always use vendor-specific settings from database
        $settings = \App\Models\GaaubesiSetting::getForCurrentTenant();
        
        // Trim and check if values are not empty
        $apiToken = $settings ? trim($settings->api_token ?? '') : '';
        $apiUrl = $settings ? trim($settings->api_url ?? '') : '';
        
        if ($settings && !empty($apiToken) && !empty($apiUrl)) {
            // Use vendor-specific settings (required)
            $this->baseUrl = $apiUrl;
            $this->token = $apiToken;
        } else {
            // Fallback to config for base URL only (token must come from database)
            $this->environment = config('gaaubesi.environment', 'production');
            $envConfig = config("gaaubesi.{$this->environment}");
            
            $this->baseUrl = $envConfig['base_url'] ?? 'https://delivery.gaaubesi.com/api/v1';
            // Token must be set in vendor settings - no fallback
            $this->token = $apiToken;
        }
        
        $this->timeout = config('gaaubesi.timeout', 30);
        $this->enableLogging = config('gaaubesi.enable_logging', true);
    }

    /**
     * Check if service is properly configured with token and URL
     * 
     * @return bool
     */
    public function isConfigured()
    {
        $token = trim($this->token ?? '');
        $url = trim($this->baseUrl ?? '');
        return !empty($token) && !empty($url);
    }

    /**
     * Get HTTP client with authorization headers
     */
    protected function getClient()
    {
        if (!$this->isConfigured()) {
            throw new Exception('Gaaubesi API is not configured. Please set API token and URL in Gaaubesi Settings.');
        }
        
        // Ensure token is trimmed and has no extra whitespace
        $token = trim($this->token);
        
        // Log the request details for debugging (without exposing full token)
        Log::info('Gaaubesi API Request', [
            'base_url' => $this->baseUrl,
            'token_length' => strlen($token),
            'token_preview' => substr($token, 0, 10) . '...' . substr($token, -4),
            'authorization_header' => 'Token ' . substr($token, 0, 10) . '...',
        ]);
        
        return Http::timeout($this->timeout)
            ->withHeaders([
                'Authorization' => "Token {$token}",
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
            // Reload settings to ensure we have the latest values
            $this->reloadSettings();
            
            if (!$this->isConfigured()) {
                $settings = \App\Models\GaaubesiSetting::getForCurrentTenant();
                $hasToken = $settings && !empty(trim($settings->api_token ?? ''));
                $hasUrl = $settings && !empty(trim($settings->api_url ?? ''));
                
                $message = 'Gaaubesi API is not configured. ';
                if (!$hasToken && !$hasUrl) {
                    $message .= 'Please set both API token and URL in Gaaubesi Settings.';
                } elseif (!$hasToken) {
                    $message .= 'Please set API token in Gaaubesi Settings.';
                } elseif (!$hasUrl) {
                    $message .= 'Please set API URL in Gaaubesi Settings.';
                }
                
                return [
                    'success' => false,
                    'message' => $message,
                ];
            }
            
            // Ensure base URL doesn't have trailing slash and endpoint starts with /
            $baseUrl = rtrim($this->baseUrl, '/');
            $endpointPath = config('gaaubesi.endpoints.create_order');
            $endpoint = $baseUrl . $endpointPath;
            
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
            
            // Get raw response body to check for HTML responses
            $rawBody = $response->body();
            $statusCode = $response->status();
            
            // Check if response is HTML (likely a login page or error page)
            $isHtml = !empty($rawBody) && (
                stripos($rawBody, '<!DOCTYPE html') !== false ||
                stripos($rawBody, '<html') !== false ||
                stripos($rawBody, '<head') !== false
            );
            
            if ($isHtml && $response->successful()) {
                return [
                    'success' => false,
                    'message' => 'API returned HTML login page instead of JSON data. This usually means your API token is invalid or expired. Please check your API token in Gaaubesi Settings.',
                    'errors' => ['html_response' => true],
                ];
            }
            
            $result = $response->json();

            $this->logApi('POST', 'create_order', $payload, $result);

            if ($response->successful() && isset($result['success']) && $result['success']) {
                return [
                    'success' => true,
                    'order_id' => $result['order_id'],
                    'message' => $result['message'] ?? 'Order created successfully',
                ];
            }

            // Provide more detailed error messages
            $errorMessage = $result['message'] ?? 'Failed to create order';
            if ($statusCode === 401) {
                $errorMessage = 'Authentication failed. Please check your API token.';
            } elseif ($statusCode === 400) {
                $errorMessage = 'Bad request. Please verify all required fields are provided correctly.';
                if (isset($result['errors'])) {
                    $errorMessage .= ' Errors: ' . json_encode($result['errors']);
                }
            } elseif (isset($result['error'])) {
                $errorMessage = $result['error'];
            }

            return [
                'success' => false,
                'message' => $errorMessage,
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
            // Reload settings to ensure we have the latest values
            $this->reloadSettings();
            
            if (!$this->isConfigured()) {
                return [
                    'success' => false,
                    'message' => 'Gaaubesi API is not configured. Please set API token and URL in Gaaubesi Settings.',
                ];
            }
            
            // Ensure base URL doesn't have trailing slash and endpoint starts with /
            $baseUrl = rtrim($this->baseUrl, '/');
            $endpointPath = config('gaaubesi.endpoints.order_detail');
            $endpoint = $baseUrl . $endpointPath;
            
            $response = $this->getClient()->get($endpoint, [
                'order_id' => $orderId,
            ]);
            
            // Check for HTML responses
            $rawBody = $response->body();
            $isHtml = !empty($rawBody) && (
                stripos($rawBody, '<!DOCTYPE html') !== false ||
                stripos($rawBody, '<html') !== false ||
                stripos($rawBody, '<head') !== false
            );
            
            if ($isHtml && $response->successful()) {
                return [
                    'success' => false,
                    'message' => 'API returned HTML login page. Please check your API token in Gaaubesi Settings.',
                ];
            }
            
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
            // Reload settings to ensure we have the latest values
            $this->reloadSettings();
            
            if (!$this->isConfigured()) {
                return [
                    'success' => false,
                    'message' => 'Gaaubesi API is not configured. Please set API token and URL in Gaaubesi Settings.',
                ];
            }
            
            // Ensure base URL doesn't have trailing slash and endpoint starts with /
            $baseUrl = rtrim($this->baseUrl, '/');
            $endpointPath = config('gaaubesi.endpoints.order_status');
            $endpoint = $baseUrl . $endpointPath;
            
            $response = $this->getClient()->get($endpoint, [
                'order_id' => $orderId,
            ]);
            
            // Check for HTML responses
            $rawBody = $response->body();
            $isHtml = !empty($rawBody) && (
                stripos($rawBody, '<!DOCTYPE html') !== false ||
                stripos($rawBody, '<html') !== false ||
                stripos($rawBody, '<head') !== false
            );
            
            if ($isHtml && $response->successful()) {
                return [
                    'success' => false,
                    'message' => 'API returned HTML login page. Please check your API token in Gaaubesi Settings.',
                ];
            }
            
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
            // Reload settings to ensure we have the latest values
            $this->reloadSettings();
            
            if (!$this->isConfigured()) {
                return [
                    'success' => false,
                    'message' => 'Gaaubesi API is not configured. Please set API token and URL in Gaaubesi Settings.',
                    'comments' => [],
                ];
            }
            
            // Ensure base URL doesn't have trailing slash and endpoint starts with /
            $baseUrl = rtrim($this->baseUrl, '/');
            $endpointPath = config('gaaubesi.endpoints.comment_list');
            $endpoint = $baseUrl . $endpointPath;
            
            $response = $this->getClient()->get($endpoint, [
                'order_id' => $orderId,
            ]);
            
            // Check for HTML responses
            $rawBody = $response->body();
            $isHtml = !empty($rawBody) && (
                stripos($rawBody, '<!DOCTYPE html') !== false ||
                stripos($rawBody, '<html') !== false ||
                stripos($rawBody, '<head') !== false
            );
            
            if ($isHtml && $response->successful()) {
                return [
                    'success' => false,
                    'message' => 'API returned HTML login page. Please check your API token in Gaaubesi Settings.',
                    'comments' => [],
                ];
            }
            
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
                'comments' => [],
            ];

        } catch (Exception $e) {
            Log::error('Gaaubesi Get Order Comments Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'API request failed: ' . $e->getMessage(),
                'comments' => [],
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
            // Reload settings to ensure we have the latest values
            $this->reloadSettings();
            
            if (!$this->isConfigured()) {
                $settings = \App\Models\GaaubesiSetting::getForCurrentTenant();
                $hasToken = $settings && !empty(trim($settings->api_token ?? ''));
                $hasUrl = $settings && !empty(trim($settings->api_url ?? ''));
                
                $message = 'Gaaubesi API is not configured. ';
                if (!$hasToken && !$hasUrl) {
                    $message .= 'Please set both API token and URL in Gaaubesi Settings.';
                } elseif (!$hasToken) {
                    $message .= 'Please set API token in Gaaubesi Settings.';
                } elseif (!$hasUrl) {
                    $message .= 'Please set API URL in Gaaubesi Settings.';
                }
                
                return [
                    'success' => false,
                    'message' => $message,
                ];
            }
            
            // Ensure base URL doesn't have trailing slash and endpoint starts with /
            $baseUrl = rtrim($this->baseUrl, '/');
            $endpointPath = config('gaaubesi.endpoints.comment_create');
            $endpoint = $baseUrl . $endpointPath;
            
            $payload = [
                'order' => (string)$orderId,
                'comments' => $comment,
            ];

            $response = $this->getClient()->post($endpoint, $payload);
            
            // Get raw response body to check for HTML responses
            $rawBody = $response->body();
            $statusCode = $response->status();
            
            // Check if response is HTML (likely a login page or error page)
            $isHtml = !empty($rawBody) && (
                stripos($rawBody, '<!DOCTYPE html') !== false ||
                stripos($rawBody, '<html') !== false ||
                stripos($rawBody, '<head') !== false
            );
            
            if ($isHtml && $response->successful()) {
                return [
                    'success' => false,
                    'message' => 'API returned HTML login page instead of JSON data. This usually means your API token is invalid or expired. Please check your API token in Gaaubesi Settings.',
                    'errors' => ['html_response' => true],
                ];
            }
            
            $result = $response->json();

            $this->logApi('POST', 'comment_create', $payload, $result);

            if ($response->successful() && isset($result['success']) && $result['success']) {
                return [
                    'success' => true,
                    'message' => $result['message'] ?? 'Comment posted successfully',
                ];
            }

            // Provide more detailed error messages
            $errorMessage = $result['message'] ?? 'Failed to post comment';
            if ($statusCode === 401) {
                $errorMessage = 'Authentication failed. Please check your API token.';
            } elseif ($statusCode === 400) {
                $errorMessage = 'Bad request. Please verify the comment and order ID are valid.';
                if (isset($result['errors'])) {
                    $errorMessage .= ' Errors: ' . json_encode($result['errors']);
                }
            } elseif (isset($result['error'])) {
                $errorMessage = $result['error'];
            }

            return [
                'success' => false,
                'message' => $errorMessage,
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
    /**
     * Reload settings from database (useful after settings are updated)
     */
    public function reloadSettings()
    {
        $settings = \App\Models\GaaubesiSetting::getForCurrentTenant();
        
        $apiToken = $settings ? trim($settings->api_token ?? '') : '';
        $apiUrl = $settings ? trim($settings->api_url ?? '') : '';
        
        if (!empty($apiToken) && !empty($apiUrl)) {
            $this->baseUrl = $apiUrl;
            $this->token = $apiToken;
        } else {
            $this->token = $apiToken;
        }
    }

    public function getLocationsData()
    {
        try {
            // Reload settings to ensure we have the latest values
            $this->reloadSettings();
            
            if (!$this->isConfigured()) {
                $settings = \App\Models\GaaubesiSetting::getForCurrentTenant();
                $hasToken = $settings && !empty(trim($settings->api_token ?? ''));
                $hasUrl = $settings && !empty(trim($settings->api_url ?? ''));
                
                $message = 'Gaaubesi API is not configured. ';
                if (!$hasToken && !$hasUrl) {
                    $message .= 'Please set both API token and URL in Gaaubesi Settings.';
                } elseif (!$hasToken) {
                    $message .= 'Please set API token in Gaaubesi Settings.';
                } elseif (!$hasUrl) {
                    $message .= 'Please set API URL in Gaaubesi Settings.';
                }
                
                return [
                    'success' => false,
                    'message' => $message,
                    'locations' => []
                ];
            }
            
            // Ensure base URL doesn't have trailing slash and endpoint starts with /
            $baseUrl = rtrim($this->baseUrl, '/');
            $endpointPath = config('gaaubesi.endpoints.locations_data');
            $endpoint = $baseUrl . $endpointPath;
            
            // Log request details for debugging
            Log::info('Gaaubesi API Request Details', [
                'endpoint' => $endpoint,
                'base_url' => $this->baseUrl,
                'token_length' => strlen(trim($this->token ?? '')),
                'token_preview' => !empty($this->token) ? (substr(trim($this->token), 0, 10) . '...' . substr(trim($this->token), -4)) : 'empty',
            ]);
            
            $response = $this->getClient()->get($endpoint);
            
            // Get raw response body first - try multiple methods
            $rawBody = $response->body();
            $statusCode = $response->status();
            
            // If body() returns empty, try to get content
            if (empty($rawBody)) {
                $rawBody = $response->getBody()->getContents();
            }
            
            // Log response details for debugging
            Log::info('Gaaubesi API Response Details', [
                'endpoint' => $endpoint,
                'status_code' => $statusCode,
                'raw_body_length' => strlen($rawBody),
                'raw_body_preview' => substr($rawBody, 0, 500),
                'headers' => $response->headers(),
                'has_body' => !empty($rawBody),
            ]);
            
            // Check if response is HTML (likely a login page or error page)
            $isHtml = !empty($rawBody) && (
                stripos($rawBody, '<!DOCTYPE html') !== false ||
                stripos($rawBody, '<html') !== false ||
                stripos($rawBody, '<head') !== false
            );
            
            if ($isHtml && $response->successful()) {
                // API returned HTML instead of JSON - likely authentication issue
                $tokenPreview = !empty($this->token) ? (substr(trim($this->token), 0, 10) . '...' . substr(trim($this->token), -4)) : 'empty';
                
                return [
                    'success' => false,
                    'message' => 'API returned HTML login page instead of JSON data. This usually means your API token is invalid, expired, or incorrectly formatted. Please verify: 1) Your API token matches exactly what you see in Gaaubesi dashboard (no extra spaces), 2) The token is active and has not expired, 3) The API URL is correct (testing: https://testing.gaaubesi.com.np/api/v1 or production: https://delivery.gaaubesi.com/api/v1). Token preview: ' . $tokenPreview . ', Endpoint: ' . $endpoint,
                    'locations' => []
                ];
            }
            
            // Try to parse JSON response, handle non-JSON responses
            $result = null;
            try {
                $result = $response->json();
                // If json() returns null but we have a body, try manual decode
                if ($result === null && !empty(trim($rawBody)) && !$isHtml) {
                    $decoded = json_decode($rawBody, true);
                    if (json_last_error() === JSON_ERROR_NONE && $decoded !== null) {
                        $result = $decoded;
                    } elseif (!empty(trim($rawBody))) {
                        // If it's not valid JSON, use raw body as string
                        $result = trim($rawBody);
                    }
                }
            } catch (\Exception $e) {
                // If JSON parsing fails, try to use raw body
                if (!empty(trim($rawBody)) && !$isHtml) {
                    $decoded = json_decode($rawBody, true);
                    if (json_last_error() === JSON_ERROR_NONE && $decoded !== null) {
                        $result = $decoded;
                    } else {
                        $result = trim($rawBody);
                    }
                }
                Log::warning('Gaaubesi API JSON parse error: ' . $e->getMessage() . '. Raw body: ' . substr($rawBody, 0, 200));
            }

            // Log the full response for debugging
            $this->logApi('GET', 'locations_data', [
                'endpoint' => $endpoint,
                'status' => $statusCode,
                'raw_body_length' => strlen($rawBody),
            ], [
                'status_code' => $statusCode,
                'result_type' => gettype($result),
                'result' => is_string($result) ? substr($result, 0, 500) : $result,
            ]);

            if ($response->successful()) {
                // Handle completely empty response
                if (empty($rawBody) || (is_string($rawBody) && trim($rawBody) === '')) {
                    return [
                        'success' => false,
                        'message' => 'API returned empty response body. Status: ' . $statusCode . '. Please check if the endpoint is correct and your API token has access to locations data.',
                        'locations' => []
                    ];
                }
                
                // Handle null result but non-empty body (parsing issue)
                if ($result === null && !empty(trim($rawBody))) {
                    // Try one more time to decode
                    $decoded = json_decode($rawBody, true);
                    if ($decoded !== null && json_last_error() === JSON_ERROR_NONE) {
                        $result = $decoded;
                    } else {
                        return [
                            'success' => false,
                            'message' => 'API returned response but could not parse as JSON. Status: ' . $statusCode . ', Raw body: "' . substr($rawBody, 0, 500) . '"',
                            'locations' => []
                        ];
                    }
                }
                
                // Handle empty result after parsing
                if (empty($result) && !is_numeric($result) && $result !== '0' && $result !== 0) {
                    if (is_array($result) && empty($result)) {
                        return [
                            'success' => false,
                            'message' => 'API returned empty array. This might mean no locations are available or the API token does not have access. Status: ' . $statusCode,
                            'locations' => []
                        ];
                    }
                }
                
                // Convert object to array if needed
                if (is_object($result)) {
                    $result = json_decode(json_encode($result), true);
                }
                
                // Handle different response formats
                // Try to preserve location => rate mapping if available
                $locationsWithRates = [];
                $locations = [];
                
                // Check if result is an array
                if (is_array($result)) {
                    // Check if it's wrapped in a 'data' key
                    if (isset($result['data']) && is_array($result['data'])) {
                        $data = $result['data'];
                        // Check if data is associative array (location => rate)
                        $keys = array_keys($data);
                        if (empty($keys) || !is_numeric($keys[0])) {
                            // It's an associative array - preserve location => rate mapping
                            $locationsWithRates = $data;
                            $locations = array_keys($data);
                        } else {
                            // It's a list, extract location names from objects
                            foreach ($data as $item) {
                                if (is_array($item) || is_object($item)) {
                                    $item = (array)$item;
                                    $location = $item['name'] ?? $item['location'] ?? $item['branch'] ?? null;
                                    $rate = $item['rate'] ?? $item['price'] ?? $item['cost'] ?? null;
                                    if ($location) {
                                        $locations[] = $location;
                                        if ($rate !== null) {
                                            $locationsWithRates[$location] = $rate;
                                        }
                                    }
                                }
                            }
                        }
                    }
                    // Check if it's wrapped in a 'locations' key
                    elseif (isset($result['locations']) && is_array($result['locations'])) {
                        $locations = $result['locations'];
                        // If it's an associative array, preserve it
                        $keys = array_keys($locations);
                        if (!empty($keys) && !is_numeric($keys[0])) {
                            $locationsWithRates = $locations;
                        }
                    }
                    // Check if it's a direct associative array (location => rate)
                    elseif (!empty($result)) {
                        $keys = array_keys($result);
                        // If keys are not numeric, it's an associative array
                        if (empty($keys) || !is_numeric($keys[0])) {
                            // Preserve the full associative array (location => rate)
                            $locationsWithRates = $result;
                            $locations = $keys;
                        }
                        // Otherwise it's a list of objects/arrays
                        elseif (isset($result[0]) && (is_array($result[0]) || is_object($result[0]))) {
                            foreach ($result as $item) {
                                $item = (array)$item;
                                $location = $item['name'] ?? $item['location'] ?? $item['branch'] ?? null;
                                $rate = $item['rate'] ?? $item['price'] ?? $item['cost'] ?? null;
                                if ($location) {
                                    $locations[] = $location;
                                    if ($rate !== null) {
                                        $locationsWithRates[$location] = $rate;
                                    }
                                }
                            }
                        }
                    }
                }
                
                // If we found locations, return success
                // Return the full associative array if available, otherwise just location names
                if (!empty($locations)) {
                    return [
                        'success' => true,
                        'locations' => !empty($locationsWithRates) ? $locationsWithRates : $locations,
                    ];
                }
                
                // Response was successful but format is unexpected
                $errorMessage = 'API returned successful response but in unexpected format. ';
                $errorMessage .= 'Status: ' . $statusCode . ', Raw body length: ' . strlen($rawBody) . ' bytes. ';
                
                if (is_string($result)) {
                    $resultStr = trim($result);
                    if (!empty($resultStr)) {
                        $errorMessage .= 'Response string length: ' . strlen($resultStr) . ' chars. Content: "' . substr($resultStr, 0, 500) . '"';
                    } else {
                        $errorMessage .= 'Response is an empty string.';
                    }
                } elseif (is_array($result) || is_object($result)) {
                    $resultArray = (array)$result;
                    if (!empty($resultArray)) {
                        $errorMessage .= 'Response keys: ' . implode(', ', array_keys($resultArray));
                        $errorMessage .= '. Response preview: ' . json_encode(array_slice($resultArray, 0, 5, true), JSON_PRETTY_PRINT);
                    } else {
                        $errorMessage .= 'Response is an empty array/object.';
                    }
                } elseif ($result === null) {
                    $errorMessage .= 'Response is null.';
                    if (!empty($rawBody)) {
                        $errorMessage .= ' Raw body exists (' . strlen($rawBody) . ' bytes). Content: "' . substr($rawBody, 0, 500) . '"';
                        $errorMessage .= '. Is valid JSON: ' . (json_decode($rawBody) !== null ? 'Yes' : 'No');
                    } else {
                        $errorMessage .= ' Raw body is also empty.';
                    }
                } else {
                    $errorMessage .= 'Response type: ' . gettype($result);
                    if (!empty($rawBody)) {
                        $errorMessage .= '. Raw body: "' . substr($rawBody, 0, 500) . '"';
                    }
                }
                
                // Always include raw body info for debugging
                if (!empty($rawBody)) {
                    $errorMessage .= ' [Check application logs for full response details]';
                }
                
                return [
                    'success' => false,
                    'message' => $errorMessage,
                    'locations' => []
                ];
            }

            // Provide more detailed error messages for non-200 responses
            $errorMessage = 'Failed to fetch locations data';
            $statusCode = $response->status();
            
            if ($statusCode === 401) {
                $errorMessage = 'Authentication failed. Please check your API token.';
            } elseif ($statusCode === 404) {
                $errorMessage = 'API endpoint not found. Please check your API URL. The endpoint should be: ' . $endpoint;
            } elseif ($statusCode === 400) {
                $errorMessage = 'Bad request. Please verify your API URL and token are correct.';
                if (isset($result['message'])) {
                    $errorMessage = $result['message'];
                } elseif (isset($result['error'])) {
                    $errorMessage = $result['error'];
                } elseif (is_string($result)) {
                    $errorMessage = $result;
                }
            } elseif (isset($result['message'])) {
                $errorMessage = $result['message'];
            } elseif (isset($result['error'])) {
                $errorMessage = $result['error'];
            } elseif (is_string($result)) {
                $errorMessage = $result;
            } else {
                $errorMessage = 'API request failed with status ' . $statusCode . '. Please check your API credentials.';
            }

            return [
                'success' => false,
                'message' => $errorMessage,
                'locations' => []
            ];

        } catch (Exception $e) {
            Log::error('Gaaubesi Get Locations Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'API request failed: ' . $e->getMessage(),
                'locations' => []
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
                'message' => $response['success'] 
                    ? 'Connection successful! Found ' . count($response['locations'] ?? []) . ' location(s).'
                    : ($response['message'] ?? 'Connection failed'),
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Connection test failed: ' . $e->getMessage(),
            ];
        }
    }
}


