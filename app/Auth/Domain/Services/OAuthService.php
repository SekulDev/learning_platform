<?php

namespace App\Auth\Domain\Services;

use App\Auth\Domain\Dto\UserDTO;

interface OAuthService
{
    public function getAuthorizationUrl(string $provider): string;
    public function handleCallback(string $provider, string $code): UserDTO;
}

