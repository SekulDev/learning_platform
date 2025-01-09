<?php

namespace Media\Application\Services;

use App\Auth\Domain\Models\User;
use App\Auth\Infrastructure\Persistence\Repositories\Local\LocalUserRepository;
use App\Common\Domain\Exceptions\UnauthorizedException;
use App\Common\Domain\ValueObjects\Email;
use App\Common\Domain\ValueObjects\Password;
use App\Media\Application\Services\MediaService;
use App\Media\Infrastructure\MediaStorage\FakeMediaStorage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MediaServiceTest extends TestCase
{
    private LocalUserRepository $userRepository;
    private FakeMediaStorage $mediaStorage;
    private MediaService $mediaService;

    private User $adminUser;
    private User $regularUser;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('s3');

        $this->userRepository = new LocalUserRepository();
        $this->mediaStorage = new FakeMediaStorage();
        $this->mediaService = new MediaService($this->mediaStorage, $this->userRepository);

        $this->adminUser = new User(1, 'admin', new Email('admin@test.com'), Password::fromPlainText('password'), ['user', 'admin']);
        $this->regularUser = new User(2, 'user', new Email('user@test.com'), Password::fromPlainText('password'), ['user']);

        $this->userRepository->save($this->adminUser);
        $this->userRepository->save($this->regularUser);
    }

    public function testItGeneratesCorrectsUniqueName()
    {
        $originalName = 'example file name.jpg';
        $uniqueName = $this->mediaService->getUniqueName($originalName);

        $this->assertMatchesRegularExpression('/^\w+_example_file_name\.jpg$/', $uniqueName);
    }

    public function testUploadFileForAdminUser()
    {
        $file = UploadedFile::fake()->image('test-image.jpg');

        $url = $this->mediaService->upload($this->adminUser->getId(), $file);

        $uploadedFileName = explode('/', $url);
        $uploadedFileName = end($uploadedFileName);
        Storage::disk('s3')->assertExists($uploadedFileName);
        $this->assertEquals(Storage::disk('s3')->url($uploadedFileName), $url);
    }

    public function testUploadFileForNonAdminUser()
    {
        $file = UploadedFile::fake()->image('test-image.jpg');

        $this->expectException(UnauthorizedException::class);

        $this->mediaService->upload($this->regularUser->getId(), $file);
    }

    public function testUploadFileForNonExistentUser()
    {
        $file = UploadedFile::fake()->image('test-image.jpg');

        $this->expectException(UnauthorizedException::class);

        $this->mediaService->upload(999, $file);
    }
}
