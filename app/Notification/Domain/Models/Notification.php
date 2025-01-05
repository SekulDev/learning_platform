<?php

namespace App\Notification\Domain\Models;

use App\Common\Domain\AggregateRoot;
use Illuminate\Support\Carbon;

class Notification extends AggregateRoot
{

    public function __construct(
        private int             $id,
        private int             $userId,
        private string          $eventName,
        private array           $metadata,
        private bool            $read,
        private readonly Carbon $createdAt,

    )
    {
    }

    public function makeAsRead(): void
    {
        $this->read = true;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getEventName(): string
    {
        return $this->eventName;
    }

    public function getMetadata(): array
    {
        return $this->metadata;
    }

    public function getRead(): bool
    {
        return $this->read;
    }

    public function getCreatedAt(): Carbon
    {
        return $this->createdAt;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->userId,
            'event_name' => $this->eventName,
            'metadata' => $this->metadata,
            'read' => $this->read,
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
        ];
    }
}
