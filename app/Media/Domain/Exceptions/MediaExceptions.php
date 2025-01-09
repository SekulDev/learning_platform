<?php

namespace App\Media\Domain\Exceptions;

use App\Common\Domain\Exceptions\TooManyRequestsException;
use App\Common\Domain\Exceptions\UnauthorizedException;

class MediaExceptions extends \Exception
{
    public static function isNotAdmin(): UnauthorizedException
    {
        return new UnauthorizedException("User is not admin");
    }

    public static function failedUpload(): \Exception
    {
        return new \Exception("Failed to upload file to bucket");
    }

    public static function rateLimit(): TooManyRequestsException
    {
        return new TooManyRequestsException("Rate limited upload");
    }
}
