<?php

namespace App\Services;

use App\Contracts\AccountServiceInterface;
use App\Dtos\AccountDto;
use App\Http\Resources\AccountResource;
use App\Models\Account;
use App\Models\User;
use App\Responses\AccountResponse;
use App\Responses\UserResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AccountService implements AccountServiceInterface
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
     * @return AccountResponse
     */
    public function generateAccount(User $user): AccountResponse
    {
        $response = new AccountResponse();
        try {
            if ($this->hasAccount($user)){
                $response->setSuccess(false);
                $response->setMessage('User already as an account');
                return $response;
            }

            $accountNo = $this->generateAccountNumber();
            $account = Account::where('account_no', $accountNo)->first();

            if ($account) {
                return $this->generateAccount($user);
            }
            $user->account()->create([
                'account_no' => $accountNo
            ]);

            $response->setSuccess(true);
            $response->setMessage('Account successfully generated');
            return $response;
        }
        catch (\Exception $exception)
        {
            Log::error('Something went wrong : ' . $exception);
            $response->setSuccess(false);
            $response->setMessage('Could not generate account');
            return $response;
        }

    }

    /**
     * @param User $user
     * @param string $pin
     * @return AccountResponse
     */
    public function setTransactionPin(User $user, string $pin): AccountResponse
    {
        $response = new AccountResponse();
        try {
            if ($this->hasSetupPin($user)){
                $response->setSuccess(false);
                $response->setMessage('User already has a pin');
                return $response;
            }

            if (!$this->validatePin($pin)){
                $response->setSuccess(false);
                $response->setMessage('Not a valid pin');
                return $response;
            }

            if (!$this->hasAccount($user)) {
                $response->setSuccess(false);
                $response->setMessage('User does not have an account');
                return $response;
            }

            Account::where('user_id', $user->id)
                ->update(['pin' => Hash::make($pin)]);

            $response->setSuccess(true);
            $response->setMessage('Pin added');
            return $response;

        }
        catch (\Exception $exception){
            Log::error('Something went wrong : ' . $exception);
            $response->setSuccess(false);
            $response->setMessage('Something went wrong');
            return $response;
        }

    }


    /**
     * @param User $user
     * @return bool
     */
    public function hasSetupPin(User $user): bool
    {

        if ($user->account->pin === null){
            return false;
        }

        return true;
    }

    /**
     * @param int $user_id
     * @return AccountResponse
     */
    public function getAccountByUserId(int $user_id): AccountResponse
    {
        $response = new AccountResponse();
        try {
            $account = Account::where('user_id', $user_id)->first();
            if (!$account){
                $response->setSuccess(false);
                $response->setMessage('Account not found');
                return $response;
            }

            $response->setSuccess(true);
            $response->setMessage('Account details fetched');
            $accountDto = AccountDto::ModelToArray($account);
            $response->setData(['data' => $accountDto]);
            return $response;
        }
        catch (\Exception $exception){
            Log::error('Something went wrong : ' . $exception);
            $response->setSuccess(false);
            $response->setMessage('Something went wrong');
            return $response;
        }
    }

    /**
     * @param int $id
     * @return AccountResponse
     */
    public function getAccountById(int $id): AccountResponse
    {
        $response = new AccountResponse();
        try {
            $account = Account::where('id', $id)->first();

            if (!$account){
                $response->setSuccess(false);
                $response->setMessage('Account not found');
                return $response;
            }

            $accountDto = AccountDto::ModelToArray($account);

            $response->setSuccess(true);
            $response->setMessage('Account details fetched');
            $response->setData(['data' => $accountDto]);

            return $response;
        }
        catch (\Exception $exception){
            Log::error('Something went wrong : ' . $exception);
            $response->setSuccess(false);
            $response->setMessage('Something went wrong');
            return $response;
        }
    }

    /**
     * @return string
     */
    public function generateAccountNumber(): string
    {
        $prefix = '0482';
        $number = strval(rand(10000, 99999));
        return $prefix . $number;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function hasAccount(User $user): bool
    {
        if ($user->account){
            return true;
        }
        return false;
    }

    /**
     * @param string $pin
     * @return bool
     */
    public function validatePin(string $pin): bool
    {
        if(ctype_digit($pin)){
            return true;
        }

        return false;
    }

    /**
     * @param User $user
     * @param string $newPin
     * @return AccountResponse
     */
    public function resetPin(User $user, string $newPin): AccountResponse
    {
        $response = new AccountResponse();
        try {
            if (!$this->validatePin($newPin)){
                $response->setSuccess(false);
                $response->setMessage('Not a valid pin');
                return $response;
            }

            if (!$this->hasAccount($user)) {
                $response->setSuccess(false);
                $response->setMessage('User does not have an account');
                return $response;
            }

            Account::where('user_id', $user->id)
                ->update(['pin' => Hash::make($newPin)]);

            $response->setSuccess(true);
            $response->setMessage('Pin added');
            return $response;

        }
        catch (\Exception $exception){
            Log::error('Something went wrong : ' . $exception);
            $response->setSuccess(false);
            $response->setMessage('Something went wrong');
            return $response;
        }
    }

    /**
     * @param User $user
     * @param string $pin
     * @return bool
     */
    public function verifyPin(User $user, string $pin): bool
    {
        if (Hash::check($pin, $user->account->pin)){
            return true;
        }

        return false;
    }

    public function getAllAccounts(): AccountResponse
    {
        $accounts = Account::all();
        $response = new AccountResponse();

        $response->setSuccess(true);
        $response->setMessage('Accounts detail fetched');
        $accountDto = AccountResource::collection($accounts);
        $response->setData(['accounts' => $accountDto]);
        return $response;
    }
}
