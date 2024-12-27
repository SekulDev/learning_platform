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
use App\Group\Domain\Dto\CreateGroupDTO;
use App\Group\Domain\Dto\DeleteGroupDTO;
use App\Group\Domain\Repositories\GroupRepository;
use App\Group\Infrastructure\Persistence\Repositories\Local\LocalGroupRepository;
use Tests\TestCase;

class GroupServiceTest extends TestCase
{
    private GroupRepository $groupRepository;
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
}
