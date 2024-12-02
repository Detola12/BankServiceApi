<?php

namespace App\Services;

use App\Contracts\AccountServiceInterface;
use App\Dtos\AccountDto;
use App\Http\Resources\AccountResource;
use App\Models\Account;
use App\Models\User;
use App\Responses\AccountResponse;
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
     * @param int $type
     * @return AccountResponse
     */
    public function generateAccount(User $user, int $type): AccountResponse
    {
        $response = new AccountResponse();
        try {

            if ($this->hasAccount($user, $type)){
                $response->setSuccess(false);
                $response->setMessage(__('User already as an account'));
                return $response;
            }

            $accountNo = $this->generateAccountNumber();
            $account = Account::where('account_no', $accountNo)->first();

            if ($account) {
                return $this->generateAccount($user, $type);
            }

            Account::create([
                'user_id' => $user->id,
                'account_no' => $accountNo,
                'account_type' => $type
            ]);

            $response->setSuccess(true);
            $response->setMessage(__('Account successfully generated'));
            return $response;
        }
        catch (\Exception $exception)
        {
            Log::error(__('Something went wrong : ' . $exception));
            $response->setSuccess(false);
            $response->setMessage(__('Could not generate account'));
            return $response;
        }

    }

    /**
     * @param User $user
     * @param string $pin
     * @param int $type
     * @return AccountResponse
     */
    public function setTransactionPin(User $user, int $type, string $pin): AccountResponse
    {
        $response = new AccountResponse();
        try {
            if ($this->hasSetupPin($user, $type)){
                $response->setSuccess(false);
                $response->setMessage(__('User already has a pin'));
                return $response;
            }

            if (!$this->validatePin($pin)){
                $response->setSuccess(false);
                $response->setMessage(__('Not a valid pin'));
                return $response;
            }

            if (!$this->hasAccount($user, $type)) {
                $response->setSuccess(false);
                $response->setMessage(__('User does not have an account'));
                return $response;
            }

            Account::where('user_id', $user->id)
                ->where('account_type', $type)
                ->update(['pin' => Hash::make($pin)]);

            $response->setSuccess(true);
            $response->setMessage(__('Pin added'));
            return $response;

        }
        catch (\Exception $exception){
            Log::error('Something went wrong : ' . $exception);
            $response->setSuccess(false);
            $response->setMessage(__('Something went wrong'));
            return $response;
        }

    }


    /**
     * @param User $user
     * @param int $type
     * @return bool
     */
    public function hasSetupPin(User $user, int $type): bool
    {
        if ($type == 1 && $user->savings_account->pin === null){
            return false;
        }
        if ($type == 2 && $user->current_account->pin === null){
            return false;
        }
        return true;
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
     * @param int $type
     * @return bool
     */
    public function hasAccount(User $user, int $type): bool
    {
        if ($type == 1 && $user->savings_account){
            return true;
        }
        if ($type == 2 && $user->current_account){
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
        return ctype_digit($pin) && strlen($pin) === 4;
    }

    /**
     * @param User $user
     * @param int $type
     * @param string $newPin
     * @return AccountResponse
     */
    public function resetPin(User $user, int $type, string $newPin): AccountResponse
    {
        $response = new AccountResponse();
        try {
            if (!$this->validatePin($newPin)){
                $response->setSuccess(false);
                $response->setMessage(__('Not a valid pin'));
                return $response;
            }

            if (!$this->hasAccount($user, $type)) {
                $response->setSuccess(false);
                $response->setMessage(__('User does not have an account'));
                return $response;
            }

            Account::where('user_id', $user->id)
                ->where('account_type', $type)
                ->update(['pin' => Hash::make($newPin)]);

            $response->setSuccess(true);
            $response->setMessage(__('Pin added'));
            return $response;

        }
        catch (\Exception $exception){
            Log::error(__('Something went wrong : ' . $exception));
            $response->setSuccess(false);
            $response->setMessage(__('Something went wrong'));
            return $response;
        }
    }

    /**
     * @param User $user
     * @param int $type
     * @param string $pin
     * @return bool
     */
    public function verifyPin(User $user, int $type, string $pin): bool
    {
        if ($type == 1 && Hash::check($pin, $user->savings_account->pin)){
            return true;
        }
        if ($type == 2 && Hash::check($pin, $user->current_account->pin)){
            return true;
        }
        return false;
    }

    public function getAllAccounts(): AccountResponse
    {
        $accounts = Account::all();
        $response = new AccountResponse();

        $response->setSuccess(true);
        $response->setMessage(__('Accounts detail fetched'));
        $accountDto = AccountResource::collection($accounts);
        $response->setData(['accounts' => $accountDto]);
        return $response;
    }
}
