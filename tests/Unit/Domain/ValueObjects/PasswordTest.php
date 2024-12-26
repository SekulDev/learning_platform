<?php

namespace Domain\ValueObjects;

use App\Domain\Exceptions\ValidationException;
use App\Domain\ValueObjects\Password;
use Tests\TestCase;

class PasswordTest extends TestCase
{
    public function testCreateValidPassword(): void
    {
        $password = Password::fromPlainText('password123');
        $this->assertTrue($password->verify('password123'));
    }

    public function testCreateShortPasswordThrowsException(): void
    {
        $this->expectException(ValidationException::class);
        Password::fromPlainText('12345');
    }
}
