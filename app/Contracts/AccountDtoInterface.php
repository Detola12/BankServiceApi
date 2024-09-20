<?php

namespace App\Contracts;

use App\Models\Account;

interface AccountDtoInterface
{
    public static function ModelToArray(Account $account) : array;

}
