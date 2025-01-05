<?php

namespace App\Notification\Domain\Repositories;

use App\Notification\Domain\Models\Notification;

interface NotificationRepository
{
    public function deleteOldNotifications(int $days): void;

    public function findById(int $id): ?Notification;

    public function save(Notification $notification): Notification;

    /**
     * @param int $userId
     * @return Notification[]
     */
    public function findUserNotifications(int $userId): array;

    public function readAllForUser(int $userId): void;

}
