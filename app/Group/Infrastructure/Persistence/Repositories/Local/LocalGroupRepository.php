<?php

namespace App\Group\Infrastructure\Persistence\Repositories\Local;

use App\Group\Domain\Models\Group;
use App\Group\Domain\Repositories\GroupRepository;

class LocalGroupRepository implements GroupRepository
{

    private $groups = [];

    public function findById(int $id): ?Group
    {
        return $this->groups[$id] ?? null;
    }

    public function delete(int $id): bool
    {
        $this->groups[$id] = null;
        return true;
    }

    public function save(Group $group): Group
    {
        $this->groups[$group->getId()] = $group;
        return $group;
    }
}
