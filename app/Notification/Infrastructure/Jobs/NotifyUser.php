<?php

namespace App\Notification\Infrastructure\Jobs;

use App\Notification\Domain\Dto\NotifyUserDTO;
use App\Notification\Domain\Models\Notification;
use App\Notification\Domain\Repositories\NotificationRepository;
use App\Notification\Infrastructure\Broadcasting\NotifyUserEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Carbon;

class NotifyUser implements ShouldQueue
{
    use Queueable;

    public function __construct(private int $userId, private string $eventName, private array $metadata)
    {

    }

    public function handle(NotificationRepository $notificationRepository)
    {
        $notification = new Notification(0, $this->userId, $this->eventName, $this->metadata, false, Carbon::now());

        $notification = $notificationRepository->save($notification);

        event(new NotifyUserEvent(NotifyUserDTO::fromNotification($notification)));
    }
}
