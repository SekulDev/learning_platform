<?php

namespace App\Infrastructure\Http\Middleware;

use App\Domain\Dto\Auth\UserDTO;
use App\Domain\Exceptions\AuthenticationException;
use App\Domain\Repositories\UserRepository;
use App\Domain\Services\TokenService;
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
        try {
            $token = $this->extractToken($request);
            $payload = $this->tokenService->validateToken($token);

            if (!$payload) {
                throw AuthenticationException::invalidToken();
            }

            $user = $this->userRepository->findById($payload['sub']);
            if (!$user) {
                throw AuthenticationException::invalidToken();
            }

            auth()->setUser($user);
            return $next($request);
        } catch (AuthenticationException $e) {
            return response()->json(['error' => $e->getMessage()], 401);
        }
    }

    private function extractToken(Request $request): string
    {
        if (!$token = $request->bearerToken()) {
            throw AuthenticationException::invalidToken();
        }
        return $token;
    }
}
