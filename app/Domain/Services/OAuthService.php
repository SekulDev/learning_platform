<?php

namespace App\Domain\Services;

use App\Domain\Dto\Auth\UserDTO;

interface OAuthService
{
    public function getAuthorizationUrl(string $provider): string;
    public function handleCallback(string $provider, string $code): UserDTO;
}

