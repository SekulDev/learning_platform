<?php

namespace App\Domain\ValueObjects;

use App\Domain\Exceptions\ValidationException;
use Illuminate\Support\Facades\Hash;

class Password
{

    private const MIN_PASSWORD_LENGTH = 6;
    private string $hashedValue;

    private function __construct(string $hashedValue)
    {
        $this->hashedValue = $hashedValue;
    }

    public static function fromPlainText(string $password): self
    {
        if (strlen($password) < self::MIN_PASSWORD_LENGTH) {
            throw ValidationException::passwordTooShort();
        }
        return new self(Hash::make($password));
    }

    public static function fromHash(string $hashedValue): self
    {
        return new self($hashedValue);
    }

    public function verify(string $plainText): bool
    {
        return Hash::check($plainText, $this->hashedValue);
    }

    public function value(): string
    {
        return $this->hashedValue;
    }
}
