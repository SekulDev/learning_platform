<?php

namespace App\Auth\Infrastructure\Jobs;

use App\Auth\Domain\Dto\NotifyUserDTO;
use App\Auth\Infrastructure\Broadcasting\NotifyUserEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class NotifyUser implements ShouldQueue
{
    use Queueable;

    public function __construct(private int $userId, private string $eventName, private array $metadata)
    {

    }

    public function handle()
    {
        event(new NotifyUserEvent(new NotifyUserDTO($this->userId, $this->eventName, $this->metadata)));
    }
}
