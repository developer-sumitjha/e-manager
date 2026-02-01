<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class PathaoService
{
    protected $baseUrl;
    protected $accessToken;
    protected $refreshToken;
    protected $tokenExpiresAt;
    protected $clientId;
    protected $clientSecret;
    protected $username;
    protected $password;
    protected $storeId;
    protected $timeout;
    protected $enableLogging;

    public function __construct()
    {
        // Load vendor-specific settings from database
        $settings = \App\Models\PathaoSetting::getForCurrentTenant();
        
        if ($settings) {
            $this->baseUrl = trim($settings->api_url ?? 'https://courier-api-sandbox.pathao.com');
            $this->clientId = trim($settings->client_id ?? '');
            $this->clientSecret = trim($settings->client_secret ?? '');
            $this->username = trim($settings->username ?? '');
            $this->password = trim($settings->password ?? '');
            $this->storeId = $settings->store_id;
            $this->accessToken = trim($settings->access_token ?? '');
            $this->refreshToken = trim($settings->refresh_token ?? '');
            $this->tokenExpiresAt = $settings->token_expires_at;
        } else {
            $this->baseUrl = 'https://courier-api-sandbox.pathao.com';
        }
        
        $this->timeout = config('pathao.timeout', 30);
        $this->enableLogging = config('pathao.enable_logging', true);
    }

    /**
     * Reload settings from database to ensure we have latest values
     */
    protected function reloadSettings()
    {
        $settings = \App\Models\PathaoSetting::getForCurrentTenant();
        
        if ($settings) {
            $this->baseUrl = trim($settings->api_url ?? 'https://courier-api-sandbox.pathao.com');
            $this->clientId = trim($settings->client_id ?? '');
            $this->clientSecret = trim($settings->client_secret ?? '');
            $this->username = trim($settings->username ?? '');
            $this->password = trim($settings->password ?? '');
            $this->storeId = $settings->store_id;
            $this->accessToken = trim($settings->access_token ?? '');
            $this->refreshToken = trim($settings->refresh_token ?? '');
            $this->tokenExpiresAt = $settings->token_expires_at;
        }
    }

    /**
     * Check if service is properly configured (has credentials)
     * Note: This only checks for credentials, not for a valid token
     * 
     * @return bool
     */
    public function isConfigured()
    {
        // Reload settings to get latest values
        $this->reloadSettings();
        
        // Only check if we have all required credentials
        $hasCredentials = !empty($this->clientId) && !empty($this->clientSecret) && 
                         !empty($this->username) && !empty($this->password);
        $hasUrl = !empty($this->baseUrl);
        
        return $hasCredentials && $hasUrl;
    }

    /**
     * Get or refresh access token
     * 
     * @return string|null
     */
    public function getAccessToken()
    {
        // Reload settings to get latest values
        $this->reloadSettings();
        
        $settings = \App\Models\PathaoSetting::getForCurrentTenant();
        
        if (!$settings) {
            Log::error('Pathao Get Access Token: No settings found for current tenant');
            return null;
        }
        
        // Check if token is expired or will expire soon
        if ($settings->isTokenExpired() || empty($this->accessToken)) {
            Log::info('Pathao Get Access Token: Token expired or missing, attempting to get new token', [
                'is_expired' => $settings->isTokenExpired(),
                'has_token' => !empty($this->accessToken),
                'has_refresh_token' => !empty($this->refreshToken),
            ]);
            
            // Try to refresh token first if we have refresh token
            if (!empty($this->refreshToken)) {
                Log::info('Pathao Get Access Token: Attempting to refresh token');
                $refreshed = $this->refreshAccessToken();
                if ($refreshed) {
                    Log::info('Pathao Get Access Token: Token refreshed successfully');
                    return $this->accessToken;
                }
                Log::warning('Pathao Get Access Token: Token refresh failed, will try to issue new token');
            }
            
            // Issue new token
            Log::info('Pathao Get Access Token: Attempting to issue new token');
            $issued = $this->issueAccessToken();
            if ($issued) {
                Log::info('Pathao Get Access Token: New token issued successfully');
                return $this->accessToken;
            }
            
            Log::error('Pathao Get Access Token: Failed to issue new token');
            return null;
        }
        
        Log::debug('Pathao Get Access Token: Using existing valid token');
        return $this->accessToken;
    }

    /**
     * Issue a new access token
     * 
     * @return bool
     */
    public function issueAccessToken()
    {
        try {
            // Reload settings to get latest values
            $this->reloadSettings();
            
            $settings = \App\Models\PathaoSetting::getForCurrentTenant();
            
            if (!$settings || empty($this->clientId) || empty($this->clientSecret) || 
                empty($this->username) || empty($this->password)) {
                Log::error('Pathao Issue Token: Missing credentials', [
                    'has_settings' => $settings !== null,
                    'has_client_id' => !empty($this->clientId),
                    'has_client_secret' => !empty($this->clientSecret),
                    'has_username' => !empty($this->username),
                    'has_password' => !empty($this->password),
                ]);
                return false;
            }
            
            $endpoint = rtrim($this->baseUrl, '/') . '/aladdin/api/v1/issue-token';
            
            $payload = [
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'username' => $this->username,
                'password' => $this->password,
                'grant_type' => 'password',
            ];
            
            Log::info('Pathao Issue Token: Attempting to get access token', [
                'endpoint' => $endpoint,
                'client_id' => substr($this->clientId, 0, 5) . '...',
                'username' => $this->username,
            ]);
            
            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                ])
                ->post($endpoint, $payload);
            
            $statusCode = $response->status();
            $rawBody = $response->body();
            
            // Try to parse JSON response
            $result = null;
            try {
                $result = $response->json();
            } catch (\Exception $e) {
                Log::error('Pathao Issue Token: JSON parse error', [
                    'error' => $e->getMessage(),
                    'raw_body' => substr($rawBody, 0, 500),
                ]);
            }
            
            // If JSON parsing failed, try manual decode
            if ($result === null && !empty($rawBody)) {
                $result = json_decode($rawBody, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    $result = ['raw_body' => substr($rawBody, 0, 500)];
                }
            }
            
            $this->logApi('POST', 'issue-token', [
                'endpoint' => $endpoint,
                'client_id' => substr($this->clientId, 0, 5) . '...',
            ], $result);
            
            if ($response->successful() && isset($result['access_token'])) {
                $this->accessToken = $result['access_token'];
                $this->refreshToken = $result['refresh_token'] ?? null;
                
                // Calculate expiration time (expires_in is in seconds)
                $expiresIn = $result['expires_in'] ?? 432000; // Default 5 days (or use actual value from API)
                $this->tokenExpiresAt = now()->addSeconds($expiresIn);
                
                // Save to database
                $settings->update([
                    'access_token' => $this->accessToken,
                    'refresh_token' => $this->refreshToken,
                    'token_expires_at' => $this->tokenExpiresAt,
                ]);
                
                Log::info('Pathao Access Token Issued Successfully', [
                    'expires_in' => $expiresIn,
                    'expires_at' => $this->tokenExpiresAt->toDateTimeString(),
                ]);
                
                return true;
            }
            
            // Log detailed error information
            $errorMessage = 'Unknown error';
            if (is_array($result)) {
                if (isset($result['message'])) {
                    $errorMessage = $result['message'];
                } elseif (isset($result['error'])) {
                    $errorMessage = $result['error'];
                } elseif (isset($result['errors'])) {
                    $errorMessage = is_array($result['errors']) ? json_encode($result['errors']) : $result['errors'];
                }
            } elseif (!empty($rawBody)) {
                $errorMessage = 'Non-JSON response: ' . substr($rawBody, 0, 200);
            }
            
            Log::error('Pathao Issue Token Failed', [
                'status_code' => $statusCode,
                'error_message' => $errorMessage,
                'response' => $result,
                'raw_body_preview' => substr($rawBody, 0, 500),
                'endpoint' => $endpoint,
                'has_client_id' => !empty($this->clientId),
                'has_client_secret' => !empty($this->clientSecret),
                'has_username' => !empty($this->username),
                'has_password' => !empty($this->password),
            ]);
            
            return false;
            
        } catch (Exception $e) {
            Log::error('Pathao Issue Token Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Refresh access token using refresh token
     * 
     * @return bool
     */
    public function refreshAccessToken()
    {
        try {
            $settings = \App\Models\PathaoSetting::getForCurrentTenant();
            
            if (!$settings || empty($this->refreshToken) || 
                empty($this->clientId) || empty($this->clientSecret)) {
                return false;
            }
            
            $endpoint = rtrim($this->baseUrl, '/') . '/aladdin/api/v1/issue-token';
            
            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                ])
                ->post($endpoint, [
                    'client_id' => $this->clientId,
                    'client_secret' => $this->clientSecret,
                    'grant_type' => 'refresh_token',
                    'refresh_token' => $this->refreshToken,
                ]);
            
            $result = $response->json();
            $this->logApi('POST', 'issue-token (refresh)', null, $result);
            
            if ($response->successful() && isset($result['access_token'])) {
                $this->accessToken = $result['access_token'];
                $this->refreshToken = $result['refresh_token'] ?? $this->refreshToken;
                
                // Calculate expiration time
                $expiresIn = $result['expires_in'] ?? 432000;
                $this->tokenExpiresAt = now()->addSeconds($expiresIn);
                
                // Save to database
                $settings->update([
                    'access_token' => $this->accessToken,
                    'refresh_token' => $this->refreshToken,
                    'token_expires_at' => $this->tokenExpiresAt,
                ]);
                
                return true;
            }
            
            Log::error('Pathao Refresh Token Failed', ['response' => $result]);
            return false;
            
        } catch (Exception $e) {
            Log::error('Pathao Refresh Token Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get HTTP client with authorization headers
     */
    protected function getClient()
    {
        $token = $this->getAccessToken();
        
        if (!$token) {
            throw new Exception('Pathao API is not configured or access token could not be obtained. Please check your credentials in Pathao Settings.');
        }
        
        return Http::timeout($this->timeout)
            ->withHeaders([
                'Authorization' => "Bearer {$token}",
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
            Log::channel('daily')->info("Pathao API {$method}: {$endpoint}", [
                'request' => $data,
                'response' => $response,
            ]);
        }
    }

    /**
     * Get list of cities
     * 
     * @return array
     */
    public function getCities()
    {
        try {
            // Check configuration first (credentials only)
            if (!$this->isConfigured()) {
                $settings = \App\Models\PathaoSetting::getForCurrentTenant();
                $hasClientId = $settings && !empty(trim($settings->client_id ?? ''));
                $hasClientSecret = $settings && !empty(trim($settings->client_secret ?? ''));
                $hasUsername = $settings && !empty(trim($settings->username ?? ''));
                $hasPassword = $settings && !empty(trim($settings->password ?? ''));
                $hasUrl = $settings && !empty(trim($settings->api_url ?? ''));
                
                $message = 'Pathao API is not configured. ';
                if (!$hasClientId && !$hasClientSecret && !$hasUsername && !$hasPassword && !$hasUrl) {
                    $message .= 'Please set Client ID, Client Secret, Username, Password, and API URL in Pathao Settings.';
                } elseif (!$hasClientId) {
                    $message .= 'Please set Client ID in Pathao Settings.';
                } elseif (!$hasClientSecret) {
                    $message .= 'Please set Client Secret in Pathao Settings.';
                } elseif (!$hasUsername) {
                    $message .= 'Please set Username in Pathao Settings.';
                } elseif (!$hasPassword) {
                    $message .= 'Please set Password in Pathao Settings.';
                } elseif (!$hasUrl) {
                    $message .= 'Please set API URL in Pathao Settings.';
                }
                
                return [
                    'success' => false,
                    'message' => $message,
                    'data' => []
                ];
            }
            
            // Try to get access token (will issue if needed)
            $token = $this->getAccessToken();
            if (!$token) {
                return [
                    'success' => false,
                    'message' => 'Failed to obtain access token. Please verify your credentials are correct in Pathao Settings.',
                    'data' => []
                ];
            }
            
            $endpoint = rtrim($this->baseUrl, '/') . '/aladdin/api/v1/city-list';
            
            $response = $this->getClient()->get($endpoint);
            $result = $response->json();
            
            $this->logApi('GET', 'city-list', null, $result);
            
            if ($response->successful() && isset($result['type']) && $result['type'] === 'success') {
                return [
                    'success' => true,
                    'data' => $result['data']['data'] ?? [],
                ];
            }
            
            return [
                'success' => false,
                'message' => $result['message'] ?? 'Failed to fetch cities',
                'data' => []
            ];
            
        } catch (Exception $e) {
            Log::error('Pathao Get Cities Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'API request failed: ' . $e->getMessage(),
                'data' => []
            ];
        }
    }

    /**
     * Get zones for a city
     * 
     * @param int $cityId
     * @return array
     */
    public function getZones($cityId)
    {
        try {
            // Try to get access token (will issue if needed)
            $token = $this->getAccessToken();
            if (!$token) {
                return [
                    'success' => false,
                    'message' => 'Failed to obtain access token. Please verify your credentials are correct in Pathao Settings.',
                    'data' => []
                ];
            }
            
            if (!$this->isConfigured()) {
                return [
                    'success' => false,
                    'message' => 'Pathao API is not configured.',
                    'data' => []
                ];
            }
            
            // Use GET request with city_id in URL path
            $endpoint = rtrim($this->baseUrl, '/') . '/aladdin/api/v1/cities/' . $cityId . '/zone-list';
            
            $response = $this->getClient()->get($endpoint);
            
            // Get raw response body first
            $rawBody = $response->body();
            $statusCode = $response->status();
            
            // Try to parse JSON response
            $result = null;
            try {
                $result = $response->json();
            } catch (\Exception $e) {
                Log::error('Pathao Get Zones: JSON parse error', [
                    'error' => $e->getMessage(),
                    'raw_body' => substr($rawBody, 0, 500),
                ]);
            }
            
            // If JSON parsing failed, try manual decode
            if ($result === null && !empty($rawBody)) {
                $result = json_decode($rawBody, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    $result = ['raw_body' => substr($rawBody, 0, 500)];
                }
            }
            
            $this->logApi('GET', 'zone-list', ['city_id' => $cityId, 'endpoint' => $endpoint], $result);
            
            if ($response->successful() && isset($result['type']) && $result['type'] === 'success') {
                return [
                    'success' => true,
                    'data' => $result['data']['data'] ?? $result['data'] ?? [],
                ];
            }
            
            $errorMessage = $result['message'] ?? 'Failed to fetch zones';
            Log::error('Pathao Get Zones Failed', [
                'status_code' => $statusCode,
                'city_id' => $cityId,
                'endpoint' => $endpoint,
                'response' => $result,
            ]);
            
            return [
                'success' => false,
                'message' => $errorMessage,
                'data' => []
            ];
            
        } catch (Exception $e) {
            Log::error('Pathao Get Zones Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'API request failed: ' . $e->getMessage(),
                'data' => []
            ];
        }
    }

    /**
     * Get areas for a zone
     * 
     * @param int $zoneId
     * @return array
     */
    public function getAreas($zoneId)
    {
        try {
            // Try to get access token (will issue if needed)
            $token = $this->getAccessToken();
            if (!$token) {
                return [
                    'success' => false,
                    'message' => 'Failed to obtain access token. Please verify your credentials are correct in Pathao Settings.',
                    'data' => []
                ];
            }
            
            if (!$this->isConfigured()) {
                return [
                    'success' => false,
                    'message' => 'Pathao API is not configured.',
                    'data' => []
                ];
            }
            
            // Try GET first (following the pattern from zones), fallback to POST if needed
            $endpoint = rtrim($this->baseUrl, '/') . '/aladdin/api/v1/zones/' . $zoneId . '/area-list';
            
            $response = $this->getClient()->get($endpoint);
            
            // If GET fails with 404, try POST method (legacy endpoint)
            if ($response->status() === 404) {
                $endpoint = rtrim($this->baseUrl, '/') . '/aladdin/api/v1/area-list';
                $response = $this->getClient()->post($endpoint, [
                    'zone_id' => $zoneId,
                ]);
            }
            
            // Get raw response body first
            $rawBody = $response->body();
            $statusCode = $response->status();
            
            // Try to parse JSON response
            $result = null;
            try {
                $result = $response->json();
            } catch (\Exception $e) {
                Log::error('Pathao Get Areas: JSON parse error', [
                    'error' => $e->getMessage(),
                    'raw_body' => substr($rawBody, 0, 500),
                ]);
            }
            
            // If JSON parsing failed, try manual decode
            if ($result === null && !empty($rawBody)) {
                $result = json_decode($rawBody, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    $result = ['raw_body' => substr($rawBody, 0, 500)];
                }
            }
            
            $this->logApi('GET', 'area-list', ['zone_id' => $zoneId, 'endpoint' => $endpoint], $result);
            
            if ($response->successful() && isset($result['type']) && $result['type'] === 'success') {
                return [
                    'success' => true,
                    'data' => $result['data']['data'] ?? $result['data'] ?? [],
                ];
            }
            
            $errorMessage = $result['message'] ?? 'Failed to fetch areas';
            Log::error('Pathao Get Areas Failed', [
                'status_code' => $statusCode,
                'zone_id' => $zoneId,
                'endpoint' => $endpoint,
                'response' => $result,
            ]);
            
            return [
                'success' => false,
                'message' => $errorMessage,
                'data' => []
            ];
            
        } catch (Exception $e) {
            Log::error('Pathao Get Areas Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'API request failed: ' . $e->getMessage(),
                'data' => []
            ];
        }
    }

    /**
     * Get merchant stores
     * 
     * @return array
     */
    public function getStores()
    {
        try {
            $this->getAccessToken();
            
            if (!$this->isConfigured()) {
                return [
                    'success' => false,
                    'message' => 'Pathao API is not configured.',
                    'data' => []
                ];
            }
            
            $endpoint = rtrim($this->baseUrl, '/') . '/aladdin/api/v1/stores';
            
            $response = $this->getClient()->get($endpoint);
            $result = $response->json();
            
            $this->logApi('GET', 'stores', null, $result);
            
            if ($response->successful() && isset($result['type']) && $result['type'] === 'success') {
                return [
                    'success' => true,
                    'data' => $result['data']['data'] ?? [],
                ];
            }
            
            return [
                'success' => false,
                'message' => $result['message'] ?? 'Failed to fetch stores',
                'data' => []
            ];
            
        } catch (Exception $e) {
            Log::error('Pathao Get Stores Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'API request failed: ' . $e->getMessage(),
                'data' => []
            ];
        }
    }

    /**
     * Calculate price for an order
     * 
     * @param array $data
     * @return array
     */
    public function calculatePrice($data)
    {
        try {
            $this->getAccessToken();
            
            if (!$this->isConfigured()) {
                return [
                    'success' => false,
                    'message' => 'Pathao API is not configured.',
                ];
            }
            
            $settings = \App\Models\PathaoSetting::getForCurrentTenant();
            $storeId = $data['store_id'] ?? $this->storeId ?? $settings->store_id;
            
            if (!$storeId) {
                return [
                    'success' => false,
                    'message' => 'Store ID is required. Please configure your store in Pathao Settings.',
                ];
            }
            
            $endpoint = rtrim($this->baseUrl, '/') . '/aladdin/api/v1/merchant/price-plan';
            
            $payload = [
                'store_id' => $storeId,
                'item_type' => $data['item_type'] ?? 2,
                'delivery_type' => $data['delivery_type'] ?? 48,
                'item_weight' => $data['item_weight'] ?? 0.5,
                'recipient_city' => $data['recipient_city'],
                'recipient_zone' => $data['recipient_zone'],
            ];
            
            $response = $this->getClient()->post($endpoint, $payload);
            $result = $response->json();
            
            $this->logApi('POST', 'price-plan', $payload, $result);
            
            if ($response->successful() && isset($result['type']) && $result['type'] === 'success') {
                return [
                    'success' => true,
                    'data' => $result['data'] ?? [],
                ];
            }
            
            return [
                'success' => false,
                'message' => $result['message'] ?? 'Failed to calculate price',
            ];
            
        } catch (Exception $e) {
            Log::error('Pathao Calculate Price Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'API request failed: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Create a new order
     * 
     * @param array $orderData
     * @return array
     */
    public function createOrder(array $orderData)
    {
        try {
            $this->getAccessToken();
            
            if (!$this->isConfigured()) {
                $settings = \App\Models\PathaoSetting::getForCurrentTenant();
                $hasCredentials = $settings && !empty(trim($settings->client_id ?? '')) && 
                                 !empty(trim($settings->client_secret ?? '')) &&
                                 !empty(trim($settings->username ?? '')) && 
                                 !empty(trim($settings->password ?? ''));
                
                $message = 'Pathao API is not configured. ';
                if (!$hasCredentials) {
                    $message .= 'Please set Client ID, Client Secret, Username, and Password in Pathao Settings.';
                } else {
                    $message .= 'Please check your credentials and try again.';
                }
                
                return [
                    'success' => false,
                    'message' => $message,
                ];
            }
            
            $settings = \App\Models\PathaoSetting::getForCurrentTenant();
            $storeId = $orderData['store_id'] ?? $this->storeId ?? $settings->store_id;
            
            if (!$storeId) {
                return [
                    'success' => false,
                    'message' => 'Store ID is required. Please configure your store in Pathao Settings.',
                ];
            }
            
            $endpoint = rtrim($this->baseUrl, '/') . '/aladdin/api/v1/orders';
            
            $payload = [
                'store_id' => (int)$storeId,
                'merchant_order_id' => $orderData['merchant_order_id'] ?? null,
                'recipient_name' => $orderData['recipient_name'],
                'recipient_phone' => $orderData['recipient_phone'],
                'recipient_address' => $orderData['recipient_address'],
                'recipient_city' => (int)$orderData['recipient_city'],
                'recipient_zone' => (int)$orderData['recipient_zone'],
                'recipient_area' => isset($orderData['recipient_area']) ? (int)$orderData['recipient_area'] : null,
                'delivery_type' => (int)($orderData['delivery_type'] ?? 48),
                'item_type' => (int)($orderData['item_type'] ?? 2),
                'item_weight' => (string)($orderData['item_weight'] ?? '0.5'), // Must be string
                'item_quantity' => (int)($orderData['item_quantity'] ?? 1), // Must be integer
                'item_description' => $orderData['item_description'] ?? '',
                'amount_to_collect' => (float)($orderData['amount_to_collect'] ?? 0),
                'special_instruction' => $orderData['special_instruction'] ?? '',
            ];
            
            $response = $this->getClient()->post($endpoint, $payload);
            
            // Check for HTML responses (authentication issues)
            $rawBody = $response->body();
            $isHtml = !empty($rawBody) && (
                stripos($rawBody, '<!DOCTYPE html') !== false ||
                stripos($rawBody, '<html') !== false ||
                stripos($rawBody, '<head') !== false
            );
            
            if ($isHtml && $response->successful()) {
                return [
                    'success' => false,
                    'message' => 'API returned HTML login page. Please check your credentials in Pathao Settings.',
                ];
            }
            
            $result = $response->json();
            $this->logApi('POST', 'create_order', $payload, $result);
            
            if ($response->successful() && isset($result['type']) && $result['type'] === 'success') {
                $data = $result['data'] ?? [];
                return [
                    'success' => true,
                    'order_id' => $data['consignment_id'] ?? $data['id'] ?? null,
                    'consignment_id' => $data['consignment_id'] ?? null,
                    'tracking_id' => $data['tracking_id'] ?? null,
                    'message' => $result['message'] ?? 'Order created successfully',
                    'data' => $data,
                ];
            }
            
            $errorMessage = $result['message'] ?? 'Failed to create order';
            if (isset($result['errors'])) {
                $errorMessage .= '. Errors: ' . json_encode($result['errors']);
            }
            
            return [
                'success' => false,
                'message' => $errorMessage,
                'errors' => $result['errors'] ?? null,
            ];
            
        } catch (Exception $e) {
            Log::error('Pathao Create Order Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'API request failed: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Get order details
     * 
     * @param string $consignmentId
     * @return array
     */
    public function getOrderDetail($consignmentId)
    {
        try {
            $this->getAccessToken();
            
            if (!$this->isConfigured()) {
                return [
                    'success' => false,
                    'message' => 'Pathao API is not configured.',
                ];
            }
            
            $endpoint = rtrim($this->baseUrl, '/') . '/aladdin/api/v1/orders/' . $consignmentId;
            
            $response = $this->getClient()->get($endpoint);
            $result = $response->json();
            
            $this->logApi('GET', 'order_detail', ['consignment_id' => $consignmentId], $result);
            
            if ($response->successful() && isset($result['type']) && $result['type'] === 'success') {
                return [
                    'success' => true,
                    'data' => $result['data'] ?? [],
                ];
            }
            
            return [
                'success' => false,
                'message' => $result['message'] ?? 'Failed to fetch order details',
            ];
            
        } catch (Exception $e) {
            Log::error('Pathao Get Order Detail Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'API request failed: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Test API connection
     */
    public function testConnection()
    {
        try {
            // Reload settings first
            $this->reloadSettings();
            
            // Check if credentials are configured
            if (!$this->isConfigured()) {
                $settings = \App\Models\PathaoSetting::getForCurrentTenant();
                $hasClientId = $settings && !empty(trim($settings->client_id ?? ''));
                $hasClientSecret = $settings && !empty(trim($settings->client_secret ?? ''));
                $hasUsername = $settings && !empty(trim($settings->username ?? ''));
                $hasPassword = $settings && !empty(trim($settings->password ?? ''));
                $hasUrl = $settings && !empty(trim($settings->api_url ?? ''));
                
                $message = 'Pathao API is not configured. ';
                if (!$hasClientId && !$hasClientSecret && !$hasUsername && !$hasPassword && !$hasUrl) {
                    $message .= 'Please set Client ID, Client Secret, Username, Password, and API URL in Pathao Settings.';
                } elseif (!$hasClientId) {
                    $message .= 'Please set Client ID in Pathao Settings.';
                } elseif (!$hasClientSecret) {
                    $message .= 'Please set Client Secret in Pathao Settings.';
                } elseif (!$hasUsername) {
                    $message .= 'Please set Username in Pathao Settings.';
                } elseif (!$hasPassword) {
                    $message .= 'Please set Password in Pathao Settings.';
                } elseif (!$hasUrl) {
                    $message .= 'Please set API URL in Pathao Settings.';
                }
                
                return [
                    'success' => false,
                    'message' => $message,
                ];
            }
            
            // Try to get access token first
            Log::info('Pathao Test Connection: Attempting to get access token', [
                'base_url' => $this->baseUrl,
                'has_client_id' => !empty($this->clientId),
                'has_client_secret' => !empty($this->clientSecret),
                'has_username' => !empty($this->username),
                'has_password' => !empty($this->password),
            ]);
            
            $token = $this->getAccessToken();
            if (!$token) {
                // Check logs for detailed error - we'll provide a helpful message
                Log::error('Pathao Test Connection: Failed to obtain access token', [
                    'base_url' => $this->baseUrl,
                    'client_id_length' => strlen($this->clientId ?? ''),
                    'client_secret_length' => strlen($this->clientSecret ?? ''),
                    'username' => $this->username ?? 'empty',
                    'has_password' => !empty($this->password),
                ]);
                
                // Try to issue token directly to get error details
                $issued = $this->issueAccessToken();
                if (!$issued) {
                    // Check recent logs for the actual error
                    return [
                        'success' => false,
                        'message' => 'Failed to obtain access token. Please check your credentials and verify: 1) Client ID is correct, 2) Client Secret is correct, 3) Username (email) is correct, 4) Password is correct, 5) API URL is correct. Check Laravel logs for detailed error message.',
                    ];
                }
                $token = $this->accessToken;
            }
            
            Log::info('Pathao Test Connection: Access token obtained, testing API call');
            
            // Try to get cities as a test
            $response = $this->getCities();
            
            if ($response['success']) {
                Log::info('Pathao Test Connection: Success');
                return [
                    'success' => true,
                    'message' => 'Connection successful! Access token obtained and API is accessible.',
                ];
            } else {
                Log::error('Pathao Test Connection: API call failed', ['response' => $response]);
                return [
                    'success' => false,
                    'message' => $response['message'] ?? 'Connection test failed. API call was unsuccessful.',
                ];
            }
            
        } catch (Exception $e) {
            Log::error('Pathao Test Connection: Exception', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return [
                'success' => false,
                'message' => 'Connection test failed: ' . $e->getMessage(),
            ];
        }
    }
}
