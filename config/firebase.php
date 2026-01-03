<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Firebase Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for Firebase services including SMS/Push notifications
    |
    */

    'project_id' => env('FIREBASE_PROJECT_ID', ''),
    'api_key' => env('FIREBASE_API_KEY', ''),
    'auth_domain' => env('FIREBASE_AUTH_DOMAIN', ''),
    'database_url' => env('FIREBASE_DATABASE_URL', ''),
    'storage_bucket' => env('FIREBASE_STORAGE_BUCKET', ''),
    'messaging_sender_id' => env('FIREBASE_MESSAGING_SENDER_ID', ''),
    'app_id' => env('FIREBASE_APP_ID', ''),
    
    // Server Key for Cloud Messaging
    'server_key' => env('FIREBASE_SERVER_KEY', ''),
    
    // SMS Provider Configuration
    'sms_provider' => env('SMS_PROVIDER', 'firebase'), // firebase, twilio, nexmo, etc.
    
    // Twilio Configuration (Alternative SMS Provider)
    'twilio' => [
        'sid' => env('TWILIO_SID', ''),
        'token' => env('TWILIO_TOKEN', ''),
        'from' => env('TWILIO_FROM', ''),
    ],
];
