<?php

namespace App\Group\Infrastructure\Persistence\Repositories\Eloquent;

use App\Group\Domain\Models\Group;
use App\Group\Domain\Repositories\GroupRepository;
use App\Group\Infrastructure\Persistence\GroupModel;

class EloquentGroupRepository implements GroupRepository
{

    public function findById(int $id): ?Group
    {
        $model = GroupModel::find($id);
        return $model ? $model->toGroup() : null;
    }

    public function delete(int $id): bool
    {
        GroupModel::destroy($id);
        return true;
    }

    public function save(Group $group): Group
    {
        $model = $group->getId() ? GroupModel::find($group->getId()) : new GroupModel();

        $model->fill([
            'name' => $group->getName(),
            'user_id' => $group->getUserId(),
        ]);

        $model->save();

        return $model->toGroup();
    }
}
