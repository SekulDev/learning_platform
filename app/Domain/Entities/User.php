<?php

namespace App\Domain\Entities;

use App\Domain\ValueObjects\Email;
use App\Domain\ValueObjects\Password;

class User
{
    private function __construct(
        private int $id,
        private string $name,
        private Email $email,
        private Password $password,
        private array $roles = [],
        private ?string $provider = null,
        private ?string $providerId = null
    ) {}

    public static function create(
        string $name,
        Email $email,
        Password $password,
        array $roles = []
    ): self {
        return new self(
            id: 0,
            name: $name,
            email: $email,
            password: $password,
            roles: $roles
        );
    }

    public static function createFromOAuth(
        string $name,
        Email $email,
        string $provider,
        string $providerId,
        array $roles = []
    ): self {
        return new self(
            id: 0,
            name: $name,
            email: $email,
            password: Password::fromHash(''),
            roles: $roles,
            provider: $provider,
            providerId: $providerId
        );
    }

    public static function reconstruct(
        int $id,
        string $name,
        Email $email,
        Password $password,
        array $roles = [],
        ?string $provider = null,
        ?string $providerId = null
    ): self {
        return new self($id, $name, $email, $password, $roles, $provider, $providerId);
    }

    public function getId(): int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getEmail(): Email { return $this->email; }
    public function getPassword(): Password { return $this->password; }
    public function getRoles(): array { return $this->roles; }
    public function getProvider(): ?string { return $this->provider; }
    public function getProviderId(): ?string { return $this->providerId; }
}
