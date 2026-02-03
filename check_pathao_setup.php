<?php
/**
 * Pathao Setup Diagnostic Script
 * Run this to check if Pathao tables and columns are set up correctly
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

echo "=== Pathao Setup Diagnostic ===\n\n";

// Check if pathao_shipments table exists
if (Schema::hasTable('pathao_shipments')) {
    echo "✅ pathao_shipments table exists\n";
    
    // Check for tenant_id column
    $columns = Schema::getColumnListing('pathao_shipments');
    if (in_array('tenant_id', $columns)) {
        echo "✅ tenant_id column exists\n";
    } else {
        echo "❌ tenant_id column MISSING - Run migration: php artisan migrate\n";
    }
    
    // Check other important columns
    $requiredColumns = ['order_id', 'pathao_order_id', 'consignment_id', 'recipient_name', 'status'];
    foreach ($requiredColumns as $col) {
        if (in_array($col, $columns)) {
            echo "✅ {$col} column exists\n";
        } else {
            echo "❌ {$col} column MISSING\n";
        }
    }
    
    // Check record count
    try {
        $count = DB::table('pathao_shipments')->count();
        echo "📊 Total records: {$count}\n";
    } catch (\Exception $e) {
        echo "❌ Error counting records: " . $e->getMessage() . "\n";
    }
} else {
    echo "❌ pathao_shipments table DOES NOT EXIST\n";
    echo "   Run migration: php artisan migrate\n";
}

echo "\n";

// Check if pathao_settings table exists
if (Schema::hasTable('pathao_settings')) {
    echo "✅ pathao_settings table exists\n";
    
    // Check for tenant_id column
    $columns = Schema::getColumnListing('pathao_settings');
    if (in_array('tenant_id', $columns)) {
        echo "✅ tenant_id column exists\n";
    } else {
        echo "❌ tenant_id column MISSING\n";
    }
    
    // Check token columns
    $tokenColumns = ['access_token', 'refresh_token'];
    foreach ($tokenColumns as $col) {
        if (in_array($col, $columns)) {
            $columnType = DB::select("SHOW COLUMNS FROM pathao_settings WHERE Field = '{$col}'")[0]->Type ?? 'unknown';
            if (strpos($columnType, 'text') !== false || strpos($columnType, 'TEXT') !== false) {
                echo "✅ {$col} column exists (TEXT type - good for long tokens)\n";
            } else {
                echo "⚠️  {$col} column exists but type is {$columnType} (should be TEXT)\n";
            }
        } else {
            echo "❌ {$col} column MISSING\n";
        }
    }
} else {
    echo "❌ pathao_settings table DOES NOT EXIST\n";
    echo "   Run migration: php artisan migrate\n";
}

echo "\n=== Diagnostic Complete ===\n";
echo "\nIf you see any ❌ errors, run:\n";
echo "php artisan migrate\n";
echo "\n";
