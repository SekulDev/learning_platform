<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->priority([
            \App\Common\Infrastructure\Http\Middleware\TrustProxies::class,
            \App\Common\Infrastructure\Http\Middleware\EncryptCookies::class,
            \App\Common\Infrastructure\Http\Middleware\HandleInertiaRequests::class,
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
        ]);
        $middleware->api(append: [
            \App\Common\Infrastructure\Http\Middleware\TrustProxies::class,
            \App\Common\Infrastructure\Http\Middleware\EncryptCookies::class,
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
        ]);
        $middleware->web(append: [
            \App\Common\Infrastructure\Http\Middleware\TrustProxies::class,
            \App\Common\Infrastructure\Http\Middleware\EncryptCookies::class,
            \App\Common\Infrastructure\Http\Middleware\HandleInertiaRequests::class,
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
        ]);

        $middleware->alias([
            'web.auth' => \App\Auth\Infrastructure\Http\Middleware\WebJwtAuthMiddleware::class,
            'api.auth' => \App\Auth\Infrastructure\Http\Middleware\JwtAuthMiddleware::class,
        ]);
        $middleware->encryptCookies(except: ['jwt']);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (\App\Common\Domain\Exceptions\HttpException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json(['error' => $e->getMessage(), 'status_code' => $e->getStatusCode()], $e->getStatusCode());
            } else {
                if ($e->getStatusCode() == 404) {
                    abort(404);
                } else if ($e->getStatusCode() == 500) {
                    abort(500);
                } else if ($e->getStatusCode() == 401) {
                    return response()->redirectTo('/login');
                } else {
                    // temporary
                    abort($e->getStatusCode());
                }
            }
        });
    })->create();
