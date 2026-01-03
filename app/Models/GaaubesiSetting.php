<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class GaaubesiSetting extends Model
{
    use BelongsToTenant;
    
    protected $fillable = [
        'tenant_id',
        'api_token',
        'api_url',
        'default_package_access',
        'default_delivery_type',
        'default_insurance',
        'pickup_branch',
        'pickup_address',
        'pickup_contact_person',
        'pickup_contact_phone',
        'auto_create_shipment',
        'send_notifications',
        'is_active',
    ];

    protected $casts = [
        'auto_create_shipment' => 'boolean',
        'send_notifications' => 'boolean',
        'is_active' => 'boolean',
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
                'api_url' => 'https://api.gaaubesi.com',
                'default_package_access' => 'Accessible',
                'default_delivery_type' => 'Normal',
                'default_insurance' => 'Not Insured',
                'send_notifications' => true,
                'is_active' => true,
            ]
        );
    }
}







