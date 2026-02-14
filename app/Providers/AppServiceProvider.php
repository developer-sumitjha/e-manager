<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Blade;
use App\Http\View\Composers\StorefrontComposer;
use App\Helpers\StorefrontHelper;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register view composers
        View::composer('layouts.storefront', StorefrontComposer::class);
        View::composer('storefront.*', StorefrontComposer::class);
        
        // Share StorefrontHelper with all views
        View::share('storefrontHelper', new \App\Helpers\StorefrontHelper());
    }
}
