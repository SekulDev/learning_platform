<?php

namespace App\Common\Domain\Exceptions;

class ValidationException extends \Exception
{
    public static function invalidEmail(string $email): BadRequestException
    {
        return new BadRequestException("Invalid email format: {$email}");
    }

    public static function passwordTooShort(): BadRequestException
    {
        return new BadRequestException('Password must be at least 6 characters long');
    }
}
