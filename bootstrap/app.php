<?php

use App\Http\Middleware\EnsureAdmin;
use App\Http\Middleware\EnsureReseller;
use App\Http\Middleware\PilihTampilan;
use App\Http\Middleware\SecurityHeaders;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');
        $middleware->alias(['admin' => EnsureAdmin::class, 'reseller' => EnsureReseller::class]);
        $middleware->validateCsrfTokens(except: ['midtrans/notify']);
        $middleware->web(append: [PilihTampilan::class, SecurityHeaders::class]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
