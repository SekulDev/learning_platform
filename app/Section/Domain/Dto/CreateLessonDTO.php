<?php

namespace App\Section\Domain\Dto;

use App\Auth\Domain\Dto\UserDTO;

readonly class CreateLessonDTO
{
    public function __construct(
        public readonly int     $sectionId,
        public readonly string  $title,
        public readonly ?string $content,
        public readonly UserDTO $user,
    )
    {
    }
}
