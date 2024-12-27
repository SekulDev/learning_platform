<?php

namespace App\Auth\Infrastructure\Http\Middleware;

use App\Auth\Domain\Dto\UserDTO;
use App\Auth\Domain\Exceptions\AuthenticationException;
use App\Auth\Domain\Repositories\UserRepository;
use App\Auth\Domain\Services\TokenStrategy;
use Closure;
use Illuminate\Http\Request;

class JwtAuthMiddleware
{
    public function __construct(
        private TokenStrategy  $tokenService,
        private UserRepository $userRepository
    )
    {
    }

    public function handle(Request $request, Closure $next)
    {
        $token = $this->extractToken($request);
        $payload = $this->tokenService->validateToken($token);

        if (!$payload) {
            throw AuthenticationException::invalidToken();
        }

        $user = $this->userRepository->findById($payload['sub']);
        if (!$user) {
            throw AuthenticationException::invalidToken();
        }

        $this->setUser(UserDTO::fromUser($user));
        return $next($request);
    }

    protected function setUser(UserDTO $user): void
    {
        auth()->setUser($user);
    }

    protected function extractToken(Request $request): string
    {
        $token = $request->bearerToken();
        if (!$token) {
            throw AuthenticationException::invalidToken();
        }
        return $token;
    }
}
