<?php

namespace App\Section\Domain\Dto;

use App\Section\Domain\Models\Lesson;

class SimpleLessonDTO
{
    public function __construct(
        public readonly int    $id,
        public readonly string $title,
    )
    {
    }

    public static function fromLesson(Lesson $lesson): self
    {
        return new self(
            $lesson->getId(),
            $lesson->getTitle(),
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
        ];
    }
}
