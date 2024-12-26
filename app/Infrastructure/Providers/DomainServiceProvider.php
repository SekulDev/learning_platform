<?php

namespace App\Infrastructure\Providers;

use App\Application\Services\JwtTokenService;
use App\Application\Services\OAuthServiceImpl;
use App\Domain\Repositories\UserRepository;
use App\Domain\Services\OAuthService;
use App\Domain\Services\TokenService;
use App\Infrastructure\Persistence\Eloquent\Repositories\UserRepositoryImpl;
use Illuminate\Support\ServiceProvider;

class DomainServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(UserRepository::class, UserRepositoryImpl::class);

        $this->app->bind(TokenService::class, JwtTokenService::class);
        $this->app->bind(OAuthService::class, OAuthServiceImpl::class);

        $this->app->singleton(TokenService::class, function () {
            return new JwtTokenService(
                config('jwt.secret'),
                config('jwt.ttl')
            );
        });
    }
}
