<?php

namespace App\Group\Infrastructure\Providers;

use App\Group\Application\Services\GroupService;
use App\Group\Domain\Repositories\GroupRepository;
use App\Group\Infrastructure\Persistence\Repositories\Eloquent\EloquentGroupRepository;
use Illuminate\Support\ServiceProvider;

class GroupServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(GroupRepository::class, EloquentGroupRepository::class);

        $this->app->singleton(GroupService::class, function ($app) {
            return new GroupService(
                $app->make('App\Group\Domain\Repositories\GroupRepository'),
                $app->make('App\Auth\Domain\Repository\UserRepository')
            );
        });
    }
}
