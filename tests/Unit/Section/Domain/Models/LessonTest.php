<?php

namespace Section\Domain\Models;

use App\Section\Domain\Models\Lesson;
use Tests\TestCase;

class LessonTest extends TestCase
{

    private int $id;
    private string $title;

    private string $content;

    private Lesson $lesson;

    protected function setUp(): void
    {
        $this->id = 1;
        $this->title = 'Test Lesson';
        $this->content = 'Test Lesson content';
        $this->lesson = new Lesson(
            $this->id,
            $this->title,
            $this->content,
        );
    }

    public function testToArrayReturnsCorrectStructure(): void
    {
        $expected = [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
        ];

        $result = $this->lesson->toArray();

        $this->assertEquals($expected, $result);
        $this->assertIsArray($result);
        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('title', $result);
        $this->assertArrayHasKey('content', $result);
    }
}
