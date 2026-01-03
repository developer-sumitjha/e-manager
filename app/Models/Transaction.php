<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class Transaction extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'account_id',
        'type',
        'amount',
        'description',
        'reference',
        'transaction_date',
        'account_type',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'transaction_date' => 'date',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getFormattedAmountAttribute()
    {
        return 'Rs. ' . number_format($this->amount, 2);
    }

    public function getTypeBadgeAttribute()
    {
        $badges = [
            'income' => 'success',
            'expense' => 'danger',
            'purchase' => 'warning',
            'sale' => 'primary',
            'transfer' => 'info',
        ];

        return $badges[$this->type] ?? 'secondary';
    }

    public function getAccountTypeBadgeAttribute()
    {
        $badges = [
            'cash' => 'success',
            'bank' => 'primary',
            'credit' => 'warning',
        ];

        return $badges[$this->account_type] ?? 'secondary';
    }

    public function getIsIncomeAttribute()
    {
        return $this->type === 'income' || $this->type === 'sale';
    }

    public function getIsExpenseAttribute()
    {
        return $this->type === 'expense' || $this->type === 'purchase';
    }
}

