<?php

namespace App\Common\Domain\Exceptions;

class UnauthorizedException extends HttpException
{
    public const STATUS_CODE = 401;

    public function __construct(string $message = 'Unauthorized')
    {
        parent::__construct($message, self::STATUS_CODE);
    }

    public function getStatusCode(): int
    {
        return self::STATUS_CODE;
    }
}
