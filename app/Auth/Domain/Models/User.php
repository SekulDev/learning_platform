<?php

namespace App\Auth\Domain\Models;

use App\Auth\Domain\Dto\UpdateUserDTO;
use App\Auth\Domain\Exceptions\AuthenticationException;
use App\Common\Domain\AggregateRoot;
use App\Common\Domain\ValueObjects\Email;
use App\Common\Domain\ValueObjects\Password;

class User extends AggregateRoot
{

    public function __construct(
        private int      $id,
        private string   $name,
        private Email    $email,
        private Password $password,
        private array    $roles = ['user'],
        private ?string  $provider = null,
        private ?string  $providerId = null)
    {
    }

    public function authenticate(string $password): bool
    {
        if ($this->provider !== null) {
            return false;
        }
        return $this->password->verify($password);
    }

    public function isAdmin(): bool
    {
        return in_array('admin', $this->roles);
    }

    public static function createFromOAuth(
        string $name,
        Email  $email,
        string $provider,
        string $providerId,
        array  $roles = ['user']
    ): self
    {
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

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email->value(),
        ];
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getPassword(): Password
    {
        return $this->password;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function getProvider(): ?string
    {
        return $this->provider;
    }

    public function getProviderId(): ?string
    {
        return $this->providerId;
    }

    public function update(UpdateUserDTO $data)
    {
        if ($data->userId !== $this->getId()) {
            throw AuthenticationException::invalidCredentials();
        }
        $this->name = $data->name;
    }
}
