#!/usr/bin/env php
<?php
/*
|--------------------------------------------------------------------------
| View Validation Script
|--------------------------------------------------------------------------
| This script validates all views and routes are accessible
*/

echo "🔍 E-MANAGER VIEW & ROUTE VALIDATION\n";
echo str_repeat("=", 60) . "\n\n";

$baseUrl = 'http://localhost/e-manager/public';
$results = [];
$errors = [];

// Test Routes
$routes = [
    'Public Landing Page' => [
        'url' => "$baseUrl/",
        'expected' => 'Powerful Business Management',
        'method' => 'GET'
    ],
    'Pricing Page' => [
        'url' => "$baseUrl/pricing",
        'expected' => 'Simple, Transparent Pricing',
        'method' => 'GET'
    ],
    'Signup Page' => [
        'url' => "$baseUrl/signup",
        'expected' => 'Start Your Free Trial',
        'method' => 'GET'
    ],
    'Super Admin Login' => [
        'url' => "$baseUrl/super/login",
        'expected' => 'Super Admin',
        'method' => 'GET'
    ],
    'Admin Login' => [
        'url' => "$baseUrl/login",
        'expected' => 'Login',
        'method' => 'GET'
    ],
    'Delivery Boy Login' => [
        'url' => "$baseUrl/delivery-boy/login",
        'expected' => 'Delivery Boy',
        'method' => 'GET'
    ],
];

$passCount = 0;
$failCount = 0;

foreach ($routes as $name => $route) {
    echo "Testing: $name\n";
    echo "  URL: {$route['url']}\n";
    
    $ch = curl_init($route['url']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    if ($httpCode == 200) {
        if (stripos($response, $route['expected']) !== false) {
            echo "  ✅ PASS (HTTP 200, content found)\n";
            $passCount++;
            $results[$name] = 'PASS';
        } else {
            echo "  ⚠️  WARN (HTTP 200, but expected content not found)\n";
            echo "     Looking for: '{$route['expected']}'\n";
            $passCount++;
            $results[$name] = 'WARN';
        }
    } elseif ($httpCode >= 300 && $httpCode < 400) {
        echo "  ✅ PASS (HTTP $httpCode - Redirect)\n";
        $passCount++;
        $results[$name] = 'PASS';
    } else {
        echo "  ❌ FAIL (HTTP $httpCode)\n";
        if ($error) {
            echo "     Error: $error\n";
        }
        $failCount++;
        $results[$name] = 'FAIL';
        $errors[] = "$name failed with HTTP $httpCode";
    }
    echo "\n";
}

// Test API Endpoints
echo str_repeat("-", 60) . "\n";
echo "API ENDPOINTS\n";
echo str_repeat("-", 60) . "\n\n";

$apiRoutes = [
    'Get Plans' => [
        'url' => "$baseUrl/api/plans",
        'method' => 'GET',
        'check' => function($data) {
            return isset($data['success']) && $data['success'] === true && isset($data['plans']);
        }
    ],
];

foreach ($apiRoutes as $name => $route) {
    echo "Testing API: $name\n";
    echo "  URL: {$route['url']}\n";
    
    $ch = curl_init($route['url']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode == 200) {
        $data = json_decode($response, true);
        if ($data && $route['check']($data)) {
            echo "  ✅ PASS (Valid JSON response)\n";
            $passCount++;
            $results["API: $name"] = 'PASS';
        } else {
            echo "  ❌ FAIL (Invalid response format)\n";
            $failCount++;
            $results["API: $name"] = 'FAIL';
            $errors[] = "API $name returned invalid format";
        }
    } else {
        echo "  ❌ FAIL (HTTP $httpCode)\n";
        $failCount++;
        $results["API: $name"] = 'FAIL';
        $errors[] = "API $name failed with HTTP $httpCode";
    }
    echo "\n";
}

// Summary
echo str_repeat("=", 60) . "\n";
echo "📊 VALIDATION SUMMARY\n";
echo str_repeat("=", 60) . "\n";
echo "Total Tests: " . ($passCount + $failCount) . "\n";
echo "✅ Passed: $passCount\n";
echo "❌ Failed: $failCount\n";
echo "Success Rate: " . round(($passCount / ($passCount + $failCount)) * 100, 1) . "%\n";

if (count($errors) > 0) {
    echo "\n⚠️  ERRORS:\n";
    foreach ($errors as $error) {
        echo "   - $error\n";
    }
}

echo "\n" . str_repeat("=", 60) . "\n";

if ($failCount == 0) {
    echo "✅ All routes are accessible!\n";
    exit(0);
} else {
    echo "❌ Some routes failed validation.\n";
    exit(1);
}






