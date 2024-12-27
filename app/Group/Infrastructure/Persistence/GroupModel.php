<?php

namespace App\Group\Infrastructure\Persistence;

use App\Group\Domain\Models\Group;
use Illuminate\Database\Eloquent\Model;

class GroupModel extends Model
{
    protected $table = 'groups';

    protected $fillable = [
        'name',
        'user_id'
    ];

    public function toGroup(): Group
    {
        return new Group(
            $this->id,
            $this->name,
            $this->user_id
        );
    }
}
