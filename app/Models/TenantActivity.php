<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TenantActivity extends Model
{
    protected $fillable = [
        'tenant_id',
        'activity_type',
        'description',
        'meta_data',
        'ip_address',
    ];

    protected $casts = [
        'meta_data' => 'array',
    ];

    // Relationships
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
