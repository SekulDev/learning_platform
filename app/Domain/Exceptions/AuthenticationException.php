<?php

namespace App\Domain\Exceptions;

class AuthenticationException extends \Exception
{
    public static function invalidCredentials(): self
    {
        return new self('Invalid credentials provided');
    }

    public static function invalidToken(): self
    {
        return new self('Invalid or expired token');
    }

    public static function oauthError(string $provider, string $message = ''): self
    {
        return new self("Authentication failed with provider: {$provider}. {$message}");
    }
}
