<?php

namespace Section\Domain\Dto;

use App\Section\Domain\Dto\LessonDTO;
use App\Section\Domain\Dto\SectionDTO;
use App\Section\Domain\Dto\SimpleLessonDTO;
use App\Section\Domain\Models\Lesson;
use App\Section\Domain\Models\Section;
use Tests\TestCase;

class SectionDTOTest extends TestCase
{
    private int $id;
    private string $name;

    private int $owner_id;

    private array $lessons = [];

    private SectionDTO $section;

    protected function setUp(): void
    {
        $this->id = 1;
        $this->name = 'test section';
        $this->owner_id = 1;
        $this->lessons = [
            new LessonDTO(1, "test lesson", ["test lesson content"]),
        ];

        $this->section = new SectionDTO($this->id, $this->name, $this->owner_id, $this->lessons);
    }

    public function testToArrayReturnsCorrectStructure(): void
    {
        $expected = [
            "id" => $this->id,
            "name" => $this->name,
            "owner_id" => $this->owner_id,
            "lessons" => array_map(function ($l) {
                return [
                    'id' => $l->id,
                    'title' => $l->title,
                    'content' => $l->content,
                ];
            }, $this->lessons)
        ];

        $result = $this->section->toArray();

        $this->assertEquals($expected, $result);
        $this->assertIsArray($result);
        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('name', $result);
        $this->assertArrayHasKey('owner_id', $result);
        $this->assertArrayHasKey('lessons', $result);
    }

    public function testFromSectionCreatesCorrectDTO(): void
    {
        $section = new Section($this->id, $this->name, $this->owner_id, [
            new Lesson(1, "test lesson", ["test lesson content"]),
        ]);
        $lessons = array_map(fn($lesson) => SimpleLessonDTO::fromLesson($lesson), $section->getLessons());

        $dto = SectionDTO::fromSection($section);

        $this->assertInstanceOf(SectionDTO::class, $dto);
        $this->assertEquals($section->getId(), $dto->id);
        $this->assertEquals($section->getName(), $dto->name);
        $this->assertEquals($section->getOwnerId(), $dto->owner_id);
        $this->assertEquals(array_map(fn($l) => $l->toArray(), $lessons), array_map(fn($l) => $l->toArray(), $dto->lessons));
    }
}
