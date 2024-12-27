<?php

namespace Group\Application;

use App\Auth\Domain\Dto\UserDTO;
use App\Auth\Domain\Models\User;
use App\Auth\Domain\Repositories\UserRepository;
use App\Auth\Infrastructure\Persistence\Repositories\Local\LocalUserRepository;
use App\Common\Domain\Exceptions\NotFoundException;
use App\Common\Domain\Exceptions\UnauthorizedException;
use App\Common\Domain\ValueObjects\Email;
use App\Common\Domain\ValueObjects\Password;
use App\Group\Application\Services\GroupService;
use App\Group\Domain\Dto\AddMemberToGroupDTO;
use App\Group\Domain\Dto\CreateGroupDTO;
use App\Group\Domain\Dto\DeleteGroupDTO;
use App\Group\Domain\Dto\RemoveMemberFromGroupDTO;
use App\Group\Domain\Models\Group;
use App\Group\Infrastructure\Persistence\Repositories\Local\LocalGroupRepository;
use Tests\TestCase;

class GroupServiceTest extends TestCase
{
    private LocalGroupRepository $groupRepository;
    private UserRepository $userRepository;
    private GroupService $groupService;

    private User $adminUser;
    private User $regularUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->groupRepository = new LocalGroupRepository();
        $this->userRepository = new LocalUserRepository();

        $this->groupService = new GroupService($this->groupRepository, $this->userRepository);

        $this->adminUser = new User(1, 'admin', new Email('admin@test.com'), Password::fromPlainText('password'), ['user', 'admin']);
        $this->regularUser = new User(2, 'user', new Email('user@test.com'), Password::fromPlainText('password'), ['user']);

