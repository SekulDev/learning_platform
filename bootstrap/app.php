<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->priority([
            \App\Common\Infrastructure\Middleware\TrustProxies::class,
            \App\Common\Infrastructure\Middleware\EncryptCookies::class,
            \App\Common\Infrastructure\Middleware\HandleInertiaRequests::class,
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
        ]);
        $middleware->web(append: [
            \App\Common\Infrastructure\Middleware\TrustProxies::class,
            \App\Common\Infrastructure\Middleware\EncryptCookies::class,
            \App\Common\Infrastructure\Middleware\HandleInertiaRequests::class,
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
        ]);
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (\App\Common\Domain\Exceptions\HttpException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json(['error' => $e->getMessage()], $e->getStatusCode());
            } else {
                if ($e->getStatusCode() == 404) {
                    abort(404);
                } else if ($e->getStatusCode() == 500) {
                    abort(500);
                } else if ($e->getStatusCode() == 401) {
                    return response()->redirectTo('/login');
                } else {
                    // temporary
                    abort(500);
                }
            }
        });
    })->create();
