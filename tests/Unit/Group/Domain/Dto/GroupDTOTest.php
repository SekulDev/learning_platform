<?php

namespace Group\Domain\Dto;

use App\Group\Domain\Dto\GroupDTO;
use App\Group\Domain\Models\Group;
use Tests\TestCase;

class GroupDTOTest extends TestCase
{
    private int $id;
    private string $name;
    private int $userId;

    private array $members;
    private GroupDTO $dto;

    protected function setUp(): void
    {
        $this->id = 1;
        $this->name = 'Test Group';
        $this->userId = 123;
        $this->members = [];
        $this->dto = new GroupDTO(
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

        $result = $this->dto->toArray();

        $this->assertEquals($expected, $result);
        $this->assertIsArray($result);
        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('name', $result);
        $this->assertArrayHasKey('user_id', $result);
        $this->assertArrayHasKey('members', $result);
    }

    public function testFromGroupCreatesCorrectDTO(): void
    {
        $group = new Group($this->id, $this->name, $this->userId);

        $dto = GroupDTO::fromGroup($group);

        $this->assertInstanceOf(GroupDTO::class, $dto);
        $this->assertEquals($this->id, $dto->id);
        $this->assertEquals($this->name, $dto->name);
        $this->assertEquals($this->userId, $dto->user_id);
    }
}
