<?php

namespace App\Infrastructure\Persistence\Eloquent\Repositories;

use App\Domain\Entities\User;
use App\Domain\Repositories\UserRepository;
use App\Domain\ValueObjects\Email;
use App\Domain\ValueObjects\Password;
use App\Infrastructure\Persistence\Eloquent\Models\UserModel;

class UserRepositoryImpl implements UserRepository
{
    public function findById(int $id): ?User
    {
        $model = UserModel::find($id);
        return $model ? $this->toEntity($model) : null;
    }

    public function findByEmail(Email $email): ?User
    {
        $model = UserModel::where('email', $email->value())->first();
        return $model ? $this->toEntity($model) : null;
    }


    public function findByProvider(string $provider, string $providerId): ?User
    {
        $model = UserModel::where('provider', $provider)
            ->where('provider_id', $providerId)
            ->first();
        return $model ? $this->toEntity($model) : null;
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

        return $this->toEntity($model);
    }

    private function toEntity(UserModel $model): User
    {
        return User::reconstruct(
            id: $model->id,
            name: $model->name,
            email: new Email($model->email),
            password: Password::fromHash($model->password),
            roles: $model->roles ?? [],
            provider: $model->provider,
            providerId: $model->provider_id
        );
    }
}
