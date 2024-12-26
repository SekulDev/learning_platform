<?php

namespace App\Auth\Infrastructure\Http\Middleware;

use App\Auth\Domain\Exceptions\AuthenticationException;
use App\Auth\Domain\Repositories\UserRepository;
use App\Auth\Domain\Services\TokenService;
use Illuminate\Http\Request;


class WebJwtAuthMiddleware extends JwtAuthMiddleware
{

    public function __construct(TokenService $tokenService, UserRepository $userRepository)
    {
        parent::__construct($tokenService, $userRepository);
    }

    protected function extractToken(Request $request): string
    {
        $token = $request->cookie('jwt');
        if (!$token) {
            throw AuthenticationException::invalidToken();
        }
        return $token;
    }
}
