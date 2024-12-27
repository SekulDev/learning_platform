<?php

namespace App\Group\Application\Services;

use App\Auth\Domain\Repositories\UserRepository;
use App\Group\Domain\Dto\CreateGroupDTO;
use App\Group\Domain\Dto\DeleteGroupDTO;
use App\Group\Domain\Dto\GroupDTO;
use App\Group\Domain\Exceptions\GroupException;
use App\Group\Domain\Models\Group;
use App\Group\Domain\Repositories\GroupRepository;

class GroupService
{
    public function __construct(private GroupRepository $groupRepository, private UserRepository $userRepository)
    {
    }

    public function createGroup(CreateGroupDTO $createGroupDTO): GroupDTO
    {
        $user = $this->userRepository->findById($createGroupDTO->user->id);
        if (!$user || !$user->isAdmin()) {
            throw GroupException::isNotAdmin();
        }

        $group = new Group(0, $createGroupDTO->name, $user->getId());

        $group = $this->groupRepository->save($group);
        return GroupDTO::fromGroup($group);
    }

    public function deleteGroup(DeleteGroupDTO $deleteGroupDTO): void
    {
        $user = $this->userRepository->findById($deleteGroupDTO->user->id);
        if (!$user || !$user->isAdmin()) {
            throw GroupException::isNotAdmin();
        }

        $group = $this->groupRepository->findById($deleteGroupDTO->id);
        if (!$group) {
            throw GroupException::groupNotExists();
        }

        if ($group->getUserId() !== $user->getId()) {
            throw GroupException::isNotOwner();
        }

        $this->groupRepository->delete($group->getId());
    }
}
