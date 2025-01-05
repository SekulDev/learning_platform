<?php

namespace App\Group\Infrastructure\Jobs;

use App\Group\Domain\Dto\UserAddedToGroupDTO;
use App\Group\Domain\Dto\UserRemovedFromGroupDTO;
use App\Group\Domain\Jobs\GroupDispatcher;
use App\Notification\Infrastructure\Jobs\NotifyUser;

class GroupDispatcherImpl implements GroupDispatcher
{

    public function dispatchUserAddedToGroup(UserAddedToGroupDTO $userAddedToGroupDTO): void
    {
        NotifyUser::dispatch($userAddedToGroupDTO->userId, "user_added_to_group", [
            "group" => $userAddedToGroupDTO->group,
        ]);
    }

    public function dispatchUserRemovedFromGroup(UserRemovedFromGroupDTO $userRemovedFromGroupDTO): void
    {
        NotifyUser::dispatch($userRemovedFromGroupDTO->userId, "user_removed_from_group", [
            "group" => $userRemovedFromGroupDTO->group,
        ]);
    }

}
