<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class StockAdjustment extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'product_id',
        'adjustment_number',
        'type',
        'quantity',
        'old_stock',
        'new_stock',
        'reason',
        'notes',
        'reference_number',
        'adjusted_by',
        'adjustment_date',
    ];

    protected $casts = [
        'adjustment_date' => 'datetime',
        'quantity' => 'integer',
        'old_stock' => 'integer',
        'new_stock' => 'integer',
    ];

    /**
     * Get the product that owns the adjustment
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the user who made the adjustment
     */
    public function adjustedBy()
    {
        return $this->belongsTo(User::class, 'adjusted_by');
    }

    /**
     * Get the tenant that owns the adjustment
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Get human-readable type
     */
    public function getTypeDisplayAttribute()
    {
        return ucfirst($this->type);
    }

    /**
     * Get human-readable reason
     */
    public function getReasonDisplayAttribute()
    {
        return ucwords(str_replace('_', ' ', $this->reason));
    }

    /**
     * Get badge class for type
     */
    public function getTypeBadgeClassAttribute()
    {
        return $this->type === 'increase' ? 'success' : 'danger';
    }

    /**
     * Get icon for type
     */
    public function getTypeIconAttribute()
    {
        return $this->type === 'increase' ? 'arrow-up' : 'arrow-down';
    }

    /**
     * Scope to filter by type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope to filter by reason
     */
    public function scopeOfReason($query, $reason)
    {
        return $query->where('reason', $reason);
    }

    /**
     * Scope to filter by date range
     */
    public function scopeDateRange($query, $start, $end)
    {
        return $query->whereBetween('adjustment_date', [$start, $end]);
    }

    /**
     * Generate adjustment number
     */
    public static function generateAdjustmentNumber()
    {
        $lastAdjustment = self::latest()->first();
        $nextNumber = $lastAdjustment ? intval(substr($lastAdjustment->adjustment_number, 4)) + 1 : 1;
        return 'ADJ-' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
    }
}






