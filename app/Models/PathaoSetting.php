<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class PathaoSetting extends Model
{
    use BelongsToTenant;
    
    protected $fillable = [
        'tenant_id',
        'client_id',
        'client_secret',
        'username',
        'password',
        'api_url',
        'access_token',
        'refresh_token',
        'token_expires_at',
        'store_id',
        'store_name',
        'default_item_type',
        'default_delivery_type',
        'default_item_weight',
        'pickup_city_id',
        'pickup_zone_id',
        'pickup_area_id',
        'pickup_address',
        'pickup_contact_name',
        'pickup_contact_number',
        'auto_create_shipment',
        'send_notifications',
        'is_active',
    ];

    protected $casts = [
        'default_item_type' => 'integer',
        'default_delivery_type' => 'integer',
        'default_item_weight' => 'decimal:2',
        'pickup_city_id' => 'integer',
        'pickup_zone_id' => 'integer',
        'pickup_area_id' => 'integer',
        'store_id' => 'integer',
        'auto_create_shipment' => 'boolean',
        'send_notifications' => 'boolean',
        'is_active' => 'boolean',
        'token_expires_at' => 'datetime',
    ];

    // Get settings for current tenant
    public static function getForCurrentTenant()
    {
        $user = auth()->user();
        if (!$user || !$user->tenant_id) {
            return null;
        }

        return static::firstOrCreate(
            ['tenant_id' => $user->tenant_id],
            [
                'api_url' => 'https://courier-api-sandbox.pathao.com',
                'default_item_type' => 2,
                'default_delivery_type' => 48,
                'default_item_weight' => 0.5,
                'send_notifications' => true,
                'is_active' => true,
            ]
        );
    }
    
    /**
     * Check if access token is expired or will expire soon
     */
    public function isTokenExpired()
    {
        if (!$this->token_expires_at) {
            return true;
        }
        
        // Consider token expired if it expires in less than 5 minutes
        return $this->token_expires_at->copy()->subMinutes(5)->isPast();
    }
}
