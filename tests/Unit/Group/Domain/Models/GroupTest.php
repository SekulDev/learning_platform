<?php

namespace Group\Domain\Models;

use App\Auth\Domain\Models\User;
use App\Common\Domain\Exceptions\ConflictException;
use App\Common\Domain\ValueObjects\Email;
use App\Common\Domain\ValueObjects\Password;
use App\Group\Domain\Models\Group;
use Tests\TestCase;

class GroupTest extends TestCase
{
    private int $id;
    private string $name;
    private int $userId;

    private array $members;
    private Group $group;

    protected function setUp(): void
    {
        $this->id = 1;
        $this->name = 'Test Group';
        $this->userId = 123;
        $this->members = [];
        $this->group = new Group(
            $this->id,
            $this->name,
            $this->userId,
            $this->members
        );
    }

    public function testToArrayReturnsCorrectStructure(): void
    {
        $expected = [
            'id' => $this->id,
            'name' => $this->name,
            'user_id' => $this->userId,
            'members' => $this->members
        ];

        $result = $this->group->toArray();

        $this->assertEquals($expected, $result);
        $this->assertIsArray($result);
        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('name', $result);
        $this->assertArrayHasKey('user_id', $result);
        $this->assertArrayHasKey('members', $result);
    }

    public function testAddMemberSuccessfully(): void
    {
        $user = new User(5, 'test', new Email('test@test.com'), Password::fromPlainText('password'), ['user']);

        $this->group->addMember($user);

        $this->assertTrue(in_array($user->getId(), $this->group->getMembers()));
    }

    public function testAddMemberThrowsExceptionWhenUserAlreadyInGroup(): void
    {
        $user = new User(5, 'test', new Email('test@test.com'), Password::fromPlainText('password'), ['user']);
        $this->group->addMember($user);

        $this->expectException(ConflictException::class);
        $this->group->addMember($user);
    }

    public function testRemoveMemberSuccessfully(): void
    {
        $user = new User(5, 'test', new Email('test@test.com'), Password::fromPlainText('password'), ['user']);
        $this->group->addMember($user);

        $this->group->removeMember($user);

        $this->assertFalse(in_array($user->getId(), $this->group->getMembers()));
    }

    public function testRemoveMemberWhenUserNotInGroup(): void
    {
        $startMembers = $this->group->getMembers();
        $user = new User(5, 'test', new Email('test@test.com'), Password::fromPlainText('password'), ['user']);
        $this->group->removeMember($user);

        $this->assertEquals($this->group->getMembers(), $startMembers);
    }
}
