<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'delivery_boy' => \App\Http\Middleware\DeliveryBoyAuth::class,
            'vendor' => \App\Http\Middleware\VendorMiddleware::class,
            'plan.limit' => \App\Http\Middleware\CheckPlanLimits::class,
                    'subscription.active' => \App\Http\Middleware\EnsureActiveSubscription::class,
                    'admin_employee' => \App\Http\Middleware\AdminEmployeeMiddleware::class,
                    'employee.can' => \App\Http\Middleware\EmployeePermission::class,
                    'customer' => \App\Http\Middleware\CustomerMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
