<?php

namespace App\Section\Domain\Dto;

use App\Section\Domain\Models\Lesson;

class LessonDTO
{
    public function __construct(
        public readonly int    $id,
        public readonly string $title,
        public readonly array  $content,
    )
    {
    }

    public static function fromLesson(Lesson $lesson): self
    {
        return new self(
            $lesson->getId(),
            $lesson->getTitle(),
            $lesson->getContent(),
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
        ];
    }
}
