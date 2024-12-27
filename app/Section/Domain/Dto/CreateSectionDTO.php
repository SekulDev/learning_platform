<?php

namespace App\Section\Domain\Dto;

use App\Auth\Domain\Dto\UserDTO;

readonly class CreateSectionDTO
{
    public function __construct(
        public readonly string  $name,
        public readonly UserDTO $user,
    )
    {
    }
}
