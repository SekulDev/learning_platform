<?php

namespace Common\Domain\ValueObjects;

use App\Common\Domain\Exceptions\BadRequestException;
use App\Common\Domain\ValueObjects\Email;
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
        $this->expectException(BadRequestException::class);
        new Email('invalid-email');
    }
}
