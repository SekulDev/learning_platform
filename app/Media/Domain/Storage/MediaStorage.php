<?php

namespace App\Media\Domain\Storage;

use Illuminate\Http\UploadedFile;

interface MediaStorage
{
    public function upload(string $fileName, UploadedFile $file): string;
}
