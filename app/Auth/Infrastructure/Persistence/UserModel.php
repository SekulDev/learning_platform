<?php

namespace App\Auth\Infrastructure\Persistence;

use App\Auth\Domain\Models\User;
use App\Common\Domain\ValueObjects\Email;
use App\Common\Domain\ValueObjects\Password;
use Illuminate\Foundation\Auth\User as Authenticatable;

class UserModel extends Authenticatable
{
    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'provider',
        'provider_id',
        'roles'
    ];

    protected $casts = [
        'roles' => 'array'
    ];

    public function toUser(): User
    {
        return new User(
            id: $this->id,
            name: $this->name,
            email: new Email($this->email),
            password: Password::fromHash($this->password),
            roles: $this->roles ?? [],
            provider: $this->provider,
            providerId: $this->provider_id
        );
    }
}
