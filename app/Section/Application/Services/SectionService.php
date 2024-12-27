<?php

namespace App\Section\Application\Services;

use App\Auth\Domain\Repositories\UserRepository;
use App\Section\Domain\Dto\CreateLessonDTO;
use App\Section\Domain\Dto\CreateSectionDTO;
use App\Section\Domain\Dto\LessonDTO;
use App\Section\Domain\Dto\RemoveSectionDTO;
use App\Section\Domain\Dto\SectionDTO;
use App\Section\Domain\Dto\UpdateLessonDTO;
use App\Section\Domain\Exceptions\SectionException;
use App\Section\Domain\Models\Lesson;
use App\Section\Domain\Models\Section;
use App\Section\Domain\Repositories\SectionRepository;

class SectionService
{
    public function __construct(
        private readonly SectionRepository $sectionRepository,
        private readonly UserRepository    $userRepository
    )
    {
    }

    private function checkPermissions(int $userId, int $sectionId): void
    {
        $user = $this->userRepository->findById($userId);
        if (!$user || !$user->isAdmin()) {
            throw SectionException::isNotAdmin();
        }

        $section = $this->sectionRepository->findById($sectionId);
        if (!$section) {
            throw SectionException::sectionNotExists();
        }

        if ($section->getOwnerId() !== $user->getId()) {
            throw SectionException::isNotOwner();
        }

        return;
    }

    public function createSection(CreateSectionDTO $createSectionDTO): SectionDTO
    {
        $user = $this->userRepository->findById($createSectionDTO->user->id);
        if (!$user || !$user->isAdmin()) {
            throw SectionException::isNotAdmin();
        }

        $section = new Section(0, $createSectionDTO->name, $user->getId());

        $section = $this->sectionRepository->save($section);

        return SectionDTO::fromSection($section);
    }

    public function removeSection(RemoveSectionDTO $removeSectionDTO): void
    {
        $this->checkPermissions($removeSectionDTO->user->id, $removeSectionDTO->sectionId);

        $section = $this->sectionRepository->findById($removeSectionDTO->sectionId);

        $this->sectionRepository->delete($section->getId());
    }

    public function createLesson(CreateLessonDTO $createLessonDTO): LessonDTO
    {
        $this->checkPermissions($createLessonDTO->user->id, $createLessonDTO->sectionId);

        $lesson = new Lesson(0, $createLessonDTO->title, $createLessonDTO->content ?? "");

        $lesson = $this->sectionRepository->saveLesson($lesson, $createLessonDTO->sectionId);

        return LessonDTO::fromLesson($lesson);
    }

    public function updateLesson(UpdateLessonDTO $updateLessonDTO): LessonDTO
    {
        $this->checkPermissions($updateLessonDTO->user->id, $updateLessonDTO->sectionId);

        $lesson = $this->sectionRepository->findLessonById($updateLessonDTO->lessonId);
        if (!$lesson) {
            throw SectionException::lessonNotExists();
        }

        $lesson->updateLesson($updateLessonDTO->title, $updateLessonDTO->content ?? "");

        $lesson = $this->sectionRepository->saveLesson($lesson, $updateLessonDTO->sectionId);

        return LessonDTO::fromLesson($lesson);
    }

    public function getSections(int $userId): array
    {
        $sections = $this->sectionRepository->findSections($userId);

        return array_map(fn($section) => SectionDTO::fromSection($section), $sections);
    }
}
