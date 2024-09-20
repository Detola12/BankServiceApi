<?php

namespace App\Services;

use App\Contracts\TransactionServiceInterface;
use App\Models\Account;
use App\Models\Transaction;
use App\Models\User;
use App\Responses\TransactionResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransactionService implements TransactionServiceInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * @param User $user
     * @return TransactionResponse
     */
    public function getAllUserTransaction(User $user): TransactionResponse
    {
        // TODO: Implement getAllUserTransaction() method.
    }

    /**
     * @param User $user
     * @return TransactionResponse
     */
    public function getAllUserDebitTransaction(User $user): TransactionResponse
    {
        // TODO: Implement getAllUserDebitTransaction() method.
    }

    /**
     * @param User $user
     * @return TransactionResponse
     */
    public function getAllUserCreditTransaction(User $user): TransactionResponse
    {
        // TODO: Implement getAllUserCreditTransaction() method.
    }

    /**
     * @param User $sender
     * @param string $accountNo
     * @param float $amount
     * @return TransactionResponse
     */
    public function initiateTransfer(User $sender, string $accountNo, float $amount): TransactionResponse
    {
        $response = new TransactionResponse();

        try {
            if (!$this->checkBalance($sender, $amount)){
                $response->setSuccess(false);
                $response->setMessage('Insufficient Balance');
                return $response;
            }

            if ($sender->account->account_no == $accountNo){
                $response->setSuccess(false);
                $response->setMessage('Cannot transfer to same account');
                return $response;
            }
            $receiver = Account::where('account_no', $accountNo)->first();
            $senderAccount = Account::where('user_id', $sender->id)->first();
            DB::beginTransaction();

            $senderAccount->balance -= $amount;
            $senderAccount->save();
            $receiver->balance += $amount;
            $receiver->save();

            Transaction::create([
                'sender_id' => $sender->id,
                'account_no' => $receiver->account_no,
                'amount' => $amount,
                'transaction_type' => 'transfer',
                'status' => 'successful'
            ]);

            DB::commit();

            $response->setSuccess(true);
            $response->setMessage('Transfer successful');
            return $response;

        }
        catch (\Exception $exception){
            DB::rollBack();
            Log::error('Something went wrong : ' . $exception);
            $response->setSuccess(false);
            $response->setMessage('Something went wrong');
            return $response;
        }
    }

    /**
     * @param User $user
     * @param float $amount
     * @return TransactionResponse
     */
    public function initiateDeposit(User $user, float $amount): TransactionResponse
    {
        $response = new TransactionResponse();

        try {
            $account = Account::where('user_id', $user->id)->first();
            /*if (!$this->checkAmount($amount)){
                $response->setSuccess(false);
                $response->setMessage('Invalid Amount');
                return $response;
            }*/
            if (!$account){
                $response->setSuccess(false);
                $response->setMessage('User does not have an account');
                return $response;
            }

            DB::beginTransaction();
            $account->balance += $amount;
            $account->save();
            Transaction::create([
                'sender_id' => $user->id,
                'account_no' => $account->account_no,
                'amount' => $amount,
                'transaction_type' => 'withdraw',
                'status' => 'successful',
            ]);
            DB::commit();
            $response->setSuccess(true);
            $response->setMessage('Deposit successful');
            return $response;

        }
        catch (\Exception $exception){
            DB::rollBack();
            Log::error('Something went wrong : ' . $exception);
            $response->setSuccess(false);
            $response->setMessage('Something went wrong');
            return $response;
        }
    }

    /**
     * @param User $user
     * @param float $amount
     * @return TransactionResponse
     */
    public function initiateWithdraw(User $user, float $amount): TransactionResponse
    {
        $response = new TransactionResponse();

        try {
            $account = Account::where('user_id', $user->id)->first();
            if (!$account){
                $response->setSuccess(false);
                $response->setMessage('User does not have an account');
                return $response;
            }

            if (!$this->checkBalance($user, $amount)){
                $response->setSuccess(false);
                $response->setMessage('Insufficient Balance');
                return $response;
            }

            DB::beginTransaction();

            $account->balance -= $amount;
            $account->save();

            Transaction::create([
                'sender_id' => $user->id,
                'account_no' => $account->account_no,
                'amount' => $amount,
                'transaction_type' => 'withdraw',
                'status' => 'successful',
            ]);

            DB::commit();

            $response->setSuccess(true);
            $response->setMessage('Withdraw successful');
            return $response;

        }
        catch (\Exception $exception){
            DB::rollBack();
            Log::error('Something went wrong : ' . $exception);
            $response->setSuccess(false);
            $response->setMessage('Something went wrong');
            return $response;
        }
    }

    /**
     * @param User $user
     * @param float $amount
     * @return bool
     */
    public function checkBalance(User $user, float $amount): bool
    {
        if ($user->account->balance - $amount < 0){
            return false;
        }

        return true;
    }

    /**
     * @param float $amount
     * @return bool
     */
    public function checkAmount(float $amount): bool
    {
        if ($amount < 0){
            return false;
        }

        return true;
    }
}
