<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Global middleware — berlaku untuk SEMUA route, termasuk Filament /admin
        $middleware->append(\App\Http\Middleware\LogRequestPerformance::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
