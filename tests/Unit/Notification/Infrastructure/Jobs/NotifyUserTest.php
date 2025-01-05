<?php

namespace Notification\Infrastructure\Jobs;

use App\Notification\Infrastructure\Jobs\NotifyUser;
use App\Notification\Infrastructure\Persistence\Repositories\Local\LocalNotificationRepository;
use Tests\TestCase;

class NotifyUserTest extends TestCase
{
    private LocalNotificationRepository $notificationRepository;
    private int $userId;
    private string $eventName;
    private array $metadata;

    private NotifyUser $notifyUser;

    public function setUp(): void
    {
        parent::setUp();
        $this->notificationRepository = new LocalNotificationRepository();
        $this->userId = 1;
        $this->eventName = 'test';
        $this->metadata = [
            'data' => 'test_data'
        ];

        $this->notifyUser = new NotifyUser($this->userId, $this->eventName, $this->metadata);
    }

    public function testNotifyUser(): void
    {
        $this->notifyUser->handle($this->notificationRepository);

        $notifications = $this->notificationRepository->findUserNotifications($this->userId);
        $this->assertCount(1, $notifications);
    }
}
