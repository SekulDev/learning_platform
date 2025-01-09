<?php

namespace App\Common\Domain\Exceptions;

class TooManyRequestsException extends HttpException
{
    private const STATUS_CODE = 429;

    public function __construct(string $message = 'Too Many Requests')
    {
        parent::__construct($message, $this->getStatusCode());
    }

    public function getStatusCode(): int
    {
        return self::STATUS_CODE;
    }
}
