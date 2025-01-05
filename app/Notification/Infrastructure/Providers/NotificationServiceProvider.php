<?php

namespace App\Notification\Infrastructure\Providers;

use App\Notification\Application\Services\NotificationService;
use App\Notification\Domain\Dto\NotifyUserDTO;
use App\Notification\Domain\Repositories\NotificationRepository;
use App\Notification\Infrastructure\Jobs\NotifyUser;
use App\Notification\Infrastructure\Jobs\RemoveOldNotifications;
use App\Notification\Infrastructure\Persistence\Repositories\Eloquent\EloquentNotificationRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\ServiceProvider;

class NotificationServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(NotificationRepository::class, EloquentNotificationRepository::class);

        $this->app->bindMethod([RemoveOldNotifications::class, 'handle'], function (RemoveOldNotifications $job, Application $app) {
            $job->handle($app->make(NotificationRepository::class));
        });

        $this->app->bindMethod([NotifyUser::class, '__construct'], function (NotifyUser $job, Application $app) {
            return new $job($app->make(NotifyUserDTO::class));
        });

        $this->app->bindMethod([NotifyUser::class, 'handle'], function (NotifyUser $job, Application $app) {
            $job->handle($app->make(NotificationRepository::class));
        });

        $this->app->singleton(NotificationService::class, function ($app) {
            return new NotificationService(
                $app->make(NotificationRepository::class),
            );
        });


    }

    public function boot()
    {
        Schedule::job(new RemoveOldNotifications)->daily();
    }
}
