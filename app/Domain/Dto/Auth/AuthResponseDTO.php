<?php

namespace App\Domain\Dto\Auth;

readonly class AuthResponseDTO
{
    public function __construct(
        public string $accessToken,
        public string $tokenType = 'Bearer',
        public int    $expiresIn = 3600
    ) {}

    public function toArray(): array
    {
        return [
            'access_token' => $this->accessToken,
            'token_type' => $this->tokenType,
            'expires_in' => $this->expiresIn
        ];
    }

}
