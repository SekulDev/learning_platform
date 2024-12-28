<?php

namespace App\Group\Infrastructure\Jobs;

use App\Auth\Infrastructure\Jobs\NotifyUser;
use App\Group\Domain\Dto\UserAddedToGroupDTO;
use App\Group\Domain\Jobs\GroupDispatcher;

class GroupDispatcherImpl implements GroupDispatcher
{

    public function dispatchUserAddedToGroup(UserAddedToGroupDTO $userAddedToGroupDTO): void
    {
        NotifyUser::dispatch($userAddedToGroupDTO->userId, "user_added_to_group", [
            "group" => $userAddedToGroupDTO->group,
        ]);
    }
}
