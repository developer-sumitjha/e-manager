<?php
/**
 * Storage Images Fix Script
 * 
 * This script ensures that:
 * 1. The storage symlink exists
 * 2. Storage permissions are correct
 * 3. Product images are accessible
 * 
 * Run this script on your live server after deployment:
 * php fix_storage_images.php
 */

echo "=== Storage Images Fix Script ===\n\n";

// Check if we're in a Laravel environment
if (!file_exists(__DIR__ . '/vendor/autoload.php')) {
    die("Error: This script must be run from the Laravel project root.\n");
}

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "1. Checking storage symlink...\n";
$publicStorage = public_path('storage');
$storageAppPublic = storage_path('app/public');

if (!file_exists($publicStorage)) {
    echo "   ⚠ Storage symlink does not exist. Creating...\n";
    try {
        // Remove if it's a broken symlink
        if (is_link($publicStorage)) {
            unlink($publicStorage);
        }
        
        // Create the symlink
        symlink($storageAppPublic, $publicStorage);
        echo "   ✓ Storage symlink created successfully!\n";
    } catch (\Exception $e) {
        echo "   ✗ Failed to create symlink: " . $e->getMessage() . "\n";
        echo "   Please run manually: php artisan storage:link\n";
    }
} else {
    if (is_link($publicStorage)) {
        $target = readlink($publicStorage);
        if ($target === $storageAppPublic || realpath($publicStorage) === realpath($storageAppPublic)) {
            echo "   ✓ Storage symlink exists and is correct.\n";
        } else {
            echo "   ⚠ Storage symlink exists but points to wrong location.\n";
            echo "   Current: $target\n";
            echo "   Expected: $storageAppPublic\n";
            echo "   Please run: php artisan storage:link\n";
        }
    } else {
        echo "   ⚠ public/storage exists but is not a symlink.\n";
        echo "   Please remove it and run: php artisan storage:link\n";
    }
}

echo "\n2. Checking storage directory permissions...\n";
if (is_dir($storageAppPublic)) {
    $perms = substr(sprintf('%o', fileperms($storageAppPublic)), -4);
    echo "   Storage directory permissions: $perms\n";
    
    // Check if writable
    if (is_writable($storageAppPublic)) {
        echo "   ✓ Storage directory is writable.\n";
    } else {
        echo "   ⚠ Storage directory is not writable.\n";
        echo "   Please run: chmod -R 775 storage/app/public\n";
    }
} else {
    echo "   ⚠ Storage directory does not exist. Creating...\n";
    mkdir($storageAppPublic, 0755, true);
    echo "   ✓ Storage directory created.\n";
}

echo "\n3. Checking product images...\n";
try {
    $products = \App\Models\Product::whereNotNull('image')
        ->orWhereNotNull('images')
        ->limit(5)
        ->get();
    
    $imageCount = 0;
    $missingCount = 0;
    
    foreach ($products as $product) {
        if ($product->primary_image_url) {
            $imageCount++;
            // Check if the file actually exists
            $imagePath = $product->images && is_array($product->images) && count($product->images) > 0
                ? $product->images[$product->primary_image_index ?? 0] ?? $product->images[0]
                : $product->image;
            
            if ($imagePath && !\Illuminate\Support\Facades\Storage::disk('public')->exists($imagePath)) {
                $missingCount++;
                echo "   ⚠ Product '{$product->name}' has image path but file is missing: $imagePath\n";
            }
        }
    }
    
    echo "   ✓ Found $imageCount products with images.\n";
    if ($missingCount > 0) {
        echo "   ⚠ $missingCount products have missing image files.\n";
    }
} catch (\Exception $e) {
    echo "   ⚠ Could not check product images: " . $e->getMessage() . "\n";
}

echo "\n4. Testing image URL generation...\n";
try {
    $testProduct = \App\Models\Product::whereNotNull('image')
        ->orWhereNotNull('images')
        ->first();
    
    if ($testProduct && $testProduct->primary_image_url) {
        echo "   Test product: {$testProduct->name}\n";
        echo "   Image URL: {$testProduct->primary_image_url}\n";
        
        // Check if URL is accessible (basic check)
        $urlParts = parse_url($testProduct->primary_image_url);
        if ($urlParts && isset($urlParts['path'])) {
            $filePath = public_path($urlParts['path']);
            if (file_exists($filePath) || is_link($filePath)) {
                echo "   ✓ Image file is accessible via URL path.\n";
            } else {
                echo "   ⚠ Image file may not be accessible. Check symlink.\n";
            }
        }
    } else {
        echo "   ⚠ No products with images found to test.\n";
    }
} catch (\Exception $e) {
    echo "   ⚠ Could not test image URL: " . $e->getMessage() . "\n";
}

echo "\n=== Fix Complete ===\n";
echo "\nIf images still don't display, ensure:\n";
echo "1. Storage symlink exists: php artisan storage:link\n";
echo "2. Storage permissions: chmod -R 775 storage/app/public\n";
echo "3. Web server can follow symlinks (check .htaccess or nginx config)\n";
echo "4. APP_URL in .env matches your domain\n";
