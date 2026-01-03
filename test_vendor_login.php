<?php
/**
 * Test Vendor Login Script
 * Run from browser: http://localhost/e-manager/public/test_vendor_login.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "<h1>Vendor Login Test</h1>";
echo "<hr>";

try {
    // Test credentials
    $email = 'dreamadsnepal@gmail.com';
    $password = 'password123';
    
    echo "<h2>Testing Login for: {$email}</h2>";
    
    // Check if user exists
    $user = App\Models\User::where('email', $email)->first();
    
    if (!$user) {
        echo "<div style='background: #ffe6e6; padding: 20px; margin: 20px 0; border-left: 4px solid red;'>";
        echo "<h3>❌ User Not Found</h3>";
        echo "<p>No user found with email: {$email}</p>";
        echo "</div>";
        exit;
    }
    
    echo "<div style='background: #e6ffe6; padding: 20px; margin: 20px 0; border-left: 4px solid green;'>";
    echo "<h3>✅ User Found</h3>";
    echo "<p><strong>Name:</strong> {$user->name}</p>";
    echo "<p><strong>Email:</strong> {$user->email}</p>";
    echo "<p><strong>Role:</strong> {$user->role}</p>";
    echo "<p><strong>Tenant ID:</strong> " . ($user->tenant_id ?: 'NULL') . "</p>";
    echo "<p><strong>Is Active:</strong> " . ($user->is_active ? 'Yes' : 'No') . "</p>";
    echo "</div>";
    
    // Check tenant
    if ($user->tenant_id) {
        $tenant = $user->tenant;
        if ($tenant) {
            echo "<div style='background: #e6ffe6; padding: 20px; margin: 20px 0; border-left: 4px solid green;'>";
            echo "<h3>✅ Tenant Found</h3>";
            echo "<p><strong>Business Name:</strong> {$tenant->business_name}</p>";
            echo "<p><strong>Status:</strong> {$tenant->status}</p>";
            echo "<p><strong>Email:</strong> {$tenant->business_email}</p>";
            echo "</div>";
            
            // Check tenant status
            if ($tenant->status === 'suspended') {
                echo "<div style='background: #ffe6e6; padding: 20px; margin: 20px 0; border-left: 4px solid red;'>";
                echo "<h3>❌ Login Blocked: Tenant Suspended</h3>";
                echo "<p>Your vendor account is suspended. Please contact support.</p>";
                echo "</div>";
            } elseif ($tenant->status === 'pending') {
                echo "<div style='background: #fff3cd; padding: 20px; margin: 20px 0; border-left: 4px solid orange;'>";
                echo "<h3>⚠️ Login Blocked: Tenant Pending</h3>";
                echo "<p>Your vendor account is pending approval. Please wait for activation.</p>";
                echo "</div>";
            } else {
                echo "<div style='background: #e6ffe6; padding: 20px; margin: 20px 0; border-left: 4px solid green;'>";
                echo "<h3>✅ Tenant Status OK</h3>";
                echo "<p>Tenant is active and ready for login.</p>";
                echo "</div>";
            }
        } else {
            echo "<div style='background: #ffe6e6; padding: 20px; margin: 20px 0; border-left: 4px solid red;'>";
            echo "<h3>❌ Tenant Not Found</h3>";
            echo "<p>User has tenant_id but tenant record not found.</p>";
            echo "</div>";
        }
    } else {
        echo "<div style='background: #ffe6e6; padding: 20px; margin: 20px 0; border-left: 4px solid red;'>";
        echo "<h3>❌ No Tenant ID</h3>";
        echo "<p>User has no tenant_id assigned.</p>";
        echo "</div>";
    }
    
    // Test password
    echo "<h3>Password Test</h3>";
    if (Hash::check($password, $user->password)) {
        echo "<div style='background: #e6ffe6; padding: 20px; margin: 20px 0; border-left: 4px solid green;'>";
        echo "<h3>✅ Password Correct</h3>";
        echo "<p>Password '{$password}' is correct.</p>";
        echo "</div>";
    } else {
        echo "<div style='background: #ffe6e6; padding: 20px; margin: 20px 0; border-left: 4px solid red;'>";
        echo "<h3>❌ Password Incorrect</h3>";
        echo "<p>Password '{$password}' is incorrect.</p>";
        echo "</div>";
    }
    
    // Test authentication
    echo "<h3>Authentication Test</h3>";
    if (Auth::attempt(['email' => $email, 'password' => $password])) {
        echo "<div style='background: #e6ffe6; padding: 20px; margin: 20px 0; border-left: 4px solid green;'>";
        echo "<h3>✅ Authentication Successful</h3>";
        echo "<p>Login should work correctly!</p>";
        echo "</div>";
        
        // Test vendor middleware logic
        $authenticatedUser = Auth::user();
        if ($authenticatedUser->role === 'admin' && $authenticatedUser->tenant) {
            $tenant = $authenticatedUser->tenant;
            if ($tenant->status !== 'suspended' && $tenant->status !== 'pending') {
                echo "<div style='background: #e6ffe6; padding: 20px; margin: 20px 0; border-left: 4px solid green;'>";
                echo "<h3>✅ Vendor Middleware Check Passed</h3>";
                echo "<p>User should be able to access vendor dashboard.</p>";
                echo "</div>";
            } else {
                echo "<div style='background: #ffe6e6; padding: 20px; margin: 20px 0; border-left: 4px solid red;'>";
                echo "<h3>❌ Vendor Middleware Check Failed</h3>";
                echo "<p>Tenant status: {$tenant->status}</p>";
                echo "</div>";
            }
        } else {
            echo "<div style='background: #ffe6e6; padding: 20px; margin: 20px 0; border-left: 4px solid red;'>";
            echo "<h3>❌ Vendor Middleware Check Failed</h3>";
            echo "<p>User is not admin or has no tenant.</p>";
            echo "</div>";
        }
        
        Auth::logout();
    } else {
        echo "<div style='background: #ffe6e6; padding: 20px; margin: 20px 0; border-left: 4px solid red;'>";
        echo "<h3>❌ Authentication Failed</h3>";
        echo "<p>Login failed with provided credentials.</p>";
        echo "</div>";
    }
    
    echo "<hr>";
    echo "<h3>Login Instructions</h3>";
    echo "<ol>";
    echo "<li>Go to: <a href='/e-manager/public/vendor/login' target='_blank'>Vendor Login Page</a></li>";
    echo "<li>Enter Email: <strong>{$email}</strong></li>";
    echo "<li>Enter Password: <strong>{$password}</strong></li>";
    echo "<li>Click Login</li>";
    echo "</ol>";
    
    echo "<p><strong>If login still fails, check:</strong></p>";
    echo "<ul>";
    echo "<li>Browser console for JavaScript errors</li>";
    echo "<li>Network tab for failed requests</li>";
    echo "<li>Laravel logs for server errors</li>";
    echo "<li>Session configuration</li>";
    echo "</ul>";
    
} catch (Exception $e) {
    echo "<div style='background: #ffe6e6; padding: 20px; margin: 20px 0; border-left: 4px solid red;'>";
    echo "<h3>Error:</h3>";
    echo "<p>" . $e->getMessage() . "</p>";
    echo "</div>";
}





