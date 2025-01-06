<?php

namespace App\Section\Domain\Dto;

use App\Auth\Domain\Dto\UserDTO;

readonly class RemoveLessonDTO
{
    public function __construct(
        public readonly int     $sectionId,
        public readonly int     $lessonId,
        public readonly UserDTO $user,
    )
    {
    }
}
