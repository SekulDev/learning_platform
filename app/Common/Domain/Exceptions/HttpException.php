<?php

namespace App\Common\Domain\Exceptions;

abstract class HttpException extends \Exception
{
    public function __construct(string $message)
    {
        parent::__construct($message, $this->getStatusCode());
    }

    public abstract function getStatusCode(): int;
}
