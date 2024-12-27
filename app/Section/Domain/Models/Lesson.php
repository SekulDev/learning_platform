<?php

namespace App\Section\Domain\Models;

use App\Common\Domain\AggregateRoot;

class Lesson extends AggregateRoot
{

    public function __construct(
        private int    $id,
        private string $title,
        private string $content,
    )
    {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function updateLesson(string $title, string $content): void
    {
        $this->content = $content;
        $this->title = $title;
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
