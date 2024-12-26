<?php

namespace App\Infrastructure\Providers;

use App\Application\Services\AuthService;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Laravel\Socialite\Facades\Socialite;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(AuthService::class, function ($app) {
            return new AuthService(
                $app->make('App\Domain\Repositories\UserRepository'),
                $app->make('App\Domain\Services\TokenService'),
                $app->make('App\Domain\Services\OAuthService')
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        // only for dev
        $guzzle = new Client([
            'verify' => false,
        ]);
        Socialite::driver('github')->setHttpClient($guzzle);


        Vite::prefetch(concurrency: 3);
    }
}
