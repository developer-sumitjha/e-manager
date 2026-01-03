<?php

namespace App\Services;

use App\Models\Tenant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class TenantManager
{
    protected $currentTenant = null;

    /**
     * Set current tenant and switch database
     */
    public function setTenant(Tenant $tenant)
    {
        $this->currentTenant = $tenant;
        $tenant->configureDatabaseConnection();
        
        // Store in session
        session(['current_tenant_id' => $tenant->id]);
        
        return $this;
    }

    /**
     * Get current tenant
     */
    public function getTenant()
    {
        if (!$this->currentTenant && session('current_tenant_id')) {
            $this->currentTenant = Tenant::find(session('current_tenant_id'));
        }
        
        return $this->currentTenant;
    }

    /**
     * Create database for new tenant
     */
    public function createTenantDatabase(Tenant $tenant)
    {
        try {
            $dbName = 'tenant_' . strtolower($tenant->tenant_id);
            
            Log::info("Creating database for tenant: {$tenant->tenant_id}");
            
            // Create database
            DB::statement("CREATE DATABASE IF NOT EXISTS `{$dbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            
            // Update tenant with database credentials
            $tenant->update([
                'database_name' => $dbName,
                'database_host' => env('DB_HOST', 'localhost'),
                'database_username' => env('DB_USERNAME', 'root'),
                'database_password' => env('DB_PASSWORD', ''),
            ]);
            
            // Configure tenant connection
            $tenant->configureDatabaseConnection();
            
            // Run migrations on tenant database
            Log::info("Running migrations for tenant: {$tenant->tenant_id}");
            
            Artisan::call('migrate', [
                '--database' => 'tenant',
                '--force' => true,
            ]);
            
            // Seed initial tenant data
            $this->seedTenantDatabase($tenant);
            
            Log::info("Database created successfully for tenant: {$tenant->tenant_id}");
            
            return true;
            
        } catch (\Exception $e) {
            Log::error("Failed to create tenant database: " . $e->getMessage());
            
            // Rollback - delete database if created
            try {
                if (isset($dbName)) {
                    DB::statement("DROP DATABASE IF EXISTS `{$dbName}`");
                }
            } catch (\Exception $rollbackError) {
                Log::error("Rollback failed: " . $rollbackError->getMessage());
            }
            
            return false;
        }
    }

    /**
     * Seed initial data for tenant
     */
    protected function seedTenantDatabase(Tenant $tenant)
    {
        try {
            // Create admin user for tenant
            DB::connection('tenant')->table('users')->insert([
                'name' => $tenant->owner_name,
                'email' => $tenant->owner_email,
                'password' => $tenant->password, // Already hashed
                'phone' => $tenant->owner_phone,
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            Log::info("Seeded initial data for tenant: {$tenant->tenant_id}");
            
        } catch (\Exception $e) {
            Log::error("Failed to seed tenant database: " . $e->getMessage());
        }
    }

    /**
     * Delete tenant database
     */
    public function deleteTenantDatabase(Tenant $tenant)
    {
        try {
            if ($tenant->database_name) {
                Log::warning("Deleting database for tenant: {$tenant->tenant_id}");
                
                DB::statement("DROP DATABASE IF EXISTS `{$tenant->database_name}`");
                
                Log::info("Database deleted for tenant: {$tenant->tenant_id}");
            }
            
            return true;
            
        } catch (\Exception $e) {
            Log::error("Failed to delete tenant database: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Backup tenant database
     */
    public function backupTenantDatabase(Tenant $tenant)
    {
        if (!$tenant->database_name) {
            return false;
        }
        
        $backupPath = storage_path("app/backups/tenant_{$tenant->tenant_id}_" . now()->format('Y-m-d_H-i-s') . ".sql");
        
        // Create backup directory if not exists
        if (!file_exists(dirname($backupPath))) {
            mkdir(dirname($backupPath), 0755, true);
        }
        
        // Use mysqldump
        $command = sprintf(
            'mysqldump -h %s -u %s -p%s %s > %s',
            $tenant->database_host ?? env('DB_HOST'),
            $tenant->database_username ?? env('DB_USERNAME'),
            $tenant->database_password ?? env('DB_PASSWORD'),
            $tenant->database_name,
            $backupPath
        );
        
        exec($command, $output, $returnVar);
        
        return $returnVar === 0;
    }

    /**
     * Check if tenant is within usage limits
     */
    public function checkUsageLimits(Tenant $tenant)
    {
        $tenant->configureDatabaseConnection();
        
        $ordersThisMonth = DB::connection('tenant')
            ->table('orders')
            ->whereMonth('created_at', now()->month)
            ->count();
        
        $totalProducts = DB::connection('tenant')
            ->table('products')
            ->count();
        
        $totalUsers = DB::connection('tenant')
            ->table('users')
            ->count();
        
        return [
            'orders' => [
                'used' => $ordersThisMonth,
                'limit' => $tenant->max_orders,
                'percentage' => ($ordersThisMonth / $tenant->max_orders) * 100,
                'exceeded' => $ordersThisMonth >= $tenant->max_orders,
            ],
            'products' => [
                'used' => $totalProducts,
                'limit' => $tenant->max_products,
                'percentage' => ($totalProducts / $tenant->max_products) * 100,
                'exceeded' => $totalProducts >= $tenant->max_products,
            ],
            'users' => [
                'used' => $totalUsers,
                'limit' => $tenant->max_users,
                'percentage' => ($totalUsers / $tenant->max_users) * 100,
                'exceeded' => $totalUsers >= $tenant->max_users,
            ],
        ];
    }
}







