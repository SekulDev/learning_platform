<?php

namespace App\Notification\Domain\Dto;

use App\Notification\Domain\Models\Notification;

readonly class NotifyUserDTO
{
    public function __construct(
        public readonly int    $id,
        public readonly int    $userId,
        public readonly string $eventName,
        public readonly array  $metadata,
        public readonly bool   $read,
        public string          $createdAt
    )
    {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->userId,
            'event_name' => $this->eventName,
            'metadata' => $this->metadata,
            'read' => $this->read,
            'created_at' => $this->createdAt
        ];
    }

    public static function fromNotification(Notification $notification): self
    {
        return new self(
            $notification->getId(),
            $notification->getUserId(),
            $notification->getEventName(),
            $notification->getMetadata(),
            $notification->getRead(),
            $notification->getCreatedAt()->format('Y-m-d H:i:s')
        );
    }
}
