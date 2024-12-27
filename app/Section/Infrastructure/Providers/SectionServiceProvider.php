<?php

namespace App\Section\Infrastructure\Providers;

use App\Section\Application\Services\SectionService;
use App\Section\Domain\Repositories\SectionRepository;
use App\Section\Infrastructure\Persistence\Repositories\Eloquent\EloquentSectionRepository;
use Illuminate\Support\ServiceProvider;

class SectionServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(SectionRepository::class, EloquentSectionRepository::class);

        $this->app->singleton(SectionService::class, function ($app) {
            return new SectionService(
                $app->make('App\Section\Domain\Repositories\SectionRepository'),
                $app->make('App\Auth\Domain\Repositories\UserRepository'),
            );
        });
    }
}
