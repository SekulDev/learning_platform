<?php

namespace App\Auth\Infrastructure\Http\Middleware;

use App\Auth\Domain\Dto\UserDTO;
use App\Auth\Domain\Exceptions\AuthenticationException;
use App\Auth\Domain\Repositories\UserRepository;
use App\Auth\Domain\Services\TokenService;
use Closure;
use Illuminate\Http\Request;

class JwtAuthMiddleware
{
    public function __construct(
        private TokenService $tokenService,
        private UserRepository $userRepository
    ) {}

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

        auth()->setUser(UserDTO::fromUser($user));
        return $next($request);
    }

    protected function extractToken(Request $request): string
    {
        if (!$token = $request->bearerToken()) {
            throw AuthenticationException::invalidToken();
        }
        return $token;
    }
}
