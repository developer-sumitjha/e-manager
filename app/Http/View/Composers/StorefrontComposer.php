<?php

namespace App\Http\View\Composers;

use App\Models\SiteSettings;
use App\Models\Tenant;
use Illuminate\View\View;
use Illuminate\Support\Facades\Route;

class StorefrontComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view)
    {
        // Only apply to storefront views
        if (!str_contains($view->getName(), 'storefront.')) {
            return;
        }

        // Get subdomain from route parameter
        $subdomain = Route::current()->parameter('subdomain');
        
        if ($subdomain) {
            $tenant = Tenant::where('subdomain', $subdomain)->first();
            
            if ($tenant) {
                $settings = SiteSettings::where('tenant_id', $tenant->id)->first();
                
                // If no settings exist, create defaults
                if (!$settings) {
                    $defaults = SiteSettings::getDefaultSettings();
                    $defaults['tenant_id'] = $tenant->id;
                    $settings = SiteSettings::create($defaults);
                }
                
                $view->with('tenant', $tenant);
                $view->with('settings', $settings);
            }
        }
    }
}




