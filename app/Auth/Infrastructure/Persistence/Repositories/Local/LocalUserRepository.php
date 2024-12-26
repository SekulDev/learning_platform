<?php

namespace App\Auth\Infrastructure\Persistence\Repositories\Local;

use App\Auth\Domain\Models\User;
use App\Auth\Domain\Repositories\UserRepository;
use App\Common\Domain\ValueObjects\Email;

class LocalUserRepository implements UserRepository
{

    private $users = [];

    public function findById(int $id): ?User
    {
        return $this->users[$id] ?? null;
    }

    public function findByEmail(Email $email): ?User
    {
        return $this->users[array_search($email->value(), array_column($this->users, 'email'))] ?? null;
    }

    public function findByProvider(string $provider, string $providerId): ?User
    {
        return $this->users[array_search($providerId, array_column($this->users, 'provider_id'))] ?? null;
    }

    public function save(User $user): User
    {
        $this->users[$user->getId()] = $user;
        return $user;
    }
}
