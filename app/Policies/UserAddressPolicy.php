<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserAddressPolicy
{
    use HandlesAuthorization;

    public function own(User $user, UserAddress $user_address)
    {
        return $user_address->user_id === $user->id;
    }
}
