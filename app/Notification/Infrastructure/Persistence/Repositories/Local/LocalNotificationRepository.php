<?php

namespace App\Notification\Infrastructure\Persistence\Repositories\Local;

use App\Notification\Domain\Models\Notification;
use App\Notification\Domain\Repositories\NotificationRepository;

class LocalNotificationRepository implements NotificationRepository
{

    private array $notifications = [];

    public function findById(int $id): ?Notification
    {
        return $this->notifications[$id] ?? null;
    }

    public function deleteOldNotifications(int $days): void
    {

        $this->notifications = array_filter($this->notifications, fn(Notification $notification) => $notification->getCreatedAt() < now()->subDays($days));
    }

    public function save(Notification $notification): Notification
    {
        $this->notifications[$notification->getId()] = $notification;

        return $notification;
    }

    public function findUserNotifications(int $userId): array
    {
        return array_filter($this->notifications, fn(Notification $notification) => $notification->getUserId() === $userId);
    }

    public function readAllForUser(int $userId): void
    {
        $this->notifications = array_map(function (Notification $notification) use ($userId) {
            if ($notification->getUserId() == $userId) {
                $notification->makeAsRead();
                return $notification;
            }
            return $notification;
        }, $this->notifications);
    }
}
