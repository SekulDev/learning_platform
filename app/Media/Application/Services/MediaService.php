<?php

namespace App\Media\Application\Services;

use App\Auth\Domain\Repositories\UserRepository;
use App\Media\Domain\Exceptions\MediaExceptions;
use App\Media\Domain\Storage\MediaStorage;
use Illuminate\Http\UploadedFile;

class MediaService
{
    public function __construct(private MediaStorage $mediaStorage, private UserRepository $userRepository)
    {
    }

    public function getUniqueName(string $originalName): string
    {
        $sanitizedName = preg_replace('/[^a-zA-Z0-9\-_\.]/', '_', $originalName);

        $sanitizedName = preg_replace('/_+/', '_', $sanitizedName);

        $sanitizedName = trim($sanitizedName, '_');

        return uniqid() . "_" . $sanitizedName;
    }

    public function upload(int $userId, UploadedFile $file)
    {
        $user = $this->userRepository->findById($userId);
        if (!$user || !$user->isAdmin()) {
            throw MediaExceptions::isNotAdmin();
        }

        $name = $this->getUniqueName($file->getClientOriginalName());

        return $this->mediaStorage->upload($name, $file);
    }
}
