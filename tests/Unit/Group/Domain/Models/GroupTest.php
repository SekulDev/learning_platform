<?php

namespace Group\Domain\Models;

use App\Group\Domain\Models\Group;
use Tests\TestCase;

class GroupTest extends TestCase
{
    private int $id;
    private string $name;
    private int $userId;
    private Group $group;

    protected function setUp(): void
    {
        $this->id = 1;
        $this->name = 'Test Group';
        $this->userId = 123;
        $this->group = new Group(
            $this->id,
            $this->name,
            $this->userId
        );
    }

    public function testToArrayReturnsCorrectStructure(): void
    {
        $expected = [
            'id' => $this->id,
            'name' => $this->name,
            'user_id' => $this->userId,
        ];

        $result = $this->group->toArray();

        $this->assertEquals($expected, $result);
        $this->assertIsArray($result);
        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('name', $result);
        $this->assertArrayHasKey('user_id', $result);
    }
}
