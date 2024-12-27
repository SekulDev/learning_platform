<?php

namespace App\Group\Domain\Dto;

use App\Auth\Domain\Dto\UserDTO;

readonly class DeleteGroupDTO
{
    public function __construct(
        public int     $id,
        public UserDTO $user,
    )
    {
    }
}
