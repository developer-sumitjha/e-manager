<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Gaaubesi Logistics API Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for Gaaubesi Logistics API integration
    |
    */

    // API Environment (production or testing)
    // Note: Token and base URL should be configured in the Gaaubesi Settings page
    // These are only used as fallback defaults if vendor settings are not available
    'environment' => env('GAAUBESI_ENVIRONMENT', 'production'),

    // Production API Settings (Fallback defaults only)
    'production' => [
        'base_url' => env('GAAUBESI_PRODUCTION_URL', 'https://delivery.gaaubesi.com/api/v1'),
        // Token removed - must be set in vendor settings page
    ],

    // Testing API Settings (Fallback defaults only)
    'testing' => [
        'base_url' => env('GAAUBESI_TESTING_URL', 'https://testing.gaaubesi.com.np/api/v1'),
        // Token removed - must be set in vendor settings page
        'username' => 'demo_vendor',
    ],

    // API Endpoints
    'endpoints' => [
        'create_order' => '/order/create/',
        'order_detail' => '/order/detail/',
        'order_status' => '/order/status/',
        'comment_list' => '/order/comment/list/',
        'comment_create' => '/order/comment/create/',
        'locations_data' => '/locations_data/',
        'print_label' => '/print_labels/',
        'print_label_binary' => '/print_labels_binary/',
    ],

    // Default Branch
    'default_branch' => env('GAAUBESI_DEFAULT_BRANCH', 'HEAD OFFICE'),

    // Contact Information
    'contact' => [
        'email' => 'info@gaaubesi.com',
        'phone' => '01-5907736',
    ],

    // Delivery Options
    'package_access_options' => [
        "Can't Open",
        "Can Open",
    ],

    'delivery_type_options' => [
        'Pickup',
        'Drop Off',
    ],

    // Request Timeout (in seconds)
    'timeout' => 30,

    // Enable Logging
    'enable_logging' => env('GAAUBESI_LOGGING', true),
];

