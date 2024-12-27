<?php

namespace App\Group\Domain\Exceptions;

use App\Common\Domain\Exceptions\NotFoundException;
use App\Common\Domain\Exceptions\UnauthorizedException;

class GroupException extends \Exception
{
    public static function isNotAdmin(): UnauthorizedException
    {
        return new UnauthorizedException("User is not admin");
    }

    public static function isNotOwner(): UnauthorizedException
    {
        return new UnauthorizedException("User is not owner");
    }

    public static function groupNotExists(): NotFoundException
    {
        return new NotFoundException("Group does not exists");
    }
}
