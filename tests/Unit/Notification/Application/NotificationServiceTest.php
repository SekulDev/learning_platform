<?php

namespace Notification\Application;

use App\Common\Domain\Exceptions\NotFoundException;
use App\Notification\Application\Services\NotificationService;
use App\Notification\Domain\Dto\NotifyUserDTO;
use App\Notification\Domain\Models\Notification;
use App\Notification\Infrastructure\Persistence\Repositories\Local\LocalNotificationRepository;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class NotificationServiceTest extends TestCase
{
    private LocalNotificationRepository $notificationRepository;
    private NotificationService $notificationService;
    private Notification $notification;

    protected function setUp(): void
    {
        $this->notificationRepository = new LocalNotificationRepository();
        $this->notificationService = new NotificationService($this->notificationRepository);
        $this->notification = new Notification(1, 1, 'test', [], false, Carbon::now());

        $this->notificationRepository->save($this->notification);
    }

    public function testGetUserUnreadedCount(): void
    {
        $this->notificationRepository->save(new Notification(2, 1, 'test', [], true, Carbon::now()));
        $this->notificationRepository->save(new Notification(3, 1, 'test', [], false, Carbon::now()));
        $userId = $this->notification->getUserId();

        $count = $this->notificationService->getUserUnreadedCount($userId);

        $this->assertEquals(2, $count);
    }

    public function testGetUserNotifications(): void
    {
        $userId = $this->notification->getUserId();
        $expected = [
            NotifyUserDTO::fromNotification($this->notification),
        ];

        $notifications = $this->notificationService->getUserNotifications($userId);

        $this->assertEquals($expected, $notifications);
    }

    public function testReadNotificationsSuccessfully(): void
    {
        $notifyId = $this->notification->getId();

        $this->notificationService->readNotification($notifyId);

        $updated = $this->notificationRepository->findById($notifyId);
        $this->assertTrue($updated->getRead());
    }

    public function testReadNotificationsFailsForNonExistentNotification(): void
    {
        $notifyId = 5;

        $this->expectException(NotFoundException::class);

        $this->notificationService->readNotification($notifyId);
    }

    public function testReadAllForUser(): void
    {
        $this->notificationRepository->save(new Notification(2, 1, 'test', [], true, Carbon::now()));
        $this->notificationRepository->save(new Notification(3, 1, 'test', [], false, Carbon::now()));
        $userId = $this->notification->getUserId();

        $this->notificationService->readAllForUser($userId);

        $unreadedCount = $this->notificationService->getUserUnreadedCount($userId);
        $this->assertEquals(0, $unreadedCount);
    }
}
