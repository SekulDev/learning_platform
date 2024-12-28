<?php

namespace App\Group\Domain\Dto;

readonly class UserAddedToGroupDTO
{
    public function __construct(
        public readonly int      $userId,
        public readonly GroupDTO $group
    )
    {
    }
}
