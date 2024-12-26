<?php

namespace App\Auth\Infrastructure\Persistence\Repositories\Eloquent;

use App\Auth\Domain\Models\User;
use App\Auth\Domain\Repositories\UserRepository;
use App\Auth\Infrastructure\Persistence\UserModel;
use App\Common\Domain\ValueObjects\Email;

class EloquentUserRepository implements UserRepository
{
    public function findById(int $id): ?User
    {
        $model = UserModel::find($id);
        return $model ? $model->toUser() : null;
    }

    public function findByEmail(Email $email): ?User
    {
        $model = UserModel::where('email', $email->value())->first();
        return $model ? $model->toUser() : null;
    }


    public function findByProvider(string $provider, string $providerId): ?User
    {
        $model = UserModel::where('provider', $provider)
            ->where('provider_id', $providerId)
            ->first();
        return $model ? $model->toUser() : null;
    }

    public function save(User $user): User
    {
        $model = $user->getId() ? UserModel::find($user->getId()) : new UserModel();

        $model->fill([
            'name' => $user->getName(),
            'email' => $user->getEmail()->value(),
            'password' => $user->getPassword()->value(),
            'provider' => $user->getProvider(),
            'provider_id' => $user->getProviderId(),
            'roles' => $user->getRoles()
        ]);

        $model->save();

        return $model->toUser();
    }
}
