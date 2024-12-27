<?php

namespace App\Group\Infrastructure\Persistence\Repositories\Eloquent;

use App\Auth\Domain\Models\User;
use App\Common\Domain\ValueObjects\Email;
use App\Common\Domain\ValueObjects\Password;
use App\Group\Domain\Models\Group;
use App\Group\Domain\Repositories\GroupRepository;
use App\Group\Infrastructure\Persistence\GroupModel;

class EloquentGroupRepository implements GroupRepository
{

    public function findById(int $id): ?Group
    {
        $model = GroupModel::with(['members'])->find($id);
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

        $model->members()->sync(array_map(fn($member) => $member, $group->getMembers()));
        return $model->toGroup();
    }

    public function getMembers(int $groupId): array
    {
        $group = GroupModel::with(['members'])->findOrFail($groupId);

        return $group->members->map(function ($member) {
            return new User(
                $member->id,
                $member->name,
                new Email($member->email),
                Password::fromHash($member->password),
                $member->roles,
                $member->provider,
                $member->providerId
            );
        })->toArray();
    }

    public function findByMemberId(int $userId): array
    {
        $groupModels = GroupModel::whereHas('members', function ($query) {
            $query->where('user_id', 1);
        })->get();

        return $groupModels->map(fn($groupModel) => $groupModel->toGroup())->toArray();
    }

    public function findByOwnerId(int $userId): array
    {
        $groupModels = GroupModel::where('user_id', $userId)->get();

        return $groupModels->map(fn($groupModel) => $groupModel->toGroup())->toArray();
    }
}
