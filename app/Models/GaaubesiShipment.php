<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class GaaubesiShipment extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'order_id',
        'shipment_id',
        'gaaubesi_order_id',
        'track_id',
        'source_branch',
        'destination_branch',
        'receiver_name',
        'receiver_address',
        'receiver_number',
        'cod_charge',
        'delivery_charge',
        'package_access',
        'delivery_type',
        'remarks',
        'package_type',
        'order_contact_name',
        'order_contact_number',
        'last_delivery_status',
        'cod_paid',
        'api_response',
        'status_history',
        'shipped_at',
        'delivered_at',
        'created_by',
    ];

    protected $casts = [
        'cod_charge' => 'decimal:2',
        'delivery_charge' => 'decimal:2',
        'cod_paid' => 'boolean',
        'api_response' => 'array',
        'status_history' => 'array',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    /**
     * Get the order associated with this Gaaubesi shipment
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the shipment associated with this Gaaubesi shipment
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
     * Check if COD is paid
     */
    public function isCodPaid()
    {
        return $this->cod_paid;
    }

    /**
     * Check if delivered
     */
    public function isDelivered()
    {
        return $this->delivered_at !== null;
    }

    /**
     * Get tracking URL (if Gaaubesi provides one)
     */
    public function getTrackingUrl()
    {
        if ($this->track_id) {
            return "https://delivery.gaaubesi.com/tracking/{$this->track_id}";
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
        } elseif ($this->last_delivery_status && str_contains(strtolower($this->last_delivery_status), 'failed')) {
            return 'danger';
        } elseif ($this->last_delivery_status && str_contains(strtolower($this->last_delivery_status), 'transit')) {
            return 'info';
        } elseif ($this->last_delivery_status) {
            return 'warning';
        }
        return 'secondary';
    }
}


