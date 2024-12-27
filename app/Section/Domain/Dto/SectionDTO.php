<?php

namespace App\Section\Domain\Dto;

use App\Section\Domain\Models\Section;

class SectionDTO
{
    public function __construct(
        public readonly int    $id,
        public readonly string $name,
        public readonly int    $owner_id,
        public readonly array  $lessons
    )
    {
    }

    public static function fromSection(Section $section): self
    {
        return new self(
            $section->getId(),
            $section->getName(),
            $section->getOwnerId(),
            array_map(fn($l) => LessonDTO::fromLesson($l), $section->getLessons())
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'owner_id' => $this->owner_id,
            'lessons' => array_map(fn($l) => $l->toArray(), $this->lessons)
        ];
    }
}
