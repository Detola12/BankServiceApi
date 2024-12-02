<?php

namespace App\Contracts;

use App\Models\User;
use App\Responses\AccountResponse;

interface AccountServiceInterface
{
    public function generateAccountNumber() : string;

    public function hasAccount(User $user, int $type) : bool;

    public function setTransactionPin(User $user, int $type, string $pin) : AccountResponse;

    public function hasSetupPin(User $user, int $type) : bool;

    public function validatePin(string $pin) : bool;

    public function resetPin(User $user, int $type, string $newPin) : AccountResponse;

    public function verifyPin(User $user, int $type, string $pin) : bool;

}
