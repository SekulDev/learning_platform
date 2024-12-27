<?php

namespace App\Auth\Infrastructure\Providers;

use App\Auth\Application\Services\AuthService;
use App\Auth\Application\Services\JwtTokenStrategy;
use App\Auth\Application\Services\OAuthServiceImpl;
use App\Auth\Domain\Repositories\UserRepository;
use App\Auth\Domain\Services\OAuthService;
use App\Auth\Domain\Services\TokenStrategy;
use App\Auth\Infrastructure\Persistence\Repositories\Eloquent\EloquentUserRepository;
use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;
use Laravel\Socialite\Facades\Socialite;

class AuthServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(UserRepository::class, EloquentUserRepository::class);

        $this->app->bind(TokenStrategy::class, JwtTokenStrategy::class);
        $this->app->bind(OAuthService::class, OAuthServiceImpl::class);

        $this->app->singleton(TokenStrategy::class, function () {
            return new JwtTokenStrategy(
                config('jwt.secret'),
                config('jwt.ttl')
            );
        });


        $this->app->singleton(AuthService::class, function ($app) {
            return new AuthService(
                $app->make('App\Auth\Domain\Repositories\UserRepository'),
                $app->make('App\Auth\Domain\Services\TokenStrategy'),
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
