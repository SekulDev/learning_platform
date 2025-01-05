<?php

namespace App\Notification\Infrastructure\Jobs;

use App\Notification\Domain\Repositories\NotificationRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class RemoveOldNotifications implements ShouldQueue
{
    use Queueable;

    private const REMOVE_AFTER = 7;

    public function __construct()
    {

    }

    public function handle(NotificationRepository $notificationRepository): void
    {
        $notificationRepository->deleteOldNotifications(self::REMOVE_AFTER);
    }
}
