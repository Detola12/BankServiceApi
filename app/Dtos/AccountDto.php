<?php

namespace App\Dtos;

use App\Contracts\AccountDtoInterface;
use App\Models\Account;

class AccountDto implements AccountDtoInterface
{
    private string $accountNo;
    private float|int $balance;
    private int $userId;
    private string|null $pin;

    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * @param string $accountNo
     */
    public function setAccountNo(string $accountNo): void
    {
        $this->accountNo = $accountNo;
    }

    /**
     * @return string
     */
    public function getAccountNo(): string
    {
        return $this->accountNo;
    }

    /**
     * @param float|int $balance
     */
    public function setBalance(float|int $balance): void
    {
        $this->balance = $balance;
    }

    /**
     * @return float|int
     */
    public function getBalance(): float|int
    {
        return $this->balance;
    }

    /**
     * @param int $userId
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @param string|null $pin
     */
    public function setPin(?string $pin): void
    {
        $this->pin = $pin;
    }

    /**
     * @return string|null
     */
    public function getPin(): ?string
    {
        return $this->pin;
    }

    /**
     * @param Account $account
     * @return array
     */
    public static function ModelToArray(Account $account): array
    {
        return [
            'accountNo' => $account->account_no,
            'user_id' => $account->user_id,
            'balance' => $account->balance
        ];
    }
}
