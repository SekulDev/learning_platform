<?php

namespace App\Section\Domain\Repositories;

use App\Section\Domain\Models\Lesson;
use App\Section\Domain\Models\Section;

interface SectionRepository
{

    public function findById(int $id): ?Section;

    public function delete(int $id): bool;

    public function save(Section $section): Section;

    public function saveLesson(Lesson $lesson, int $sectionId): Lesson;

    public function findLessonById(int $lessonId): ?Lesson;

    /**
     * @param int $userId
     * @return Section[]
     */
    public function findSections(int $userId): array;

    public function deleteLesson(int $lessonId): bool;
}
