<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CodSettlement extends Model
{
    use HasFactory;

    protected $fillable = [
        'settlement_id',
        'delivery_boy_id',
        'settled_by',
        'total_amount',
        'total_orders',
        'order_ids',
        'payment_method',
        'transaction_reference',
        'notes',
        'settled_at',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'settled_at' => 'datetime',
    ];

    // Relationships
    public function deliveryBoy()
    {
        return $this->belongsTo(DeliveryBoy::class);
    }

    public function settledBy()
    {
        return $this->belongsTo(User::class, 'settled_by');
    }

    // Helpers
    public function getOrderIdsArray()
    {
        return json_decode($this->order_ids, true) ?? [];
    }

    public function getOrders()
    {
        $orderIds = $this->getOrderIdsArray();
        return Order::whereIn('id', $orderIds)->get();
    }
}







