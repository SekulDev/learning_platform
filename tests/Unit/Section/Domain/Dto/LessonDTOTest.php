<?php

namespace Section\Domain\Dto;

use App\Section\Domain\Dto\LessonDTO;
use App\Section\Domain\Models\Lesson;
use Tests\TestCase;

class LessonDTOTest extends TestCase
{
    private int $id;
    private string $title;

    private array $content;

    private LessonDTO $lesson;

    protected function setUp(): void
    {
        $this->id = 1;
        $this->title = 'Test lesson';
        $this->content = ['Test lesson content'];
        $this->lesson = new LessonDTO($this->id, $this->title, $this->content);
    }

    public function testToArrayReturnsCorrectStructure(): void
    {
        $expected = [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content
        ];

        $result = $this->lesson->toArray();

        $this->assertEquals($expected, $result);
        $this->assertIsArray($result);
        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('title', $result);
        $this->assertArrayHasKey('content', $result);
    }

    public function testFromLessonCreatesCorrectDTO(): void
    {
        $lesson = new Lesson($this->id, $this->title, $this->content);

        $dto = LessonDTO::fromLesson($lesson);

        $this->assertInstanceOf(LessonDTO::class, $dto);
        $this->assertEquals($lesson->getId(), $dto->id);
        $this->assertEquals($lesson->getTitle(), $dto->title);
        $this->assertEquals($lesson->getContent(), $dto->content);
    }
}
