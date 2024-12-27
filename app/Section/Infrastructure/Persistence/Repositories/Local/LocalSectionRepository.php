<?php

namespace App\Section\Infrastructure\Persistence\Repositories\Local;

use App\Section\Domain\Models\Lesson;
use App\Section\Domain\Models\Section;
use App\Section\Domain\Repositories\SectionRepository;

class LocalSectionRepository implements SectionRepository
{

    private array $sections = [];
    private array $lessons = [];

    public function findById(int $id): ?Section
    {
        return $this->sections[$id] ?? null;
    }

    public function delete(int $id): bool
    {
        unset($this->sections[$id]);
        return true;
    }

    public function save(Section $section): Section
    {
        $this->sections[$section->getId()] = $section;
        return $section;
    }

    public function saveLesson(Lesson $lesson, int $sectionId): Lesson
    {
        $lessons = $this->sections[$sectionId]->getLessons();

        $found = false;

        for ($i = 0; $i < count($lessons); $i++) {
            if ($lessons[$i]->getId() === $lesson->getId()) {
                $this->sections[$sectionId]->getLessons()[$i] = $lesson;
                $found = true;
                break;
            }
        }

        if (!$found) {
            $this->sections[$sectionId]->getLessons()[] = $lesson;
        }

        return $lesson;
    }

    public function findLessonById(int $lessonId): ?Lesson
    {
        foreach ($this->sections as $section) {
            foreach ($section->getLessons() as $lesson) {
                if ($lesson->getId() === $lessonId) {
                    return $lesson;
                }
            }
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function findSections(int $userId): array
    {
        return array_filter($this->sections, fn(Section $section) => $section->getOwnerId() === $userId);
    }
}
