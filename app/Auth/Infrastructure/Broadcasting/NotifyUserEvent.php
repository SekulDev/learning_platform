<?php

namespace App\Auth\Infrastructure\Broadcasting;

use App\Auth\Domain\Dto\NotifyUserDTO;
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

    public function broadcastOn()
    {
        return ['private-user-notify.' . $this->notifyUserDTO->userId];
    }

    public function broadcastAs()
    {
        return $this->notifyUserDTO->eventName;
    }

    public function broadcastWith()
    {
        return $this->notifyUserDTO->metadata;
    }
}
