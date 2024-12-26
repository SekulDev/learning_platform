<?php

namespace App\Domain\Services;

interface TokenService
{
    public function createToken(array $payload): string;
    public function validateToken(string $token): ?array;
}
