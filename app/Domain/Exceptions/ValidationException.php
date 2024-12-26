<?php

namespace App\Domain\Exceptions;

class ValidationException extends \Exception
{
    public static function invalidEmail(string $email): self
    {
        return new self("Invalid email format: {$email}");
    }

    public static function passwordTooShort(): self
    {
        return new self('Password must be at least 6 characters long');
    }
}
