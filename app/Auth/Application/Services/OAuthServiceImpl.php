<?php

namespace App\Auth\Application\Services;

use App\Auth\Domain\Dto\UserDTO;
use App\Auth\Domain\Exceptions\AuthenticationException;
use App\Auth\Domain\Models\User;
use App\Auth\Domain\Repositories\UserRepository;
use App\Auth\Domain\Services\OAuthService;
use App\Common\Domain\ValueObjects\Email;
use Laravel\Socialite\Facades\Socialite;

class OAuthServiceImpl implements OAuthService
{
    public function __construct(
        private UserRepository $userRepository
    ) {}

    public function getAuthorizationUrl(string $provider): string
    {
        return Socialite::driver($provider)->stateless()->redirect()->getTargetUrl();
    }

    public function handleCallback(string $provider, string $code): UserDTO
    {
        try {
            $oauthUser = Socialite::driver($provider)->stateless()->user();

            $email = new Email($oauthUser->getEmail());
            $user = $this->userRepository->findByEmail($email);

            if (!$user) {
                $user = User::createFromOAuth(
                    name: $oauthUser->getName(),
                    email: $email,
                    provider: $provider,
                    providerId: $oauthUser->getId(),
                    roles: ['user']
                );
                $user = $this->userRepository->save($user);
            }

            return UserDTO::fromUser($user);
        } catch (\Exception $e) {
            throw AuthenticationException::oauthError($provider, $e->getMessage());
        }
    }
}
