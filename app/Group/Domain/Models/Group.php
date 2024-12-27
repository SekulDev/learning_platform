<?php

namespace App\Group\Domain\Models;

use App\Auth\Domain\Models\User;
use App\Common\Domain\AggregateRoot;
use App\Group\Domain\Exceptions\GroupException;

class Group extends AggregateRoot
{

    public function __construct(
        private int    $id,
        private string $name,
        private int    $user_id,
        private array  $members = []
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

    public function getMembers(): array
    {
        return $this->members;
    }

    public function addMember(User $user): void
    {
        if (in_array($user->getId(), $this->members, true)) {
            throw GroupException::userAlreadyIsInGroup();
        }

        $this->members[] = $user->getId();
    }

    public function removeMember(User $user): void
    {
        $this->members = array_filter($this->members, fn($u) => $u !== $user->getId());
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
}
