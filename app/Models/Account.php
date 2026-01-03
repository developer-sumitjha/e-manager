<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class Account extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'name',
        'account_number',
        'type',
        'sub_type',
        'opening_balance',
        'current_balance',
        'description',
        'is_active',
    ];

    protected $casts = [
        'opening_balance' => 'decimal:2',
        'current_balance' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function getFormattedBalanceAttribute()
    {
        return 'Rs. ' . number_format($this->current_balance, 2);
    }

    public function getTypeBadgeAttribute()
    {
        $badges = [
            'asset' => 'success',
            'liability' => 'warning',
            'equity' => 'info',
            'income' => 'primary',
            'expense' => 'danger',
        ];

        return $badges[$this->type] ?? 'secondary';
    }

    public function getSubTypeBadgeAttribute()
    {
        $badges = [
            'cash' => 'success',
            'bank' => 'primary',
            'receivable' => 'info',
            'payable' => 'warning',
            'inventory' => 'secondary',
            'equipment' => 'dark',
            'other' => 'light',
        ];

        return $badges[$this->sub_type] ?? 'secondary';
    }
}

