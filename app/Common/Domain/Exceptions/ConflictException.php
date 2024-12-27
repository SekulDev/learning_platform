<?php

namespace App\Common\Domain\Exceptions;

class ConflictException extends HttpException
{
    private const STATUS_CODE = 409;

    public function __construct(string $message = 'Conflict')
    {
        parent::__construct($message, $this->getStatusCode());
    }

    public function getStatusCode(): int
    {
        return self::STATUS_CODE;
    }
}
