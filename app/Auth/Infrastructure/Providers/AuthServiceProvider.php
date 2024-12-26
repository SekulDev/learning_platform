<?php

namespace App\Auth\Infrastructure\Providers;

use App\Auth\Application\Services\AuthService;
use App\Auth\Application\Services\JwtTokenService;
use App\Auth\Application\Services\OAuthServiceImpl;
use App\Auth\Domain\Repositories\UserRepository;
use App\Auth\Domain\Services\OAuthService;
use App\Auth\Domain\Services\TokenService;
use App\Auth\Infrastructure\Persistence\Repositories\Eloquent\EloquentUserRepository;
use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;
use Laravel\Socialite\Facades\Socialite;

class AuthServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(UserRepository::class, EloquentUserRepository::class);

        $this->app->bind(TokenService::class, JwtTokenService::class);
        $this->app->bind(OAuthService::class, OAuthServiceImpl::class);

        $this->app->singleton(TokenService::class, function () {
            return new JwtTokenService(
                config('jwt.secret'),
                config('jwt.ttl')
            );
        });


        $this->app->singleton(AuthService::class, function ($app) {
            return new AuthService(
                $app->make('App\Auth\Domain\Repositories\UserRepository'),
                $app->make('App\Auth\Domain\Services\TokenService'),
                $app->make('App\Auth\Domain\Services\OAuthService')
            );
        });
    }

    public function boot(): void
    {
        // only for dev
        $guzzle = new Client([
            'verify' => false,
        ]);
        Socialite::driver('github')->setHttpClient($guzzle);
    }
}
