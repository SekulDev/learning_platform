<?php

namespace App\Group\Application\Services;

use App\Auth\Domain\Dto\UserDTO;
use App\Auth\Domain\Repositories\UserRepository;
use App\Common\Domain\ValueObjects\Email;
use App\Group\Domain\Dto\AddMemberToGroupDTO;
use App\Group\Domain\Dto\CreateGroupDTO;
use App\Group\Domain\Dto\DeleteGroupDTO;
use App\Group\Domain\Dto\GroupDTO;
use App\Group\Domain\Dto\RemoveMemberFromGroupDTO;
use App\Group\Domain\Exceptions\GroupException;
use App\Group\Domain\Models\Group;
use App\Group\Domain\Repositories\GroupRepository;

class GroupService
{
    public function __construct(private GroupRepository $groupRepository, private UserRepository $userRepository)
    {
    }

    private function checkPermissions(int $userId, int $groupId): void
    {
        $user = $this->userRepository->findById($userId);
        if (!$user || !$user->isAdmin()) {
            throw GroupException::isNotAdmin();
        }

        $group = $this->groupRepository->findById($groupId);
        if (!$group) {
            throw GroupException::groupNotExists();
        }

        if ($group->getUserId() !== $user->getId()) {
            throw GroupException::isNotOwner();
        }

        return;
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
        $this->checkPermissions($deleteGroupDTO->user->id, $deleteGroupDTO->id);

        $group = $this->groupRepository->findById($deleteGroupDTO->id);
        $this->groupRepository->delete($group->getId());
    }

    public function addMemberToGroup(AddMemberToGroupDTO $addMemberDTO): void
    {
        $this->checkPermissions($addMemberDTO->user->id, $addMemberDTO->groupId);

        $user = $this->userRepository->findByEmail(new Email($addMemberDTO->userEmail));
        if (!$user) {
            throw GroupException::userNotExists();
        }

        $group = $this->groupRepository->findById($addMemberDTO->groupId);
        $group->addMember($user);
    }

    public function removeMemberFromGroup(RemoveMemberFromGroupDTO $removeMemberDTO): void
    {
        $this->checkPermissions($removeMemberDTO->user->id, $removeMemberDTO->groupId);

        $user = $this->userRepository->findById($removeMemberDTO->userId);
        if (!$user) {
            throw GroupException::userNotExists();
        }

        $group = $this->groupRepository->findById($removeMemberDTO->groupId);
        $group->removeMember($user);
    }

    public function getMembers(int $groupId, UserDTO $user): array
    {
        $this->checkPermissions($groupId, $user->id);

        $users = $this->groupRepository->getMembers($groupId);

        return array_map(function ($user) {
            return UserDTO::fromUser($user);
        }, $users);
    }
}
