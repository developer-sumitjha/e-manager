<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingMethod extends Model
{
    protected $fillable = [
        'tenant_id','name','base_rate','min_days','max_days','is_active'
    ];
}
