<?php

namespace App\Auth\Infrastructure\Http\Controllers;

use App\Auth\Application\Services\AuthService;
use App\Auth\Domain\Dto\UpdateUserDTO;
use App\Auth\Domain\Dto\UserDTO;
use App\Auth\Infrastructure\Http\Requests\LoginRequest;
use App\Auth\Infrastructure\Http\Requests\RegisterRequest;
use App\Auth\Infrastructure\Http\Requests\UpdateUserRequest;
use App\Common\Domain\Exceptions\BadRequestException;
use App\Common\Infrastructure\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class AuthController extends Controller
{
    public function __construct(
        private AuthService $authService
    )
    {
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $authResponse = $this->authService->authenticate(
            $request->email,
            $request->password
        );

        return response()->json($authResponse->toArray())->cookie($this->authService->setCookieForResponse($authResponse));
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $authResponse = $this->authService->register(
            $request->name,
            $request->email,
            $request->password
        );

        return response()->json($authResponse->toArray())->cookie($this->authService->setCookieForResponse($authResponse));
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

    public function handleProviderCallback(string $provider): RedirectResponse
    {
        if (!request()->has('code')) {
            throw new BadRequestException('Missing code');
        }
        $authResponse = $this->authService->authenticateWithOAuth(
            $provider,
            request()->get('code')
        );

        return response()->redirectTo('/')->cookie($this->authService->setCookieForResponse($authResponse));
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
        return response()->json(['message' => 'Successfully logged out'])->cookie('jwt', null, -1000);
    }

    public function update(UpdateUserRequest $request): JsonResponse
    {
        /** @var UserDTO $me */
        $me = auth()->user();

        $user = $this->authService->updateUser(new UpdateUserDTO($me->id, $request->name));

        return response()->json($user);
    }
}
