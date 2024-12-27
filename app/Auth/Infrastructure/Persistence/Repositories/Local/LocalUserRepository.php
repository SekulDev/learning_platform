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
        return array_reduce(
            $this->users,
            fn(?User $carry, User $item) => $item->getEmail()->value() === $email->value() ? $item : $carry,
            null
        );
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
