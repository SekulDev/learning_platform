<?php

namespace Auth\Infrastructure\Http\Middleware;

use App\Auth\Application\Services\JwtTokenService;
use App\Auth\Domain\Dto\UserDTO;
use App\Auth\Domain\Models\User;
use App\Auth\Domain\Repositories\UserRepository;
use App\Auth\Infrastructure\Http\Middleware\JwtAuthMiddleware;
use App\Auth\Infrastructure\Persistence\Repositories\Local\LocalUserRepository;
use App\Common\Domain\Exceptions\UnauthorizedException;
use App\Common\Domain\ValueObjects\Email;
use App\Common\Domain\ValueObjects\Password;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class JwtAuthMiddlewareTest extends TestCase
{
    private JwtTokenService $tokenService;
    private UserRepository $userRepository;
    private JwtAuthMiddleware $middleware;

    protected function setUp(): void
    {
        parent::setUp();
        $this->tokenService = new JwtTokenService('test-secret', 60 * 60 * 24);
        $this->userRepository = new LocalUserRepository();

        $this->middleware = new JwtAuthMiddleware($this->tokenService, $this->userRepository);
    }

    public function testSuccessfulAuthentication(): void
    {
        $userId = 1;
        $user = new User(
            id: $userId,
            name: 'John Doe',
            email: new Email('john@example.com'),
            password: Password::fromHash('hashed_password'),
            roles: ['user']
        );
        $this->userRepository->save($user);

        $payload = ['sub' => $userId, 'email' => 'john@example.com'];
        $token = $this->tokenService->createToken($payload);

        $request = new Request();
        $request->headers->set('Authorization', 'Bearer ' . $token);

        $nextCalled = false;
        $next = function ($request) use (&$nextCalled) {
            $nextCalled = true;
            return 'next_middleware_response';
        };

        $response = $this->middleware->handle($request, $next);

        $this->assertTrue($nextCalled);
        $this->assertEquals('next_middleware_response', $response);
    }

    public function testMissingBearerToken(): void
    {
        $request = new Request();

        $next = function () {
            return 'next_middleware_response';
        };

        $this->expectException(UnauthorizedException::class);

        $this->middleware->handle($request, $next);
    }

    public function testInvalidAuthorizationFormat(): void
    {
        $request = new Request();
        $request->headers->set('Authorization', 'InvalidFormat token123');

        $next = function () {
            return 'next_middleware_response';
        };

        $this->expectException(UnauthorizedException::class);

        $this->middleware->handle($request, $next);
    }

    public function testInvalidToken(): void
    {
        $request = new Request();
        $request->headers->set('Authorization', 'Bearer invalid.jwt.token');

        $next = function () {
            return 'next_middleware_response';
        };

        $this->expectException(UnauthorizedException::class);

        $this->middleware->handle($request, $next);
    }

    public function testExpiredToken(): void
    {
        $expiredTokenService = new JwtTokenService('test-secret', -3600); // negative TTL for expired token
        $this->middleware = new JwtAuthMiddleware($expiredTokenService, $this->userRepository);

        $userId = 1;
        $payload = ['sub' => $userId, 'email' => 'john@example.com'];
        $token = $expiredTokenService->createToken($payload);

        $request = new Request();
        $request->headers->set('Authorization', 'Bearer ' . $token);

        $next = function () {
            return 'next_middleware_response';
        };

        $this->expectException(UnauthorizedException::class);

        $this->middleware->handle($request, $next);
    }

    public function testUserNotFound(): void
    {
        $userId = 1;
        $payload = ['sub' => $userId, 'email' => 'john@example.com'];
        $token = $this->tokenService->createToken($payload);

        $request = new Request();
        $request->headers->set('Authorization', 'Bearer ' . $token);

        $next = function () {
            return 'next_middleware_response';
        };

        $this->expectException(UnauthorizedException::class);

        $this->middleware->handle($request, $next);
    }

    public function testUserIsProperlySetInAuthFacade(): void
    {
        $userId = 1;
        $user = new User(
            id: $userId,
            name: 'John Doe',
            email: new Email('john@example.com'),
            password: Password::fromHash('hashed_password'),
            roles: ['user']
        );
        $this->userRepository->save($user);


        $payload = ['sub' => $userId, 'email' => 'john@example.com'];
        $token = $this->tokenService->createToken($payload);

        $request = new Request();
        $request->headers->set('Authorization', 'Bearer ' . $token);

        Auth::shouldReceive('setUser')
            ->once()
            ->with(\Mockery::type(UserDTO::class));

        $next = function () {
            return 'next_middleware_response';
        };

        $this->middleware->handle($request, $next);
        // Verification is handled by the mock expectations
    }

}
