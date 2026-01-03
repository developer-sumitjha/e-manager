<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class Shipment extends Model
{
    use BelongsToTenant;
    
    protected $fillable = [
        'tenant_id',
        'order_id',
        'delivery_method',
        'tracking_number',
        'status',
        'estimated_delivery_date',
        'actual_delivery_date',
        'notes',
        'delivery_agent_name',
        'delivery_agent_phone',
        'logistics_company',
        'created_by'
    ];

    protected $casts = [
        'estimated_delivery_date' => 'date',
        'actual_delivery_date' => 'date',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'warning',
            'shipped' => 'info',
            'in_transit' => 'primary',
            'delivered' => 'success',
            'returned' => 'danger'
        ];

        return $badges[$this->status] ?? 'secondary';
    }

    public function getDeliveryMethodBadgeAttribute()
    {
        return $this->delivery_method === 'manual' ? 'success' : 'primary';
    }
}