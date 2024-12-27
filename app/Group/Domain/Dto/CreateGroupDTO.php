<?php

namespace App\Group\Domain\Dto;

use App\Auth\Domain\Dto\UserDTO;

readonly class CreateGroupDTO
{
    public function __construct(
        public string  $name,
        public UserDTO $user,
    )
    {
    }
}
