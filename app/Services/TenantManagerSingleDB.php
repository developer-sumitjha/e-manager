<?php

namespace App\Services;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class TenantManagerSingleDB
{
    protected $currentTenant = null;

    /**
     * Set current tenant (for session)
     */
    public function setTenant(Tenant $tenant)
    {
        $this->currentTenant = $tenant;
        
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
     * Setup new tenant (without separate database)
     * Just create admin user in main database with tenant_id
     */
    public function setupTenant(Tenant $tenant, $password)
    {
        try {
            Log::info("Setting up tenant in single database: {$tenant->tenant_id}");
            
            // Create admin user for this tenant in main database
            $user = User::create([
                'tenant_id' => $tenant->id,
                'name' => $tenant->owner_name,
                'email' => $tenant->owner_email,
                'phone' => $tenant->owner_phone,
                'password' => Hash::make($password),
                'role' => 'admin',
                'is_active' => true,
            ]);

            Log::info("Admin user created for tenant: {$tenant->tenant_id}");

            return true;
        } catch (\Exception $e) {
            Log::error("Failed to setup tenant: " . $e->getMessage());
            return false;
        }
    }

    /**
     * For backward compatibility - just call setupTenant
     */
    public function createTenantDatabase(Tenant $tenant)
    {
        // In single database mode, we don't create separate databases
        // This is kept for compatibility with existing code
        return true;
    }
}







