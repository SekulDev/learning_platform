<?php

namespace App\Common\Domain\Exceptions;

class BadRequestException extends HttpException
{
    private const STATUS_CODE = 400;

    public function __construct(string $message = 'Bad Request')
    {
        parent::__construct($message, $this->getStatusCode());
    }

    public function getStatusCode(): int
    {
        return self::STATUS_CODE;
    }
}
