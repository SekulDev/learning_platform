<?php

use App\Auth\Domain\Dto\UserDTO;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('user-notify.{id}', function (UserDTO $user, int $id) {
    return $user->id === $id;
});
Broadcast::private('user-notify.{id}');


Broadcast::channel('group.{id}', function (UserDTO $user, int $id) {
    // check if user is in group
    return true;
});
Broadcast::private('group.{id}');
