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

    /**
     * Get the effective COD amount based on current order items total
     * This ensures COD amount always matches the sum of order items
     */
    public function getEffectiveCodAmountAttribute()
    {
        // Ensure order is loaded
        if (!$this->relationLoaded('order')) {
            $this->load('order');
        }
        
        // If order doesn't exist or payment method is not COD, return 0
        if (!$this->order || $this->order->payment_method !== 'cod') {
            return 0;
        }
        
        // Ensure order items are loaded
        if (!$this->order->relationLoaded('orderItems')) {
            $this->order->load('orderItems');
        }
        
        // Calculate items total from order items
        $itemsTotal = $this->order->orderItems->sum(function($item) {
            return ($item->quantity ?? 0) * ($item->price ?? 0);
        });
        
        return $itemsTotal;
    }

    /**
     * Sync COD amount with order items total
     * Call this method to update stored cod_amount to match current order items sum
     */
    public function syncCodAmount()
    {
        // Ensure order is loaded
        if (!$this->relationLoaded('order')) {
            $this->load('order');
        }
        
        if (!$this->order) {
            return;
        }
        
        // Ensure order items are loaded
        if (!$this->order->relationLoaded('orderItems')) {
            $this->order->load('orderItems');
        }
        
        // Only calculate COD if payment method is COD
        if ($this->order->payment_method !== 'cod') {
            // If not COD, set to 0
            if ($this->cod_amount != 0) {
                $this->cod_amount = 0;
                $this->save();
            }
            return;
        }
        
        // Calculate COD from actual order items sum
        $itemsTotal = $this->order->orderItems->sum(function($item) {
            return ($item->quantity ?? 0) * ($item->price ?? 0);
        });
        
        // Always update to ensure it's correct (even if same, in case of data inconsistency)
        $this->cod_amount = $itemsTotal;
        $this->save();
    }
}
