<?php

namespace App\Group\Domain\Dto;

use App\Auth\Domain\Dto\UserDTO;

readonly class RemoveMemberFromGroupDTO
{
    public function __construct(
        public readonly int     $groupId,
        public readonly int     $userId,
        public readonly UserDTO $user
    )
    {
    }
}
