<?php

namespace App\Group\Domain\Dto;

readonly class UserRemovedFromGroupDTO
{
    public function __construct(
        public readonly int      $userId,
        public readonly GroupDTO $group
    )
    {
    }
}
