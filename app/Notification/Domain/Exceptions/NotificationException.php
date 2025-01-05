<?php

namespace App\Notification\Domain\Exceptions;

use App\Common\Domain\Exceptions\NotFoundException;

class NotificationException extends \Exception
{

    public static function notificationNotExists(): NotFoundException
    {
        return new NotFoundException("Notification does not exist");
    }
}
