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
    'environment' => env('GAAUBESI_ENVIRONMENT', 'production'),

    // Production API Settings
    'production' => [
        'base_url' => 'https://delivery.gaaubesi.com/api/v1',
        'token' => env('GAAUBESI_TOKEN', 'a321a34a4f891a94fb45b56f3b8b0bf95e57d11c'),
    ],

    // Testing API Settings
    'testing' => [
        'base_url' => 'https://testing.gaaubesi.com.np/api/v1',
        'token' => env('GAAUBESI_TEST_TOKEN', '566f3b464f83e1bf2f2a48f7f4d7a3ed209f0f79'),
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

