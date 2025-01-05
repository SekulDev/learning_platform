<?php

namespace Notification\Infrastructure\Jobs;

use App\Notification\Domain\Models\Notification;
use App\Notification\Infrastructure\Jobs\RemoveOldNotifications;
use App\Notification\Infrastructure\Persistence\Repositories\Local\LocalNotificationRepository;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class RemoveOldNotificationsTest extends TestCase
{
    private LocalNotificationRepository $notificationRepository;
    private RemoveOldNotifications $removeOldNotifications;
    private int $userId;

    public function setUp(): void
    {
        parent::setUp();
        $this->notificationRepository = new LocalNotificationRepository();
        $this->removeOldNotifications = new RemoveOldNotifications();
        $this->userId = 1;
    }

    public function testRemoveOldNotifications(): void
    {
        $this->notificationRepository->save(new Notification(1, $this->userId, 'test', [], true, Carbon::now()->subDays(-10)));
        $this->notificationRepository->save(new Notification(2, $this->userId, 'test', [], false, Carbon::now()->subDays(-8)));

        $this->removeOldNotifications->handle($this->notificationRepository);

        $notifications = $this->notificationRepository->findUserNotifications($this->userId);
        $this->assertEmpty($notifications);
    }
}
