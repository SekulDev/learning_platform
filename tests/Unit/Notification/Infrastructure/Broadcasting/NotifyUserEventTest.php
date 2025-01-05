<?php

namespace Notification\Infrastructure\Broadcasting;

use App\Notification\Domain\Dto\NotifyUserDTO;
use App\Notification\Infrastructure\Broadcasting\NotifyUserEvent;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class NotifyUserEventTest extends TestCase
{
    private NotifyUserDTO $notifyUserDTO;
    private NotifyUserEvent $notifyUserEvent;

    protected function setUp(): void
    {
        parent::setUp();
        $this->notifyUserDTO = new NotifyUserDTO(1, 1, 'test', [
            'data' => 'test_data'
        ], false, Carbon::now()->format('Y-m-d H:i:s'));
        $this->notifyUserEvent = new NotifyUserEvent($this->notifyUserDTO);
    }

    public function testBroadcastOnCorrectChannels(): void
    {
        $expected = ['private-user-notify.' . $this->notifyUserDTO->userId];

        $result = $this->notifyUserEvent->broadcastOn();

        $this->assertEquals($expected, $result);
    }

    public function testBroadcastAsCorrectEvent(): void
    {
        $expected = $this->notifyUserDTO->eventName;

        $result = $this->notifyUserEvent->broadcastAs();

        $this->assertEquals($expected, $result);
    }

    public function testBroadcastWithCorrectMetadata(): void
    {
        $expected = [
            'id' => $this->notifyUserDTO->id,
            'metadata' => $this->notifyUserDTO->metadata,
        ];

        $result = $this->notifyUserEvent->broadcastWith();

        $this->assertEquals($expected, $result);
    }
}
