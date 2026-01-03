<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsCredit extends Model
{
    protected $fillable = [
        'balance',
        'cost_per_sms',
        'total_purchased',
        'total_used',
    ];

    /**
     * Get the singleton instance
     */
    public static function getInstance()
    {
        return self::firstOrCreate(
            ['id' => 1],
            [
                'balance' => 0,
                'cost_per_sms' => 0.05,
                'total_purchased' => 0,
                'total_used' => 0,
            ]
        );
    }

    /**
     * Deduct credits
     */
    public function deduct(int $amount): bool
    {
        if ($this->balance < $amount) {
            return false;
        }

        $this->decrement('balance', $amount);
        $this->increment('total_used', $amount);

        return true;
    }

    /**
     * Add credits
     */
    public function add(int $amount): void
    {
        $this->increment('balance', $amount);
        $this->increment('total_purchased', $amount);
    }
}
