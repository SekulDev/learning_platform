<?php

namespace App\Auth\Application\Services;

use App\Auth\Domain\Services\TokenStrategy;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtTokenStrategy implements TokenStrategy
{

    public function __construct(
        private readonly string $secret,
        private readonly int    $ttl = 60 * 60 * 24 * 7
    )
    {
    }

    public function createToken(array $payload): string
    {
        $payload['iat'] = time();
        $payload['exp'] = time() + $this->ttl;

        return JWT::encode($payload, $this->secret, 'HS256');
    }

    public function validateToken(string $token): ?array
    {
        try {
            return (array)JWT::decode($token, new Key($this->secret, 'HS256'));
        } catch (\Exception $e) {
            return null;
        }
    }
}
