<?php

namespace App\Traits;

use App\Scopes\TenantScope;
use Illuminate\Database\Eloquent\Model;

trait BelongsToTenant
{
    /**
     * Boot the trait and add global scope
     */
    protected static function bootBelongsToTenant()
    {
        // Add global scope to automatically filter by tenant_id
        static::addGlobalScope(new TenantScope);
        
        // Automatically set tenant_id when creating
        static::creating(function (Model $model) {
            if (auth()->check() && auth()->user()->tenant_id) {
                $model->tenant_id = auth()->user()->tenant_id;
            }
        });
    }

    /**
     * Get the tenant that owns the model
     */
    public function tenant()
    {
        return $this->belongsTo(\App\Models\Tenant::class);
    }
}







