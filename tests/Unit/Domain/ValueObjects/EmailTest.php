<?php

namespace Domain\ValueObjects;

use App\Domain\Exceptions\ValidationException;
use App\Domain\ValueObjects\Email;
use Tests\TestCase;

class EmailTest extends TestCase
{
    public function testCreateValidEmail(): void
    {
        $email = new Email('test@example.com');
        $this->assertEquals('test@example.com', $email->value());
    }

    public function testCreateInvalidEmailThrowsException(): void
    {
        $this->expectException(ValidationException::class);
        new Email('invalid-email');
    }
}
