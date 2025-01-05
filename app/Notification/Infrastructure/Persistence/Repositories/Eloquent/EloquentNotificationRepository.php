<?php

namespace App\Notification\Infrastructure\Persistence\Repositories\Eloquent;

use App\Notification\Domain\Models\Notification;
use App\Notification\Domain\Repositories\NotificationRepository;
use App\Notification\Infrastructure\Persistence\NotificationModel;

class EloquentNotificationRepository implements NotificationRepository
{

    public function findById(int $id): ?Notification
    {
        $model = NotificationModel::find($id);
        return $model ? $model->toNotification() : null;
    }

    public function deleteOldNotifications(int $days): void
    {
        NotificationModel::where('created_at', '<', now()->subDays($days))->delete();
    }

    public function save(Notification $notification): Notification
    {
        $model = $notification->getId() ? NotificationModel::find($notification->getId()) : new NotificationModel();

        $model->fill([
            'user_id' => $notification->getUserId(),
            'event_name' => $notification->getEventName(),
            'metadata' => $notification->getMetadata(),
            'read' => $notification->getRead()
        ]);

        $model->save();

        return $model->toNotification();
    }

    public function findUserNotifications(int $userId): array
    {
        $notifications = NotificationModel::where('user_id', '=', $userId)->orderBy('created_at', 'desc')->limit(10)->get();

        return $notifications->map(fn(NotificationModel $notification) => $notification->toNotification())->toArray();
    }

    public function readAllForUser(int $userId): void
    {
        NotificationModel::where('user_id', '=', $userId)->update(['read' => true]);
    }

}
