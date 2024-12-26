<?php

namespace Auth\Domain\Dto;

use App\Auth\Domain\Dto\AuthResponseDTO;
use Tests\TestCase;

class AuthResponseDTOTest extends TestCase
{
    public function testToArrayReturnsExpectedStructure(): void
    {
        $accessToken = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...';
        $tokenType = 'Bearer';
        $expiresIn = 3600;

        $dto = new AuthResponseDTO(
            accessToken: $accessToken,
            tokenType: $tokenType,
            expiresIn: $expiresIn
        );

        $result = $dto->toArray();

        $this->assertEquals([
            'access_token' => $accessToken,
            'token_type' => $tokenType,
            'expires_in' => $expiresIn
        ], $result);
    }

    public function testToArrayWithDefaultValues(): void
    {
        $accessToken = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...';
        $dto = new AuthResponseDTO($accessToken);

        $result = $dto->toArray();

        $this->assertEquals([
            'access_token' => $accessToken,
            'token_type' => 'Bearer',
            'expires_in' => 3600
        ], $result);
    }
}
