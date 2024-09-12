<?php

use App\Http\Middleware\Authenticate;
use Illuminate\Foundation\Application;
use App\Http\Middleware\RedirectIfAuthenticated;
use App\Http\Middleware\EnsureAdminEmailIsVerified;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            \App\Http\Middleware\HandleInertiaRequests::class,
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
        ]);
        $middleware->alias([
            'auth' => Authenticate::class, // override default, for admin redirect
            'guest' => RedirectIfAuthenticated::class, // override default, for admin redirect
            'verified-admin' => EnsureAdminEmailIsVerified::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
