<?php

namespace App\Section\Domain\Exceptions;

use App\Common\Domain\Exceptions\NotFoundException;
use App\Common\Domain\Exceptions\UnauthorizedException;

class SectionException extends \Exception
{
    public static function isNotAdmin(): UnauthorizedException
    {
        return new UnauthorizedException("User is not admin");
    }

    public static function sectionNotExists(): NotFoundException
    {
        return new NotFoundException("Section does not exists");
    }

    public static function lessonNotExists(): NotFoundException
    {
        return new NotFoundException("Lesson does not exists");
    }

    public static function isNotOwner(): UnauthorizedException
    {
        return new UnauthorizedException("User is not owner");
    }
}
