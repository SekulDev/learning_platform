<?php

namespace Common\Domain\ValueObjects;

use App\Common\Domain\Exceptions\BadRequestException;
use App\Common\Domain\ValueObjects\Password;
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
        $this->expectException(BadRequestException::class);
        Password::fromPlainText('12345');
    }
}
