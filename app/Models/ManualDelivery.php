<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class ManualDelivery extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'order_id',
        'delivery_boy_id',
        'assigned_by',
        'status',
        'assigned_at',
        'picked_up_at',
        'delivered_at',
        'cancelled_at',
        'delivery_notes',
        'cancellation_reason',
        'cod_amount',
        'cod_collected',
        'cod_settled',
        'cod_settled_at',
        'delivery_proof',
        'customer_signature',
        'customer_rating',
        'customer_feedback',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'picked_up_at' => 'datetime',
        'delivered_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'cod_settled_at' => 'datetime',
        'cod_amount' => 'decimal:2',
        'customer_rating' => 'decimal:2',
        'cod_collected' => 'boolean',
        'cod_settled' => 'boolean',
    ];

    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function deliveryBoy()
    {
        return $this->belongsTo(DeliveryBoy::class);
    }

    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function activities()
    {
        return $this->hasMany(DeliveryBoyActivity::class);
    }

    // Helpers
    public function getStatusBadgeClass()
    {
        return match($this->status) {
            'assigned' => 'bg-info',
            'picked_up' => 'bg-primary',
            'in_transit' => 'bg-warning',
            'delivered' => 'bg-success',
            'cancelled' => 'bg-danger',
            'failed' => 'bg-secondary',
            default => 'bg-secondary',
        };
    }

    public function canUpdateStatus()
    {
        return in_array($this->status, ['assigned', 'picked_up', 'in_transit']);
    }
}
