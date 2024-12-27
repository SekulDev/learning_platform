<?php

namespace App\Group\Domain\Models;

use App\Common\Domain\AggregateRoot;

class Group extends AggregateRoot
{

    public function __construct(
        private int    $id,
        private string $name,
        private int    $user_id
    )
    {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'user_id' => $this->user_id
        ];
    }
}
