<?php

namespace App\Media\Infrastructure\Providers;

use App\Auth\Domain\Repositories\UserRepository;
use App\Media\Application\Services\MediaService;
use App\Media\Domain\Storage\MediaStorage;
use App\Media\Infrastructure\MediaStorage\S3MediaStorage;
use Illuminate\Support\ServiceProvider;

class MediaServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(MediaStorage::class, S3MediaStorage::class);

        $this->app->singleton(MediaService::class, function ($app) {
            return new MediaService(
                $app->make(MediaStorage::class),
                $app->make(UserRepository::class)
            );
        });
    }
}
