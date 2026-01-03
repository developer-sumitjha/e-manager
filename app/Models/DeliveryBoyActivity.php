<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryBoyActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'delivery_boy_id',
        'manual_delivery_id',
        'activity_type',
        'description',
        'meta_data',
        'ip_address',
    ];

    protected $casts = [
        'meta_data' => 'array',
    ];

    // Relationships
    public function deliveryBoy()
    {
        return $this->belongsTo(DeliveryBoy::class);
    }

    public function manualDelivery()
    {
        return $this->belongsTo(ManualDelivery::class);
    }
}







