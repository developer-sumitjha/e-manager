<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class TenantScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        // Only apply if user is logged in and has a tenant_id
        $user = auth()->user();
        
        if ($user && isset($user->tenant_id)) {
            try {
                // Check if tenant_id column exists in the table
                $table = $model->getTable();
                $columns = \Illuminate\Support\Facades\Schema::getColumnListing($table);
                
                if (in_array('tenant_id', $columns)) {
                    $builder->where($table . '.tenant_id', $user->tenant_id);
                }
            } catch (\Exception $e) {
                // If there's an error checking columns, skip the scope
                // This prevents errors when table doesn't exist or column is missing
                \Illuminate\Support\Facades\Log::warning('TenantScope: Could not apply scope', [
                    'table' => $model->getTable(),
                    'error' => $e->getMessage()
                ]);
            }
        }
    }
}







