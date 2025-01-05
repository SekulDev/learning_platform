<?php

namespace App\Notification\Infrastructure\Broadcasting;

use App\Notification\Domain\Dto\NotifyUserDTO;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotifyUserEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(private readonly NotifyUserDTO $notifyUserDTO)
    {
    }

    public function broadcastOn(): array
    {
        return ['private-user-notify.' . $this->notifyUserDTO->userId];
    }

    public function broadcastAs(): string
    {
        return $this->notifyUserDTO->eventName;
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->notifyUserDTO->id,
            'metadata' => $this->notifyUserDTO->metadata
        ];
    }
}
