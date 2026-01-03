<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TenantPayment extends Model
{
    protected $table = 'tenant_payments';

    protected $fillable = [
        'payment_id',
        'tenant_id',
        'subscription_id',
        'invoice_id',
        'amount',
        'currency',
        'payment_method',
        'status',
        'transaction_id',
        'gateway_reference',
        'gateway_response',
        'description',
        'notes',
        'paid_at',
        'refunded_at',
        'refund_amount',
        'last_sync_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'refund_amount' => 'decimal:2',
        'gateway_response' => 'array',
        'paid_at' => 'datetime',
        'refunded_at' => 'datetime',
    ];

    // Relationships
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function invoice()
    {
        return $this->belongsTo(TenantInvoice::class, 'invoice_id');
    }
}
