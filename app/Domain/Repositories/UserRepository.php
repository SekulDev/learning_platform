<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\User;
use App\Domain\ValueObjects\Email;

interface UserRepository
{
    public function findById(int $id): ?User;
    public function findByEmail(Email $email): ?User;
    public function findByProvider(string $provider, string $providerId): ?User;
    public function save(User $user): User;
}
