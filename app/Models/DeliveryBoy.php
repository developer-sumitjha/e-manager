<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Traits\BelongsToTenant;

class DeliveryBoy extends Authenticatable
{
    use HasFactory, Notifiable, BelongsToTenant;

    protected $fillable = [
        'delivery_boy_id',
        'name',
        'phone',
        'email',
        'password',
        'cnic',
        'license_number',
        'address',
        'zone',
        'vehicle_type',
        'vehicle_number',
        'status',
        'rating',
        'total_deliveries',
        'successful_deliveries',
        'cancelled_deliveries',
        'total_cod_collected',
        'pending_settlement',
        'profile_photo',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'rating' => 'decimal:2',
        'total_cod_collected' => 'decimal:2',
        'pending_settlement' => 'decimal:2',
        'last_login_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relationships
    public function manualDeliveries()
    {
        return $this->hasMany(ManualDelivery::class);
    }

    public function activeDeliveries()
    {
        return $this->hasMany(ManualDelivery::class)
            ->whereIn('status', ['assigned', 'picked_up', 'in_transit']);
    }

    public function completedDeliveries()
    {
        return $this->hasMany(ManualDelivery::class)
            ->where('status', 'delivered');
    }

    public function codSettlements()
    {
        return $this->hasMany(CodSettlement::class);
    }

    public function activities()
    {
        return $this->hasMany(DeliveryBoyActivity::class);
    }

    // Helpers
    public function getPendingCodAmount()
    {
        return $this->manualDeliveries()
            ->where('status', 'delivered')
            ->where('cod_collected', true)
            ->where('cod_settled', false)
            ->sum('cod_amount');
    }

    public function updateStats()
    {
        $this->total_deliveries = $this->manualDeliveries()->count();
        $this->successful_deliveries = $this->completedDeliveries()->count();
        $this->cancelled_deliveries = $this->manualDeliveries()->where('status', 'cancelled')->count();
        $this->pending_settlement = $this->getPendingCodAmount();
        
        // Update rating
        $avgRating = $this->manualDeliveries()
            ->whereNotNull('customer_rating')
            ->avg('customer_rating');
        $this->rating = $avgRating ? round($avgRating, 2) : 0;
        
        $this->save();
    }

    public function logActivity($activityType, $description, $manualDeliveryId = null, $metaData = null)
    {
        return $this->activities()->create([
            'manual_delivery_id' => $manualDeliveryId,
            'activity_type' => $activityType,
            'description' => $description,
            'meta_data' => $metaData ? json_encode($metaData) : null,
            'ip_address' => request()->ip(),
        ]);
    }
}
