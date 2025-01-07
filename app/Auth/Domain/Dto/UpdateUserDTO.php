<?php

namespace App\Auth\Domain\Dto;

readonly class UpdateUserDTO
{
    public function __construct(
        public readonly int    $userId,
        public readonly string $name,
    )
    {
    }

}
