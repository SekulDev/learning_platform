<?php

namespace App\Domain\ValueObjects;

use App\Domain\Exceptions\ValidationException;

class Email
{
    private string $email;

    public function __construct(string $email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw ValidationException::invalidEmail($email);
        }
        $this->email = $email;
    }

    public function value(): string
    {
        return $this->email;

    }

    public function equals(Email $other): bool
    {
        return $this->value() === $other->value();
    }
}
