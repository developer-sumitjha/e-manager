<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TenantInvoice extends Model
{
    protected $table = 'tenant_invoices';

    protected $fillable = [
        'invoice_id',
        'tenant_id',
        'subscription_id',
        'amount',
        'currency',
        'status',
        'due_date',
        'paid_at',
        'payment_method',
        'payment_reference',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'due_date' => 'datetime',
        'paid_at' => 'datetime',
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

    public function payments()
    {
        return $this->hasMany(TenantPayment::class, 'invoice_id');
    }
}
