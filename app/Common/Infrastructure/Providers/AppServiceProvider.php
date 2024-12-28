<?php

namespace App\Common\Infrastructure\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        Broadcast::routes([
            'middleware' => ['web.auth'],
        ]);

        require base_path('routes/channels.php');

        Vite::prefetch(concurrency: 3);
    }
}
