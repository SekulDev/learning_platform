<?php

namespace App\Group\Domain\Repositories;

use App\Group\Domain\Models\Group;

interface GroupRepository
{
    public function findById(int $id): ?Group;

    public function delete(int $id): bool;

    public function save(Group $group): Group;
}
