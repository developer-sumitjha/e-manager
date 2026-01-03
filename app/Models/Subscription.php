<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'subscription_id',
        'tenant_id',
        'plan_id',
        'billing_cycle',
        'starts_at',
        'ends_at',
        'trial_ends_at',
        'amount',
        'currency',
        'status',
        'cancelled_at',
        'cancellation_reason',
        'auto_renew',
        'next_billing_date',
        'payment_method',
        'payment_reference',
        'last_payment_at',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'trial_ends_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'next_billing_date' => 'datetime',
        'last_payment_at' => 'datetime',
        'amount' => 'decimal:2',
        'auto_renew' => 'boolean',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function plan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'plan_id');
    }

    public function payments()
    {
        return $this->hasMany(TenantPayment::class);
    }

    public function isActive()
    {
        return $this->status === 'active' && $this->ends_at && $this->ends_at->isFuture();
    }

    public function isExpired()
    {
        return $this->ends_at && $this->ends_at->isPast();
    }

    public function daysRemaining()
    {
        if (!$this->ends_at) return 0;
        return max(0, now()->diffInDays($this->ends_at, false));
    }

    public function renew()
    {
        $duration = $this->billing_cycle === 'yearly' ? 12 : 1;
        
        $this->update([
            'starts_at' => now(),
            'ends_at' => now()->addMonths($duration),
            'next_billing_date' => now()->addMonths($duration),
            'status' => 'active',
        ]);
    }

    public function cancel($reason = null)
    {
        $this->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
            'cancellation_reason' => $reason,
            'auto_renew' => false,
        ]);
    }
}
