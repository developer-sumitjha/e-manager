<?php
/**
 * Quick diagnostic script to check if admin users have tenant_id
 * Run from browser: http://localhost/e-manager/public/check_admin_tenant.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "<h1>Admin User Tenant ID Check</h1>";
echo "<hr>";

try {
    // Get all users with admin role
    $adminUsers = DB::table('users')
        ->where('role', 'admin')
        ->get();
    
    echo "<h2>Admin Users Found: " . $adminUsers->count() . "</h2>";
    
    if ($adminUsers->count() > 0) {
        echo "<table border='1' cellpadding='10' style='border-collapse: collapse;'>";
        echo "<tr style='background: #f0f0f0;'>";
        echo "<th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Tenant ID</th><th>Status</th>";
        echo "</tr>";
        
        foreach ($adminUsers as $user) {
            $statusColor = $user->tenant_id ? 'green' : 'red';
            $statusText = $user->tenant_id ? '✓ Has Tenant' : '✗ NO TENANT';
            
            echo "<tr>";
            echo "<td>{$user->id}</td>";
            echo "<td>{$user->name}</td>";
            echo "<td>{$user->email}</td>";
            echo "<td>{$user->role}</td>";
            echo "<td>" . ($user->tenant_id ?: '<strong style="color:red;">NULL</strong>') . "</td>";
            echo "<td style='color: {$statusColor}; font-weight: bold;'>{$statusText}</td>";
            echo "</tr>";
        }
        
        echo "</table>";
        
        // Check if any admin has missing tenant_id
        $adminsWithoutTenant = $adminUsers->filter(function($user) {
            return is_null($user->tenant_id);
        });
        
        if ($adminsWithoutTenant->count() > 0) {
            echo "<div style='background: #ffe6e6; padding: 20px; margin: 20px 0; border-left: 4px solid red;'>";
            echo "<h3>⚠️ WARNING: {$adminsWithoutTenant->count()} Admin(s) Missing Tenant ID</h3>";
            echo "<p><strong>This will cause the Site Builder to fail!</strong></p>";
            
            echo "<h4>Solution Options:</h4>";
            echo "<ol>";
            echo "<li><strong>Option 1 (Recommended):</strong> Create a tenant and assign admin to it</li>";
            echo "<li><strong>Option 2:</strong> Use an existing tenant's admin account</li>";
            echo "</ol>";
            
            // Get available tenants
            $tenants = DB::table('tenants')->get();
            
            if ($tenants->count() > 0) {
                echo "<h4>Available Tenants:</h4>";
                echo "<table border='1' cellpadding='10' style='border-collapse: collapse;'>";
                echo "<tr style='background: #f0f0f0;'><th>ID</th><th>Tenant ID</th><th>Business Name</th><th>Email</th><th>Status</th></tr>";
                
                foreach ($tenants as $tenant) {
                    echo "<tr>";
                    echo "<td>{$tenant->id}</td>";
                    echo "<td>{$tenant->tenant_id}</td>";
                    echo "<td>{$tenant->business_name}</td>";
                    echo "<td>{$tenant->business_email}</td>";
                    echo "<td>{$tenant->status}</td>";
                    echo "</tr>";
                }
                
                echo "</table>";
                
                echo "<h4>Quick Fix Command:</h4>";
                echo "<p>Run this in your terminal to assign admin to first tenant:</p>";
                echo "<pre style='background: #f0f0f0; padding: 10px;'>";
                echo "cd /Applications/XAMPP/xamppfiles/htdocs/e-manager\n";
                echo "/Applications/XAMPP/xamppfiles/bin/php artisan tinker\n\n";
                echo "// Then run:\n";
                echo "\$user = User::where('role', 'admin')->where('tenant_id', null)->first();\n";
                echo "\$tenant = Tenant::first();\n";
                echo "\$user->tenant_id = \$tenant->id;\n";
                echo "\$user->save();\n";
                echo "</pre>";
            } else {
                echo "<p style='color: red;'><strong>No tenants found!</strong> Please create a tenant first.</p>";
                echo "<p>Visit: <a href='/e-manager/public/signup'>Create New Tenant</a></p>";
            }
            
            echo "</div>";
        } else {
            echo "<div style='background: #e6ffe6; padding: 20px; margin: 20px 0; border-left: 4px solid green;'>";
            echo "<h3>✓ All Admin Users Have Tenant IDs</h3>";
            echo "<p>Your Site Builder should work correctly!</p>";
            echo "</div>";
        }
        
    } else {
        echo "<div style='background: #fff3cd; padding: 20px; margin: 20px 0; border-left: 4px solid orange;'>";
        echo "<h3>⚠️ No Admin Users Found</h3>";
        echo "<p>Please create an admin user first.</p>";
        echo "</div>";
    }
    
    echo "<hr>";
    echo "<p><small>Diagnostic script completed successfully.</small></p>";
    
} catch (Exception $e) {
    echo "<div style='background: #ffe6e6; padding: 20px; margin: 20px 0; border-left: 4px solid red;'>";
    echo "<h3>Error:</h3>";
    echo "<p>" . $e->getMessage() . "</p>";
    echo "</div>";
}






