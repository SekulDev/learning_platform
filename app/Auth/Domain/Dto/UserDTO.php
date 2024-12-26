<?php

namespace App\Auth\Domain\Dto;

use App\Auth\Domain\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;

class UserDTO implements Authenticatable
{

    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $email,
        public readonly array $roles,
        public readonly ?string $provider = null,
        public readonly ?string $providerId = null
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'roles' => $this->roles,
            'provider' => $this->provider,
            'providerId' => $this->providerId
        ];
    }

    public static function fromUser(User $user): self
    {
        return new self(
            id: $user->getId(),
            name: $user->getName(),
            email: $user->getEmail()->value(),
            roles: $user->getRoles(),
            provider: $user->getProvider(),
            providerId: $user->getProviderId()
        );
    }

    public function getAuthIdentifierName()
    {
        return 'id';
    }

    public function getAuthIdentifier()
    {
        return $this->id;
    }

    public function getAuthPasswordName()
    {
        return 'password';
    }

    public function getAuthPassword()
    {
        return null;
    }

    public function getRememberToken()
    {
        return null;
    }

    public function setRememberToken($value)
    {
        return;
    }

    public function getRememberTokenName()
    {
        return null;
    }
}
