<?php

namespace App\Notification\Application\Services;

use App\Notification\Domain\Dto\NotifyUserDTO;
use App\Notification\Domain\Exceptions\NotificationException;
use App\Notification\Domain\Models\Notification;
use App\Notification\Domain\Repositories\NotificationRepository;

class NotificationService
{
    public function __construct(private NotificationRepository $notificationRepository)
    {
    }

    public function getUserUnreadedCount(int $userId): int
    {
        $notifications = $this->notificationRepository->findUserNotifications($userId);
        $notifications = array_filter($notifications, fn(Notification $notification) => !$notification->getRead());

        return count($notifications);
    }

    public function getUserNotifications(int $userId): array
    {
        $notifications = $this->notificationRepository->findUserNotifications($userId);

        return array_values(array_map(fn(Notification $notification) => NotifyUserDTO::fromNotification($notification), $notifications));
    }

    public function readNotification(int $id): void
    {
        $notification = $this->notificationRepository->findById($id);
        if (!$notification) {
            throw NotificationException::notificationNotExists();
        }

        $notification->makeAsRead();

        $this->notificationRepository->save($notification);
    }

    public function readAllForUser(int $userId): void
    {
        $this->notificationRepository->readAllForUser($userId);
    }

}
