<?php

namespace App\Media\Infrastructure\MediaStorage;

use App\Media\Domain\Exceptions\MediaExceptions;
use App\Media\Domain\Storage\MediaStorage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class S3MediaStorage implements MediaStorage
{

    public function upload(string $fileName, UploadedFile $file): string
    {
        $result = Storage::disk('s3')->putFileAs('/', $file, $fileName);
        if (!$result) {
            throw MediaExceptions::failedUpload();
        }

        return $this->getUrl($result);
    }

    public function getUrl(string $path): string
    {
        return Storage::url($path);
    }
}
