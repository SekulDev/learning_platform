<?php

namespace App\Group\Domain\Dto;

use App\Auth\Domain\Dto\UserDTO;

readonly class AddMemberToGroupDTO
{
    public function __construct(
        public readonly int     $groupId,
        public readonly string  $userEmail,
        public readonly UserDTO $user,
    )
    {
    }
}
