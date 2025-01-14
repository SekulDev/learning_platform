<?php

namespace App\Auth\Domain\Exceptions;

use App\Common\Domain\Exceptions\BadRequestException;
use App\Common\Domain\Exceptions\UnauthorizedException;

class AuthenticationException extends \Exception
{
    public static function invalidCredentials(): UnauthorizedException
    {
        return new UnauthorizedException('Invalid credentials provided');
    }

    public static function invalidToken(): UnauthorizedException
    {
        return new UnauthorizedException('Invalid or expired token');
    }

    public static function oauthError(string $provider, string $message = ''): UnauthorizedException
    {
        return new UnauthorizedException("Authentication failed with provider: {$provider}. {$message}");
    }

    public static function userExists(string $email): UnauthorizedException
    {
        return new UnauthorizedException("User with email: {$email} already exists");
    }

    public static function userNotFound(): BadRequestException
    {
        return new BadRequestException('User not found');
    }
}
