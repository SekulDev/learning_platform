<?php

namespace App\Auth\Infrastructure\Http\Controllers;

use App\Auth\Application\Services\AuthService;
use App\Auth\Infrastructure\Http\Requests\LoginRequest;
use App\Common\Domain\Exceptions\BadRequestException;
use App\Common\Infrastructure\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    public function __construct(
        private AuthService $authService
    ) {}

    public function login(LoginRequest $request): JsonResponse
    {
        $authResponse = $this->authService->authenticate(
            $request->email,
            $request->password
        );

        return response()->json($authResponse->toArray())->cookie(cookie('jwt', $authResponse->accessToken, $authResponse->expiresIn));
    }

    public function redirectToProvider(string $provider)
    {
        try {
            $url = $this->authService->redirectToProvider($provider);
            return response()->redirectTo($url);
        } catch (\Exception $e) {
            throw new BadRequestException('Invalid provider');
        }
    }

    public function handleProviderCallback(string $provider): JsonResponse
    {
        if (!request()->has('code')) {
            throw new BadRequestException('Missing code');
        }
        $authResponse = $this->authService->authenticateWithOAuth(
            $provider,
            request()->get('code')
        );

        return response()->json($authResponse->toArray())->cookie(cookie('jwt', $authResponse->accessToken, $authResponse->expiresIn));
    }

    public function me(): JsonResponse
    {
        return response()->json(
            auth()->user()->toArray()
        );
    }

    public function logout(): JsonResponse
    {
        auth()->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }
}
