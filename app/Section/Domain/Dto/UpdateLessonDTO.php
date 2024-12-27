<?php

namespace App\Section\Domain\Dto;

use App\Auth\Domain\Dto\UserDTO;

readonly class UpdateLessonDTO
{
    public function __construct(
        public readonly int     $sectionId,
        public readonly int     $lessonId,
        public readonly string  $title,
        public readonly string  $content,
        public readonly UserDTO $user,
    )
    {
    }
}
