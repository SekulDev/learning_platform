<?php

namespace App\Auth\Domain\Services;

interface TokenStrategy
{
    public function createToken(array $payload): string;

    public function validateToken(string $token): ?array;
}
