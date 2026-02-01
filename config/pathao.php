<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Pathao Courier API Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for Pathao Courier API integration
    |
    */

    // API Environment (production or sandbox)
    // Note: Credentials should be configured in the Pathao Settings page
    'environment' => env('PATHAO_ENVIRONMENT', 'sandbox'),

    // Sandbox API Settings (Fallback defaults only)
    'sandbox' => [
        'base_url' => env('PATHAO_SANDBOX_URL', 'https://courier-api-sandbox.pathao.com'),
    ],

    // Production API Settings (Fallback defaults only)
    'production' => [
        'base_url' => env('PATHAO_PRODUCTION_URL', 'https://api-hermes.pathao.com'),
    ],

    // API Endpoints
    'endpoints' => [
        'issue_token' => '/aladdin/api/v1/issue-token',
        'create_order' => '/aladdin/api/v1/orders',
        'bulk_order' => '/aladdin/api/v1/orders/bulk',
        'order_detail' => '/aladdin/api/v1/orders',
        'city_list' => '/aladdin/api/v1/city-list',
        'zone_list' => '/aladdin/api/v1/zone-list',
        'area_list' => '/aladdin/api/v1/area-list',
        'price_plan' => '/aladdin/api/v1/merchant/price-plan',
        'stores' => '/aladdin/api/v1/stores',
    ],

    // Item Types
    'item_types' => [
        1 => 'Document',
        2 => 'Parcel',
    ],

    // Delivery Types
    'delivery_types' => [
        48 => 'Normal Delivery',
        12 => 'On Demand Delivery',
    ],

    // Default Settings
    'default_item_type' => 2, // Parcel
    'default_delivery_type' => 48, // Normal Delivery
    'default_item_weight' => 0.5, // 0.5 kg

    // Request Timeout (in seconds)
    'timeout' => 30,

    // Enable Logging
    'enable_logging' => env('PATHAO_LOGGING', true),
];
