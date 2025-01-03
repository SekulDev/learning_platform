<?php

namespace App\Group\Domain\Repositories;

use App\Auth\Domain\Models\User;
use App\Group\Domain\Models\Group;

interface GroupRepository
{
    public function findById(int $id): ?Group;

    public function delete(int $id): bool;

    public function save(Group $group): Group;

    /**
     * @return User[]
     */
    public function getMembers(int $groupId): array;


    /**
     * @param int $userId
     * @return Group[]
     */
    public function findByMemberId(int $userId): array;

    /**
     * @param int $userId
     * @return Group[]
     */
    public function findByOwnerId(int $userId): array;
}
