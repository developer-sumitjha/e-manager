<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class PathaoShipment extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'order_id',
        'shipment_id',
        'pathao_order_id',
        'consignment_id',
        'tracking_id',
        'store_id',
        'item_type',
        'delivery_type',
        'item_weight',
        'recipient_name',
        'recipient_phone',
        'recipient_address',
        'recipient_city_id',
        'recipient_city_name',
        'recipient_zone_id',
        'recipient_zone_name',
        'recipient_area_id',
        'recipient_area_name',
        'amount',
        'delivery_charge',
        'item_quantity',
        'item_description',
        'special_instruction',
        'merchant_order_id',
        'status',
        'status_type',
        'cod_collected',
        'cod_amount',
        'api_response',
        'status_history',
        'shipped_at',
        'delivered_at',
        'created_by',
    ];

    protected $casts = [
        'item_type' => 'integer',
        'delivery_type' => 'integer',
        'item_weight' => 'decimal:2',
        'recipient_city_id' => 'integer',
        'recipient_zone_id' => 'integer',
        'recipient_area_id' => 'integer',
        'store_id' => 'integer',
        'amount' => 'decimal:2',
        'delivery_charge' => 'decimal:2',
        'cod_amount' => 'decimal:2',
        'cod_collected' => 'boolean',
        'api_response' => 'array',
        'status_history' => 'array',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    /**
     * Get the order associated with this Pathao shipment
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the shipment associated with this Pathao shipment
     */
    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }

    /**
     * Get the user who created this shipment
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Add status to history
     */
    public function addStatusHistory($status, $description = null)
    {
        $history = $this->status_history ?? [];
        $history[] = [
            'status' => $status,
            'description' => $description,
            'timestamp' => now()->toDateTimeString(),
        ];
        $this->status_history = $history;
        $this->save();
    }

    /**
     * Check if COD is collected
     */
    public function isCodCollected()
    {
        return $this->cod_collected;
    }

    /**
     * Check if delivered
     */
    public function isDelivered()
    {
        return $this->delivered_at !== null;
    }

    /**
     * Get tracking URL
     */
    public function getTrackingUrl()
    {
        if ($this->tracking_id) {
            return "https://pathao.com/track/{$this->tracking_id}";
        }
        return null;
    }

    /**
     * Get status badge color
     */
    public function getStatusColor()
    {
        if ($this->isDelivered()) {
            return 'success';
        } elseif ($this->status && str_contains(strtolower($this->status), 'failed')) {
            return 'danger';
        } elseif ($this->status && str_contains(strtolower($this->status), 'transit')) {
            return 'info';
        } elseif ($this->status) {
            return 'warning';
        }
        return 'secondary';
    }
}
