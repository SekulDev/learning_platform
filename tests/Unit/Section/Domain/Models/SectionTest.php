<?php

namespace Section\Domain\Models;

use App\Section\Domain\Models\Lesson;
use App\Section\Domain\Models\Section;
use Tests\TestCase;

class SectionTest extends TestCase
{

    private int $id;
    private string $name;

    private string $owner_id;

    private array $lessons = [];

    private Section $section;

    protected function setUp(): void
    {
        $this->id = 1;
        $this->name = 'Test section';
        $this->owner_id = 1;
        $this->lessons = [
            new Lesson(
                1,
                "Test Lesson",
                "Test lesson content"
            )
        ];
        $this->section = new Section(
            $this->id,
            $this->name,
            $this->owner_id,
            $this->lessons
        );
    }

    public function testToArrayReturnsCorrectStructure(): void
    {
        $expected = [
            'id' => $this->id,
            'name' => $this->name,
            'owner_id' => $this->owner_id,
            'lessons' => array_map(fn($l) => $l->toArray(), $this->lessons)
        ];

        $result = $this->section->toArray();

        $this->assertEquals($expected, $result);
        $this->assertIsArray($result);
        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('name', $result);
        $this->assertArrayHasKey('owner_id', $result);
        $this->assertArrayHasKey('lessons', $result);
    }
}
