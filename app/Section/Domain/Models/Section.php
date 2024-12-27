<?php

namespace App\Section\Domain\Models;

use App\Common\Domain\AggregateRoot;

class Section extends AggregateRoot
{

    /**
     * @var Lesson[]
     */
    private array $lessons = [];

    public function __construct(
        private int    $id,
        private string $name,
        private int    $owner_id,
        array          $lessons = []
    )
    {
        $this->lessons = $lessons;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getLessons(): array
    {
        return $this->lessons;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getOwnerId(): int
    {
        return $this->owner_id;
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
