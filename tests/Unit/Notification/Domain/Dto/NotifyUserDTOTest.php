<?php

namespace Notification\Domain\Dto;

use App\Notification\Domain\Dto\NotifyUserDTO;
use App\Notification\Domain\Models\Notification;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class NotifyUserDTOTest extends TestCase
{
    private int $id;
    private int $userId;
    private string $eventName;
    private array $metadata;
    private bool $read;
    public Carbon $createdAt;

    private NotifyUserDTO $dto;

    protected function setUp(): void
    {
        $this->id = 1;
        $this->userId = 1;
        $this->eventName = 'test';
        $this->metadata = [];
        $this->read = false;
        $this->createdAt = Carbon::now();

        $this->dto = new NotifyUserDTO($this->id, $this->userId, $this->eventName, $this->metadata, $this->read, $this->createdAt->format('Y-m-d H:i:s'));
    }

    public function testToArrayReturnsCorrectStructure(): void
    {
        $expected = [
            'id' => $this->id,
            'user_id' => $this->userId,
            'event_name' => $this->eventName,
            'metadata' => $this->metadata,
            'read' => $this->read,
            'created_at' => $this->createdAt->format('Y-m-d H:i:s')
        ];

        $result = $this->dto->toArray();
        $this->assertEquals($expected, $result);
        $this->assertIsArray($result);
        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('user_id', $result);
        $this->assertArrayHasKey('event_name', $result);
        $this->assertArrayHasKey('metadata', $result);
        $this->assertArrayHasKey('read', $result);
        $this->assertArrayHasKey('created_at', $result);
    }

    public function testFromNotificationCreatesCorrectDTO(): void
    {
        $notification = new Notification($this->id, $this->userId, $this->eventName, $this->metadata, $this->read, $this->createdAt);

        $dto = NotifyUserDTO::fromNotification($notification);
        $this->assertInstanceOf(NotifyUserDTO::class, $dto);
        $this->assertEquals($this->id, $dto->id);
        $this->assertEquals($this->userId, $dto->userId);
        $this->assertEquals($this->eventName, $dto->eventName);
        $this->assertEquals($this->metadata, $dto->metadata);
        $this->assertEquals($this->read, $dto->read);
        $this->assertEquals($this->createdAt, $dto->createdAt);
    }
}
