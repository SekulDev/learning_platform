<?php

namespace App\Auth\Domain\Repositories;

use App\Auth\Domain\Models\User;
use App\Common\Domain\ValueObjects\Email;

interface UserRepository
{
    public function findById(int $id): ?User;
    public function findByEmail(Email $email): ?User;
    public function findByProvider(string $provider, string $providerId): ?User;
    public function save(User $user): User;
}
