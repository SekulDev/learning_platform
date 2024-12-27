<?php

namespace App\Group\Infrastructure\Persistence;

use App\Auth\Infrastructure\Persistence\UserModel;
use App\Group\Domain\Models\Group;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class GroupModel extends Model
{
    protected $table = 'groups';

    protected $fillable = [
        'name',
        'user_id'
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'user_id');
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(UserModel::class, 'group_members', 'group_id', 'user_id');
    }

    public function toGroup(): Group
    {
        return new Group(
            $this->id,
            $this->name,
            $this->user_id,
            $this->members->map(function ($member) {
                return $member->id;
            })->toArray()
        );
    }
}
