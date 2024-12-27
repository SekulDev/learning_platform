<?php

namespace Auth\Application\Services;

use App\Auth\Application\Services\JwtTokenStrategy;
use Tests\TestCase;

class JwtTokenStrategyTest extends TestCase
{
    private JwtTokenStrategy $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new JwtTokenStrategy('test-secret', 3600);
    }

    public function testCreateAndValidateToken(): void
    {
        $payload = ['sub' => 1, 'email' => 'test@example.com'];

        $token = $this->service->createToken($payload);
        $this->assertIsString($token);

        $decodedPayload = $this->service->validateToken($token);
        $this->assertEquals($payload['sub'], $decodedPayload['sub']);
        $this->assertEquals($payload['email'], $decodedPayload['email']);
    }
}
