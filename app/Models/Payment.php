<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'invoice_id',
        'payment_number',
        'amount',
        'payment_method',
        'method',
        'payment_date',
        'status',
        'reference',
        'notes',
        'paid_at',
        'processed_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date',
        'paid_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function getFormattedAmountAttribute()
    {
        return '₹' . number_format($this->amount, 2);
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'warning',
            'completed' => 'success',
            'failed' => 'danger',
            'refunded' => 'info',
        ];

        return $badges[$this->status] ?? 'secondary';
    }

    public function getStatusTextAttribute()
    {
        $texts = [
            'pending' => 'Pending',
            'completed' => 'Completed',
            'failed' => 'Failed',
            'refunded' => 'Refunded',
        ];

        return $texts[$this->status] ?? 'Unknown';
    }

    public function getMethodBadgeAttribute()
    {
        $badges = [
            'cash' => 'success',
            'credit_card' => 'primary',
            'debit_card' => 'info',
            'bank_transfer' => 'warning',
            'digital_wallet' => 'secondary',
            'other' => 'dark',
        ];

        return $badges[$this->method] ?? 'secondary';
    }

    public function getMethodTextAttribute()
    {
        $texts = [
            'cash' => 'Cash',
            'credit_card' => 'Credit Card',
            'debit_card' => 'Debit Card',
            'bank_transfer' => 'Bank Transfer',
            'digital_wallet' => 'Digital Wallet',
            'other' => 'Other',
        ];

        return $texts[$this->method] ?? 'Unknown';
    }

    public function getIsCompletedAttribute()
    {
        return $this->status === 'completed';
    }

    public function getIsPendingAttribute()
    {
        return $this->status === 'pending';
    }
}

