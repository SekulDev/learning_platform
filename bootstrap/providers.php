<?php

return [
    \App\Common\Infrastructure\Providers\AppServiceProvider::class,
    \App\Auth\Infrastructure\Providers\AuthServiceProvider::class,
    \App\Group\Infrastructure\Providers\GroupServiceProvider::class,
    \App\Section\Infrastructure\Providers\SectionServiceProvider::class,
    \App\Notification\Infrastructure\Providers\NotificationServiceProvider::class,
    \App\Media\Infrastructure\Providers\MediaServiceProvider::class
];
