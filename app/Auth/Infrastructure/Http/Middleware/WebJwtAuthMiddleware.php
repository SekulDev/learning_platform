<?php

namespace App\Auth\Infrastructure\Http\Middleware;

use App\Auth\Domain\Dto\UserDTO;
use App\Auth\Domain\Exceptions\AuthenticationException;
use App\Auth\Domain\Repositories\UserRepository;
use App\Auth\Domain\Services\TokenStrategy;
use Illuminate\Http\Request;
use Inertia\Inertia;


class WebJwtAuthMiddleware extends JwtAuthMiddleware
{

    public function __construct(TokenStrategy $tokenService, UserRepository $userRepository)
    {
        parent::__construct($tokenService, $userRepository);
    }

    protected function setUser(UserDTO $user): void
    {
        parent::setUser($user);
        Inertia::share([
            'auth' => [
                'user' => $user
            ]
        ]);
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
