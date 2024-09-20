<?php

namespace App\Contracts;

use App\Models\User;
use App\Responses\AccountResponse;

interface AccountServiceInterface
{
    public function generateAccount(User $user) : AccountResponse;

    public function generateAccountNumber() : string;

    public function hasAccount(User $user) : bool;

    public function setTransactionPin(User $user, string $pin) : AccountResponse;

    public function hasSetupPin(User $user) : bool;

    public function getAccountByUserId(int $user_id) : AccountResponse;

    public function getAccountById(int $id) : AccountResponse;

    public function validatePin(string $pin) : bool;

    public function resetPin(User $user,string $newPin) : AccountResponse;

    public function verifyPin(User $user, string $pin) : bool;

}