        $this->userRepository->save($this->adminUser);
        $this->userRepository->save($this->regularUser);
    }

    public function testCreateGroupSuccessfully(): void
    {
        $createGroupDTO = new CreateGroupDTO(
            'Test Group',
            UserDTO::fromUser($this->adminUser)
        );

        $result = $this->groupService->createGroup($createGroupDTO);

        $this->assertEquals('Test Group', $result->name);
        $this->assertEquals($this->adminUser->getId(), $result->user_id);

        $savedGroup = $this->groupRepository->findById($result->id);
        $this->assertNotNull($savedGroup);
        $this->assertEquals('Test Group', $savedGroup->getName());
    }

    public function testCreateGroupFailsForNonAdminUser(): void
    {
        $createGroupDTO = new CreateGroupDTO(
            'Test Group',
            UserDTO::fromUser($this->regularUser)
        );

        $this->expectException(UnauthorizedException::class);
        $this->expectExceptionMessage('User is not admin');

        $this->groupService->createGroup($createGroupDTO);
    }

    public function testCreateGroupFailsForNonExistentUser(): void
    {
        $nonExistentUser = new User(999, 'not-exists', new Email('nonexistent@test.com'), Password::fromPlainText('password'), ['user']);
        $createGroupDTO = new CreateGroupDTO(
            'Test Group',
            UserDTO::fromUser($nonExistentUser)
        );

        $this->expectException(UnauthorizedException::class);
        $this->expectExceptionMessage('User is not admin');

        $this->groupService->createGroup($createGroupDTO);
    }

    public function testDeleteGroupSuccessfully(): void
    {
        $createGroupDTO = new CreateGroupDTO(
            'Test Group',
            UserDTO::fromUser($this->adminUser)
        );
        $createdGroup = $this->groupService->createGroup($createGroupDTO);

        $deleteGroupDTO = new DeleteGroupDTO(
            $createdGroup->id,
            UserDTO::fromUser($this->adminUser)
        );

        $this->groupService->deleteGroup($deleteGroupDTO);

        $deletedGroup = $this->groupRepository->findById($createdGroup->id);
        $this->assertNull($deletedGroup);
    }

    public function testDeleteGroupFailsForNonAdminUser(): void
    {
        $createGroupDTO = new CreateGroupDTO(
            'Test Group',
            UserDTO::fromUser($this->adminUser)
        );
        $createdGroup = $this->groupService->createGroup($createGroupDTO);

        $deleteGroupDTO = new DeleteGroupDTO(
            $createdGroup->id,
            UserDTO::fromUser($this->regularUser)
        );

        $this->expectException(UnauthorizedException::class);
        $this->expectExceptionMessage('User is not admin');

        $this->groupService->deleteGroup($deleteGroupDTO);
    }

    public function testDeleteGroupFailsForNonExistentGroup(): void
    {
        $deleteGroupDTO = new DeleteGroupDTO(
            999,
            UserDTO::fromUser($this->adminUser)
        );

        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage('Group does not exist');

        $this->groupService->deleteGroup($deleteGroupDTO);
    }

    public function testDeleteGroupFailsForNonOwner(): void
    {
        $anotherAdmin = new User(3, 'admin2', new Email('admin2@test.com'), Password::fromPlainText('password'), ['user', 'admin']);
        $this->userRepository->save($anotherAdmin);

        $createGroupDTO = new CreateGroupDTO(
            'Test Group',
            UserDTO::fromUser($this->adminUser)
        );
        $createdGroup = $this->groupService->createGroup($createGroupDTO);

        $deleteGroupDTO = new DeleteGroupDTO(
            $createdGroup->id,
            UserDTO::fromUser($anotherAdmin)
        );

        $this->expectException(UnauthorizedException::class);
        $this->expectExceptionMessage('User is not owner');

        $this->groupService->deleteGroup($deleteGroupDTO);
    }

    public function testCheckPermissionsThrowsWhenGroupNotExists(): void
    {
        $dto = new AddMemberToGroupDTO(999, 'user@test.com', UserDTO::fromUser($this->adminUser));

        $this->expectException(NotFoundException::class);

        $this->groupService->addMemberToGroup($dto);
    }

    public function testCheckPermissionsThrowsWhenNotOwner(): void
    {
        $groupId = 5;
        $group = new Group($groupId, 'test group', $this->adminUser->getId());
        $anotherAdmin = new User(3, 'admin2', new Email('admin2@test.com'), Password::fromPlainText('password'), ['user', 'admin']);
        $this->userRepository->save($anotherAdmin);

        $this->userRepository->save($anotherAdmin);
        $this->groupRepository->save($group);

        $dto = new AddMemberToGroupDTO($groupId, $this->regularUser->getEmail()->value(), UserDTO::fromUser($anotherAdmin));

        $this->expectException(UnauthorizedException::class);

        $this->groupService->addMemberToGroup($dto);
    }

    public function testAddMemberToGroup(): void
    {
        $group = new Group(1, 'test group', $this->adminUser->getId());
        $this->groupRepository->save($group);

        $dto = new AddMemberToGroupDTO(1, $this->regularUser->getEmail()->value(), UserDTO::fromUser($this->adminUser));

        $this->groupService->addMemberToGroup($dto);

        $updatedGroup = $this->groupRepository->findById(1);
        $this->assertContains($this->regularUser->getId(), $updatedGroup->getMembers());
    }

    public function testAddMemberToGroupThrowsWhenNotAdmin(): void
    {
        $group = new Group(1, 'test group', $this->regularUser->getId());
        $this->groupRepository->save($group);

        $dto = new AddMemberToGroupDTO(1, $this->regularUser->getEmail()->value(), UserDTO::fromUser($this->regularUser));

        $this->expectException(UnauthorizedException::class);

        $this->groupService->addMemberToGroup($dto);
    }

    public function testRemoveMemberFromGroup(): void
    {
        $group = new Group(1, 'test group', $this->adminUser->getId(), [$this->regularUser->getId()]);

        $this->groupRepository->save($group);

        $dto = new RemoveMemberFromGroupDTO(1, $this->regularUser->getId(), UserDTO::fromUser($this->adminUser));

        $this->groupService->removeMemberFromGroup($dto);

        $updatedGroup = $this->groupRepository->findById(1);
        $this->assertNotContains($this->regularUser->getId(), $updatedGroup->getMembers());
    }

    public function testGetMembers(): void
    {
        $group = new Group(1, 'test group', $this->adminUser->getId());
        $this->groupRepository->save($group);
        // only for tests
        $this->groupRepository->addMemberToGroup($group->getId(), $this->regularUser);

        $expected = [
            UserDTO::fromUser($this->regularUser),
        ];

        $result = $this->groupService->getMembers($group->getId(), UserDTO::fromUser($this->adminUser));

        $this->assertEquals($expected, $result);
    }
}
