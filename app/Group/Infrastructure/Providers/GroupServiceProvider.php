<?php

namespace App\Group\Infrastructure\Providers;

use App\Auth\Domain\Dto\NotifyUserDTO;
use App\Auth\Infrastructure\Jobs\NotifyUser;
use App\Group\Application\Services\GroupService;
use App\Group\Domain\Jobs\GroupDispatcher;
use App\Group\Domain\Repositories\GroupRepository;
use App\Group\Infrastructure\Jobs\GroupDispatcherImpl;
use App\Group\Infrastructure\Persistence\Repositories\Eloquent\EloquentGroupRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class GroupServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(GroupRepository::class, EloquentGroupRepository::class);
        $this->app->bind(GroupDispatcher::class, GroupDispatcherImpl::class);

        $this->app->bindMethod([NotifyUser::class, '__construct'], function (NotifyUser $job, Application $app) {
            return new $job($app->make(NotifyUserDTO::class));
        });

        $this->app->singleton(GroupService::class, function ($app) {
            return new GroupService(
                $app->make('App\Group\Domain\Repositories\GroupRepository'),
                $app->make('App\Auth\Domain\Repositories\UserRepository'),
                $app->make('App\Group\Domain\Jobs\GroupDispatcher')
            );
        });
    }
}
