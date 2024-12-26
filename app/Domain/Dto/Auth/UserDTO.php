<?php

namespace App\Domain\Dto\Auth;

use App\Domain\Entities\User;

class UserDTO
{

    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $email,
        public readonly array $roles,
        public readonly ?string $provider = null,
        public readonly ?string $providerId = null
    ) {}

    public static function fromEntity(User $user): self
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
}
