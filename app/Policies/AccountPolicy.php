<?php

namespace App\Policies;

use App\Models\Account;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Auth;

class AccountPolicy
{
   /* public function generate(User $user, int $id): bool
    {
        return $user->id == $id;
    }*/

    /*public function editPin(User $user, Account $account)
    {
        return $user->id === $account->user_id;
    }*/


}
