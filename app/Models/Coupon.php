<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id','code','type','value','min_order_amount','usage_limit','used_count','starts_at','expires_at','is_active'
    ];
}






