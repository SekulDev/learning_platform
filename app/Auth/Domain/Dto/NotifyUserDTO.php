<?php

namespace App\Auth\Domain\Dto;

readonly class NotifyUserDTO
{
    public function __construct(
        public readonly int    $userId,
        public readonly string $eventName,
        public readonly array  $metadata
    )
    {
    }
}
