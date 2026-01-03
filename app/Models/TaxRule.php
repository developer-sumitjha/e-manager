<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaxRule extends Model
{
    protected $fillable = [
        'tenant_id','name','rate','country','state','is_active'
    ];
}
