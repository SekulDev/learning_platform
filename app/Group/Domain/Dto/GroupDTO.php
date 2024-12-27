<?php

namespace App\Group\Domain\Dto;

use App\Group\Domain\Models\Group;

class GroupDTO
{
    public function __construct(
        public readonly int    $id,
        public readonly string $name,
        public readonly int    $user_id,
        public readonly array  $members = []
    )
    {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'user_id' => $this->user_id,
            'members' => $this->members
        ];
    }

    public static function fromGroup(Group $group): self
    {
        return new self(
            $group->getId(),
            $group->getName(),
            $group->getUserId(),
            $group->getMembers()
        );
    }
}
