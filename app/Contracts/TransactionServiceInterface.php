<?php

namespace App\Contracts;

use App\Models\Account;
use App\Models\User;
use App\Responses\TransactionResponse;

interface TransactionServiceInterface
{
    public function getAllUserTransaction(User $user) : TransactionResponse;

    public function getAllUserDebitTransaction(User $user) : TransactionResponse;

    public function getAllUserCreditTransaction(User $user) : TransactionResponse;

    public function initiateTransfer(User $sender, string $accountNo, float $amount) : TransactionResponse;

    public function initiateDeposit(User $user, float $amount) : TransactionResponse;

    public function initiateWithdraw(User $user, float $amount) : TransactionResponse;

    public function checkBalance(User $user, float $amount) : bool;

}
