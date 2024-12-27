<?php

namespace App\Group\Infrastructure\Persistence\Repositories\Local;

use App\Auth\Domain\Models\User;
use App\Group\Domain\Models\Group;
use App\Group\Domain\Repositories\GroupRepository;

class LocalGroupRepository implements GroupRepository
{

    private $groups = [];
    private $groupMembers = [];

    public function findById(int $id): ?Group
    {
        return $this->groups[$id] ?? null;
    }

    public function delete(int $id): bool
    {
        unset($this->groups[$id]);
        unset($this->groupMembers[$id]);
        return true;
    }

    public function save(Group $group): Group
    {
        $this->groups[$group->getId()] = $group;
        $this->groupMembers[$group->getId()] = $this->groupMembers[$group->getId()] ?? [];
        return $group;
    }

    public function getMembers(int $groupId): array
    {
        if (!isset($this->groups[$groupId])) {
            return [];
        }

        return $this->groupMembers[$groupId] ?? [];
    }

    public function addMemberToGroup(int $groupId, User $user): void
    {
        if (!isset($this->groups[$groupId])) {
            throw new \Exception("Group with ID {$groupId} does not exist.");
        }

        $this->groupMembers[$groupId][] = $user;
    }

    public function removeMemberFromGroup(int $groupId, int $userId): void
    {
        if (!isset($this->groups[$groupId]) || !isset($this->groupMembers[$groupId])) {
            throw new \Exception("Group with ID {$groupId} or its members do not exist.");
        }

        $this->groupMembers[$groupId] = array_filter(
            $this->groupMembers[$groupId],
            fn(User $member) => $member->getId() !== $userId
        );
    }


}
