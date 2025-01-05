<?php

namespace Notification\Domain\Models;

use App\Notification\Domain\Models\Notification;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    private int $id;
    private int $userId;
    private string $eventName;
    private array $metadata;
    private bool $read;
    private Carbon $createdAt;

    private Notification $notification;

    protected function setUp(): void
    {
        $this->id = 1;
        $this->userId = 1;
        $this->eventName = 'test';
        $this->metadata = [];
        $this->read = false;
        $this->createdAt = Carbon::now();

        $this->notification = new Notification($this->id, $this->userId, $this->eventName, $this->metadata, $this->read, $this->createdAt);
    }

    public function testToArrayReturnsCorrectStructure(): void
    {
        $expected = [
            'id' => $this->id,
            'user_id' => $this->userId,
            'event_name' => $this->eventName,
            'metadata' => $this->metadata,
            'read' => $this->read,
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
        ];

        $result = $this->notification->toArray();

        $this->assertEquals($expected, $result);
        $this->assertIsArray($result);
        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('user_id', $result);
        $this->assertArrayHasKey('event_name', $result);
        $this->assertArrayHasKey('metadata', $result);
        $this->assertArrayHasKey('read', $result);
        $this->assertArrayHasKey('created_at', $result);
    }

    public function testMakeAsRead(): void
    {
        $this->notification->makeAsRead();

        $this->assertTrue($this->notification->getRead());
    }
}
