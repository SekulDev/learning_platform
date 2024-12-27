<?php

namespace App\Section\Domain\Dto;

use App\Auth\Domain\Dto\UserDTO;

readonly class RemoveSectionDTO
{
    public function __construct(
        public readonly int     $sectionId,
        public readonly UserDTO $user,
    )
    {
    }
}
