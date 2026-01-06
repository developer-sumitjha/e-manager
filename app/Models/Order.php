<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\BelongsToTenant;

class Order extends Model
{
    use BelongsToTenant, HasFactory;
    protected $fillable = [
        'tenant_id',
        'user_id',
        'order_number',
        'status',
        'payment_status',
        'payment_method',
        'shipping_method',
        'subtotal',
        'shipping_cost',
        'tax_amount',
        'discount_total',
        'coupon_code',
        'total',
        'billing_first_name',
        'billing_last_name',
        'billing_email',
        'billing_phone',
        'billing_address',
        'billing_city',
        'billing_state',
        'billing_postal_code',
        'billing_country',
        'shipping_first_name',
        'shipping_last_name',
        'shipping_email',
        'shipping_phone',
        'shipping_address',
        'shipping_city',
        'shipping_state',
        'shipping_postal_code',
        'shipping_country',
        // Legacy fields for backward compatibility
        'tax',
        'shipping',
        'notes',
        'is_manual',
        'created_by',
        'receiver_name',
        'receiver_phone',
        'receiver_city',
        'receiver_area',
        'receiver_full_address',
        'delivery_branch',
        'package_access',
        'delivery_type',
        'package_type',
        'sender_name',
        'sender_phone',
        'delivery_instructions',
        'last_sync_at',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_total' => 'decimal:2',
        'total' => 'decimal:2',
        // Legacy fields
        'tax' => 'decimal:2',
        'shipping' => 'decimal:2',
        'is_manual' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function shipment()
    {
        return $this->hasOne(Shipment::class);
    }

    public function manualDelivery()
    {
        return $this->hasOne(ManualDelivery::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function gaaubesiShipment()
    {
        return $this->hasOne(GaaubesiShipment::class);
    }

    /**
     * Get the sum of all order items (actual items total)
     * This calculates the total from order items, not from the stored subtotal
     */
    public function getItemsTotalAttribute()
    {
        // Ensure order items are loaded
        if (!$this->relationLoaded('orderItems')) {
            $this->load('orderItems');
        }
        
        return $this->orderItems->sum(function($item) {
            return ($item->quantity ?? 0) * ($item->price ?? 0);
        });
    }

    /**
     * Get the effective subtotal (use stored subtotal if available, otherwise calculate from items)
     */
    public function getEffectiveSubtotalAttribute()
    {
        if ($this->subtotal && $this->subtotal > 0) {
            return $this->subtotal;
        }
        return $this->items_total;
    }
}
