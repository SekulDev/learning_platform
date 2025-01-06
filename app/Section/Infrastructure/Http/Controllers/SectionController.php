<?php

namespace App\Section\Infrastructure\Http\Controllers;

use App\Auth\Domain\Dto\UserDTO;
use App\Common\Infrastructure\Http\Controllers\Controller;
use App\Section\Application\Services\SectionService;
use App\Section\Domain\Dto\CreateLessonDTO;
use App\Section\Domain\Dto\CreateSectionDTO;
use App\Section\Domain\Dto\GetLessonPageDataDTO;
use App\Section\Domain\Dto\RemoveLessonDTO;
use App\Section\Domain\Dto\RemoveSectionDTO;
use App\Section\Infrastructure\Http\Requests\CreateLessonRequest;
use App\Section\Infrastructure\Http\Requests\CreateSectionRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Inertia\Inertia;

class SectionController extends Controller
{
    public function __construct(private SectionService $sectionService)
    {
    }

    public function getOwnedSections(): JsonResponse
    {
        /** @var UserDTO $me */
        $me = auth()->user();

        $sections = $this->sectionService->getOwnedSections($me->id);

        return response()->json($sections);
    }

    public function removeSection(int $id): Response
    {
        /** @var UserDTO $me */
        $me = auth()->user();

        $this->sectionService->removeSection(new RemoveSectionDTO($id, $me));

        return response()->noContent();
    }

    public function createSection(CreateSectionRequest $request): JsonResponse
    {
        /** @var UserDTO $me */
        $me = auth()->user();

        $section = $this->sectionService->createSection(new CreateSectionDTO($request->name, $me));

        return response()->json($section);
    }

    public function createLesson(int $id, CreateLessonRequest $request): JsonResponse
    {
        /** @var UserDTO $me */
        $me = auth()->user();

        $lesson = $this->sectionService->createLesson(new CreateLessonDTO($id, $request->title, null, $me));

        return response()->json($lesson);
    }

    public function removeLesson(int $id, int $lessonId): Response
    {
        /** @var UserDTO $me */
        $me = auth()->user();

        $this->sectionService->removeLesson(new RemoveLessonDTO($id, $lessonId, $me));

        return response()->noContent();
    }

    public function showLessonEditorPage(int $id, int $lessonId)
    {
        /** @var UserDTO $me */
        $me = auth()->user();

        $data = $this->sectionService->getDataForLessonPage(new GetLessonPageDataDTO($id, $lessonId, $me, true));

        return Inertia::render('Section/LessonEdit', $data);
    }
}
