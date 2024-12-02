<?php

namespace App\Repositories;

use App\Dtos\AccountDto;
use App\Http\Resources\AccountResource;
use App\Models\Account;
use App\Models\AccountType;
use App\Responses\AccountResponse;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

class AccountRepository
{
    public function __construct()
    {

    }

    public function getAllAccounts(int $type = null) : AccountResponse
    {
        $query = Account::query();
        if ($type){
            $query->where('account_type', $type);
        }
        $count = $query->count();
        $response = new AccountResponse();

        $response->setSuccess(__(true));
        if ($count == 0) {
            $response->setMessage(__('No Account found'));
            return $response;
        }

        $response->setMessage(__('Accounts detail fetched'));
        $accountDto = AccountResource::collection($query->paginate());
        $response->setData(['count' => $count,'accounts' => $accountDto]);
        return $response;
    }

    public function getAccountTypes() : AccountResponse
    {
        $types = AccountType::query()->get();
        $response = new AccountResponse();

        $response->setSuccess(true);
        $response->setMessage(__('Account Types fetched'));
        $response->setData(['types' => $types]);
        return $response;
    }

    public function getAccountById(int $id) : AccountResponse
    {
        $response = new AccountResponse();
        try {
            $account = Account::where('id', $id)->first();

            if (!$account){
                $response->setSuccess(false);
                $response->setMessage(__('Account not found'));
                return $response;
            }

            $accountDto = AccountResource::make($account);

            $response->setSuccess(true);
            $response->setMessage(__('Account details fetched'));
            $response->setData(['data' => $accountDto]);

            return $response;
        }
        catch (\Exception $exception){
            Log::error(__('Something went wrong : ' . $exception));
            $response->setSuccess(false);
            $response->setMessage(__('Something went wrong'));
            return $response;
        }
    }

    public function getAccountByUserId(int $user_id) : AccountResponse
    {
        $response = new AccountResponse();
        try {
            $account = Account::where('user_id', $user_id)->first();
            if (!$account){
                $response->setSuccess(__(false));
                $response->setMessage(__('Account not found'));
                return $response;
            }

            $response->setSuccess(__(true));
            $response->setMessage(__('Account details fetched'));
            $accountDto = AccountDto::ModelToArray($account);
            $response->setData(['data' => $accountDto]);
            return $response;
        }
        catch (\Exception $exception){
            Log::error(__('Something went wrong : ' . $exception));
            $response->setSuccess(false);
            $response->setMessage(__('Something went wrong'));
            return $response;
        }
    }
}
