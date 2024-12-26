<?php

namespace App\Infrastructure\Persistence\Eloquent\Models;
use Illuminate\Database\Eloquent\Model;

class UserModel extends Model
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
}
