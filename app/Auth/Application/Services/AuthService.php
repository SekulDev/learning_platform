<?php

namespace App\Auth\Application\Services;

use App\Auth\Domain\Dto\AuthResponseDTO;
use App\Auth\Domain\Dto\UpdateUserDTO;
use App\Auth\Domain\Dto\UserDTO;
use App\Auth\Domain\Exceptions\AuthenticationException;
use App\Auth\Domain\Models\User;
use App\Auth\Domain\Repositories\UserRepository;
use App\Auth\Domain\Services\OAuthService;
use App\Auth\Domain\Services\TokenStrategy;
use App\Common\Domain\ValueObjects\Email;
use App\Common\Domain\ValueObjects\Password;
use Illuminate\Support\Facades\Cookie;

class AuthService
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly TokenStrategy  $tokenService,
        private readonly OAuthService   $oauthService
    )
    {
    }

    public function authenticate(string $email, string $password): AuthResponseDTO
    {
        $user = $this->userRepository->findByEmail(new Email($email));

        if (!$user || !$user->authenticate($password)) {
            throw AuthenticationException::invalidCredentials();
        }

        $token = $this->createTokenForUser(UserDTO::fromUser($user));
        return new AuthResponseDTO($token);
    }

    public function redirectToProvider(string $provider): string
    {
        return $this->oauthService->getAuthorizationUrl($provider);
    }

    public function authenticateWithOAuth(string $provider, string $code): AuthResponseDTO
    {
        $userDTO = $this->oauthService->handleCallback($provider, $code);
        $token = $this->createTokenForUser($userDTO);
        return new AuthResponseDTO($token);
    }

    public function register(string $name, string $email, string $password): AuthResponseDTO
    {
        $user = $this->userRepository->findByEmail(new Email($email));
        if ($user) {
            throw AuthenticationException::userExists($email);
        }

        $user = new User(0, $name, new Email($email), Password::fromPlaintext($password));
        $user = $this->userRepository->save($user);

        $token = $this->createTokenForUser(UserDTO::fromUser($user));
        return new AuthResponseDTO($token);
    }

    private function createTokenForUser(UserDTO $userDTO): string
    {
        return $this->tokenService->createToken([
            'sub' => $userDTO->id,
            'email' => $userDTO->email,
            'roles' => $userDTO->roles
        ]);
    }

    public function setCookieForResponse(AuthResponseDTO $authResponse): \Symfony\Component\HttpFoundation\Cookie
    {
        $HTTP_ONLY = false;
        $SECURE = false;


        return Cookie::make('jwt', $authResponse->accessToken, $authResponse->expiresIn, null, null, $SECURE, $HTTP_ONLY);
    }

    public function updateUser(UpdateUserDTO $updateUserDTO): UserDTO
    {
        $user = $this->userRepository->findById($updateUserDTO->userId);
        if (!$user) {
            throw AuthenticationException::userNotFound();
        }

        $user->update($updateUserDTO);

        $user = $this->userRepository->save($user);

        return UserDTO::fromUser($user);
    }
}
