<?php

namespace App\Auth\Infrastructure\Http\Controllers;

use App\Auth\Application\Services\AuthService;
use App\Auth\Domain\Exceptions\AuthenticationException;
use App\Auth\Infrastructure\Http\Requests\LoginRequest;
use App\Common\Domain\Exceptions\ValidationException;
use App\Common\Infrastructure\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    public function __construct(
        private AuthService $authService
    ) {}

    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $authResponse = $this->authService->authenticate(
                $request->email,
                $request->password
            );

            return response()->json($authResponse->toArray())->cookie(cookie('jwt', $authResponse->accessToken, $authResponse->expiresIn));
        } catch (AuthenticationException|ValidationException $e) {
            return response()->json(['error' => $e->getMessage()], 401);
        }
    }

    public function redirectToProvider(string $provider)
    {
        try {
            $url = $this->authService->redirectToProvider($provider);
            return response()->redirectTo($url);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid provider'], 400);
        }
    }

    public function handleProviderCallback(string $provider): JsonResponse
    {
        try {
            $authResponse = $this->authService->authenticateWithOAuth(
                $provider,
                request()->get('code')
            );

            return response()->json($authResponse->toArray())->cookie(cookie('jwt', $authResponse->accessToken, $authResponse->expiresIn));
        } catch (AuthenticationException $e) {
            return response()->json(['error' => $e->getMessage()], 401);
        }
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
