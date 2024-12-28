<?php

namespace App\Group\Domain\Jobs;

use App\Group\Domain\Dto\UserAddedToGroupDTO;

interface GroupDispatcher
{
    public function dispatchUserAddedToGroup(UserAddedToGroupDTO $userAddedToGroupDTO): void;
}
