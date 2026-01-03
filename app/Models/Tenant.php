<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

class Tenant extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'business_name',
        'subdomain',
        'domain',
        'business_type',
        'business_email',
        'business_phone',
        'business_address',
        'pan_number',
        'registration_number',
        'owner_name',
        'owner_email',
        'owner_phone',
        'password',
        'current_plan_id',
        'status',
        'trial_ends_at',
        'subscription_starts_at',
        'subscription_ends_at',
        'settings',
        'features',
        'max_orders',
        'max_products',
        'max_users',
        'database_name',
        'database_host',
        'database_username',
        'database_password',
        'is_verified',
        'verified_at',
        'last_login_at',
        'last_login_ip',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'database_password',
    ];

    protected $casts = [
        'settings' => 'array',
        'features' => 'array',
        'is_verified' => 'boolean',
        'verified_at' => 'datetime',
        'trial_ends_at' => 'datetime',
        'subscription_starts_at' => 'datetime',
        'subscription_ends_at' => 'datetime',
        'last_login_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relationships
    public function currentPlan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'current_plan_id');
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function activeSubscription()
    {
        return $this->hasOne(Subscription::class)->where('status', 'active')->latest();
    }

    public function payments()
    {
        return $this->hasMany(TenantPayment::class);
    }

    public function invoices()
    {
        return $this->hasMany(TenantInvoice::class);
    }

    public function activities()
    {
        return $this->hasMany(TenantActivity::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    // Helper Methods
    public function isActive()
    {
        return $this->status === 'active';
    }

    public function isOnTrial()
    {
        return $this->status === 'trial' && $this->trial_ends_at && $this->trial_ends_at->isFuture();
    }

    public function trialExpired()
    {
        return $this->status === 'trial' && $this->trial_ends_at && $this->trial_ends_at->isPast();
    }

    public function subscriptionActive()
    {
        return $this->status === 'active' && $this->subscription_ends_at && $this->subscription_ends_at->isFuture();
    }

    public function getDaysUntilTrialEnd()
    {
        if (!$this->trial_ends_at) return 0;
        return max(0, now()->diffInDays($this->trial_ends_at, false));
    }

    public function logActivity($type, $description, $metadata = null)
    {
        return $this->activities()->create([
            'activity_type' => $type,
            'description' => $description,
            'metadata' => $metadata,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    // Database Management
    public function configureDatabaseConnection()
    {
        // In single database mode, we don't configure separate connections
        // This method is kept for backward compatibility
        return true;
        
        if (!$this->database_name) {
            return false;
        }

        Config::set('database.connections.tenant', [
            'driver' => 'mysql',
            'host' => $this->database_host ?? env('DB_HOST', 'localhost'),
            'database' => $this->database_name,
            'username' => $this->database_username ?? env('DB_USERNAME', 'root'),
            'password' => $this->database_password ?? env('DB_PASSWORD', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
        ]);

        DB::purge('tenant');
        DB::reconnect('tenant');

        return true;
    }

    public function createDatabase()
    {
        $dbName = 'tenant_' . strtolower($this->tenant_id);
        
        try {
            // Create database
            DB::statement("CREATE DATABASE IF NOT EXISTS `{$dbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            
            // Update tenant record
            $this->update([
                'database_name' => $dbName,
                'database_host' => env('DB_HOST', 'localhost'),
                'database_username' => env('DB_USERNAME', 'root'),
                'database_password' => env('DB_PASSWORD', ''),
            ]);
            
            // Configure connection
            // In single database mode, no separate tenant database
            // This is kept for backward compatibility but does nothing
            return true;
            
            return true;
        } catch (\Exception $e) {
            \Log::error('Failed to create tenant database: ' . $e->getMessage());
            return false;
        }
    }
}
