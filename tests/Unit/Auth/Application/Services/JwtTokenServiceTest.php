<?php

namespace Auth\Application\Services;

use App\Auth\Application\Services\JwtTokenService;
use Tests\TestCase;

class JwtTokenServiceTest extends TestCase
{
    private JwtTokenService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new JwtTokenService('test-secret', 3600);
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
