<?php

namespace Section\Application\Services;

use App\Auth\Domain\Dto\UserDTO;
use App\Auth\Domain\Models\User;
use App\Auth\Domain\Repositories\UserRepository;
use App\Auth\Infrastructure\Persistence\Repositories\Local\LocalUserRepository;
use App\Common\Domain\Exceptions\UnauthorizedException;
use App\Common\Domain\ValueObjects\Email;
use App\Common\Domain\ValueObjects\Password;
use App\Section\Application\Services\SectionService;
use App\Section\Domain\Dto\CreateLessonDTO;
use App\Section\Domain\Dto\CreateSectionDTO;
use App\Section\Domain\Dto\GetLessonPageDataDTO;
use App\Section\Domain\Dto\LessonDTO;
use App\Section\Domain\Dto\RemoveLessonDTO;
use App\Section\Domain\Dto\RemoveSectionDTO;
use App\Section\Domain\Dto\SectionDTO;
use App\Section\Domain\Dto\UpdateLessonDTO;
use App\Section\Domain\Models\Lesson;
use App\Section\Domain\Models\Section;
use App\Section\Domain\Repositories\SectionRepository;
use App\Section\Infrastructure\Persistence\Repositories\Local\LocalSectionRepository;
use Tests\TestCase;

class SectionServiceTest extends TestCase
{
    private SectionService $sectionService;
    private SectionRepository $sectionRepository;
    private UserRepository $userRepository;
    private User $adminUser;
    private User $regularUser;

    private Lesson $lesson;
    private Section $section;

    protected function setUp(): void
    {
        parent::setUp();
        $this->lesson = new Lesson(5, 'test lesson', 'test lesson content');

        $this->adminUser = new User(1, 'admin', new Email('admin@test.com'), Password::fromPlainText('password'), ['user', 'admin']);
        $this->regularUser = new User(2, 'user', new Email('user@test.com'), Password::fromPlainText('password'), ['user']);
        $this->section = new Section(1, 'Test Section', $this->adminUser->getId(), [$this->lesson]);

        $this->sectionRepository = new LocalSectionRepository();
        $this->userRepository = new LocalUserRepository();

        $this->userRepository->save($this->adminUser);
        $this->userRepository->save($this->regularUser);

        $this->sectionService = new SectionService($this->sectionRepository, $this->userRepository);
    }

    public function testCreateSection(): void
    {
        $createSectionDTO = new CreateSectionDTO(
            'New Section',
            UserDTO::fromUser($this->adminUser)
        );

        $result = $this->sectionService->createSection($createSectionDTO);

        $this->assertInstanceOf(SectionDTO::class, $result);
        $this->assertEquals('New Section', $result->name);
        $this->assertEquals($this->adminUser->getId(), $result->owner_id);
    }

    public function testCreateSectionThrowsExceptionForNonAdmin(): void
    {
        $createSectionDTO = new CreateSectionDTO(
            'New Section',
            UserDTO::fromUser($this->regularUser)
        );

        $this->expectException(UnauthorizedException::class);
        $this->sectionService->createSection($createSectionDTO);
    }

    public function testRemoveSection(): void
    {
        $this->sectionRepository->save($this->section);
        $removeSectionDTO = new RemoveSectionDTO(
            $this->section->getId(),
            UserDTO::fromUser($this->adminUser)
        );

        $this->sectionService->removeSection($removeSectionDTO);

        $this->assertNull($this->sectionRepository->findById($this->section->getId()));
    }

    public function testCreateLesson(): void
    {
        $this->sectionRepository->save($this->section);
        $createLessonDTO = new CreateLessonDTO(
            $this->section->getId(),
            'New Lesson',
            'Lesson Content',
            UserDTO::fromUser($this->adminUser)
        );

        $result = $this->sectionService->createLesson($createLessonDTO);

        $this->assertInstanceOf(LessonDTO::class, $result);
        $this->assertEquals('New Lesson', $result->title);
        $this->assertEquals('Lesson Content', $result->content);
    }

    public function testUpdateLesson(): void
    {
        $this->sectionRepository->save($this->section);
        $lesson = new Lesson(5, 'Original Title', 'Original Content');
        $this->sectionRepository->saveLesson($lesson, $this->section->getId());

        $updateLessonDTO = new UpdateLessonDTO(
            $this->section->getId(),
            $lesson->getId(),
            'Updated Title',
            'Updated Content',
            UserDTO::fromUser($this->adminUser)
        );

        $result = $this->sectionService->updateLesson($updateLessonDTO);

        $this->assertInstanceOf(LessonDTO::class, $result);
        $this->assertEquals('Updated Title', $result->title);
        $this->assertEquals('Updated Content', $result->content);
    }

    public function testRemoveLesson(): void
    {
        $this->sectionRepository->save($this->section);
        $lesson = new Lesson(5, 'Original Title', 'Original Content');
        $this->sectionRepository->saveLesson($lesson, $this->section->getId());

        $removeLessonDTO = new RemoveLessonDTO($this->section->getId(), $lesson->getId(), UserDTO::fromUser($this->adminUser));

        $this->sectionService->removeLesson($removeLessonDTO);

        $this->assertNull($this->sectionRepository->findLessonById($lesson->getId()));
        $this->assertEmpty($this->sectionRepository->findById($this->section->getId())->getLessons());
    }

    public function testGetOwnedSections(): void
    {
        $this->sectionRepository->save($this->section);
        $section2 = new Section(2, 'Another Section', $this->adminUser->getId());
        $this->sectionRepository->save($section2);

        $result = $this->sectionService->getOwnedSections($this->adminUser->getId());

        $this->assertIsArray($result);
        $this->assertCount(2, $result);
        $this->assertContainsOnlyInstancesOf(SectionDTO::class, $result);
    }

    public function testGetDataForLessonPageEditor(): void
    {
        $this->sectionRepository->save($this->section);

        $getLessonPageDataDTO = new GetLessonPageDataDTO($this->section->getId(), $this->lesson->getId(), UserDTO::fromUser($this->adminUser), true);

        $expected = [
            'section' => SectionDTO::fromSection($this->section),
            'lesson' => LessonDTO::fromLesson($this->lesson),
        ];

        $result = $this->sectionService->getDataForLessonPage($getLessonPageDataDTO);

        $this->assertEquals($expected, $result);
    }
}
