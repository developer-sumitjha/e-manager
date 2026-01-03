<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'plan_id',
        'name',
        'slug',
        'description',
        'price_monthly',
        'price_yearly',
        'currency',
        'max_orders_per_month',
        'max_products',
        'max_users',
        'max_storage_gb',
        'has_inventory',
        'has_manual_delivery',
        'has_logistics_integration',
        'has_accounting',
        'has_analytics',
        'has_api_access',
        'has_multi_user',
        'has_custom_domain',
        'has_priority_support',
        'trial_days',
        'is_active',
        'is_featured',
        'sort_order',
    ];

    protected $casts = [
        'price_monthly' => 'decimal:2',
        'price_yearly' => 'decimal:2',
        'has_inventory' => 'boolean',
        'has_manual_delivery' => 'boolean',
        'has_logistics_integration' => 'boolean',
        'has_accounting' => 'boolean',
        'has_analytics' => 'boolean',
        'has_api_access' => 'boolean',
        'has_multi_user' => 'boolean',
        'has_custom_domain' => 'boolean',
        'has_priority_support' => 'boolean',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

    // Relationships
    public function tenants()
    {
        return $this->hasMany(Tenant::class, 'current_plan_id');
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'plan_id');
    }

    // Helper Methods
    public function getYearlyDiscount()
    {
        if ($this->price_monthly == 0 || $this->price_yearly == 0) {
            return 0;
        }
        
        $monthlyTotal = $this->price_monthly * 12;
        $savings = $monthlyTotal - $this->price_yearly;
        
        return round(($savings / $monthlyTotal) * 100);
    }

    public function getFeaturesList()
    {
        $features = [];
        
        if ($this->has_inventory) $features[] = 'Inventory Management';
        if ($this->has_manual_delivery) $features[] = 'Manual Delivery System';
        if ($this->has_logistics_integration) $features[] = 'Logistics Integration';
        if ($this->has_accounting) $features[] = 'Accounting Module';
        if ($this->has_analytics) $features[] = 'Analytics & Reports';
        if ($this->has_api_access) $features[] = 'API Access';
        if ($this->has_multi_user) $features[] = 'Multi-user Support';
        if ($this->has_custom_domain) $features[] = 'Custom Domain';
        if ($this->has_priority_support) $features[] = 'Priority Support';
        
        $features[] = "Up to {$this->max_orders_per_month} orders/month";
        $features[] = "Up to {$this->max_products} products";
        $features[] = "Up to {$this->max_users} users";
        $features[] = "{$this->max_storage_gb}GB Storage";
        
        return $features;
    }
}
