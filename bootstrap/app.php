<?php

use App\Http\Middleware\RedirectToBanPage;
use App\Http\Middleware\SetDefaultVersionForUrl;
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
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->appendToGroup('web', [
            RedirectToBanPage::class,
            SetDefaultVersionForUrl::class,
            // App\Http\Middleware\Opening\Opening::class,
            'cache.headers:private;must_revalidate;etag',
            'throttle:100,1',
        ]);

        $middleware->validateCsrfTokens(except: [
            'goronich',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // ...
    })
    ->create();
