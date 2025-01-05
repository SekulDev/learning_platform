<?php

namespace App\Group\Domain\Jobs;

use App\Group\Domain\Dto\UserAddedToGroupDTO;
use App\Group\Domain\Dto\UserRemovedFromGroupDTO;

interface GroupDispatcher
{
    public function dispatchUserAddedToGroup(UserAddedToGroupDTO $userAddedToGroupDTO): void;

    public function dispatchUserRemovedFromGroup(UserRemovedFromGroupDTO $userRemovedFromGroupDTO): void;
}
