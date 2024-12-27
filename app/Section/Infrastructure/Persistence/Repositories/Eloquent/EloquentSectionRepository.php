<?php

namespace App\Section\Infrastructure\Persistence\Repositories\Eloquent;

use App\Section\Domain\Models\Lesson;
use App\Section\Domain\Models\Section;
use App\Section\Domain\Repositories\SectionRepository;
use App\Section\Infrastructure\Persistence\LessonModel;
use App\Section\Infrastructure\Persistence\SectionModel;

class EloquentSectionRepository implements SectionRepository
{

    public function findById(int $id): ?Section
    {
        $model = SectionModel::with(['lessons'])->find($id);
        return $model ? $model->toSection() : null;
    }

    public function findLessonById(int $lessonId): ?Lesson
    {
        $model = LessonModel::find($lessonId);
        return $model ? $model->toLesson() : null;
    }

    public function delete(int $id): bool
    {
        SectionModel::destroy($id);
        return true;
    }

    public function save(Section $section): Section
    {
        $model = $section->getId() ? SectionModel::with(['lessons'])->find($section->getId()) : new SectionModel();

        $model->fill([
            'name' => $section->getName(),
            'owner_id' => $section->getOwnerId(),
        ]);

        $model->save();

        return $model->toSection();
    }

    public function saveLesson(Lesson $lesson, int $sectionId): Lesson
    {
        $sectionModel = SectionModel::findOrFail($sectionId);

        $model = $lesson->getId() ? LessonModel::find($lesson->getId()) : new LessonModel();
        $model->fill([
            'title' => $lesson->getTitle(),
            'content' => $lesson->getContent(),
        ]);

        $sectionModel->lessons()->save($model);

        return $model->toLesson();
    }

    public function findSections(int $userId): array
    {
        $sections = SectionModel::with(['lessons'])->where('owner_id', $userId)->get();

        return array_map(fn($section) => $section->toSection(), $sections->toArray());
    }
}
