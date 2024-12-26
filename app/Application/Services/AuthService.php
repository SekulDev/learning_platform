<?php

namespace App\Application\Services;

use App\Domain\Dto\Auth\AuthResponseDTO;
use App\Domain\Dto\Auth\UserDTO;
use App\Domain\Exceptions\AuthenticationException;
use App\Domain\Repositories\UserRepository;
use App\Domain\Services\OAuthService;
use App\Domain\Services\TokenService;
use App\Domain\ValueObjects\Email;

class AuthService
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly TokenService   $tokenService,
        private readonly OAuthService $oauthService
    ) {}

    public function authenticate(string $email, string $password): AuthResponseDTO
    {
        $user = $this->userRepository->findByEmail(new Email($email));

        if (!$user || !$user->getPassword()->verify($password)) {
            throw AuthenticationException::invalidCredentials();
        }

        $token = $this->createTokenForUser(UserDTO::fromEntity($user));
        return new AuthResponseDTO($token);
    }

    public function redirectToProvider(string $provider): string {
        return $this->oauthService->getAuthorizationUrl($provider);
    }

    public function authenticateWithOAuth(string $provider, string $code): AuthResponseDTO
    {
        $userDTO = $this->oauthService->handleCallback($provider, $code);
        $token = $this->createTokenForUser($userDTO);
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
}
