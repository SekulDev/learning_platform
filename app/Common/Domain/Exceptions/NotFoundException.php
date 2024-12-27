<?php

namespace App\Common\Domain\Exceptions;

class NotFoundException extends HttpException
{
    public const STATUS_CODE = 404;

    public function __construct(string $message = "Not found")
    {
        parent::__construct($message, self::STATUS_CODE);
    }

    public function getStatusCode(): int
    {
        return self::STATUS_CODE;
    }
}
