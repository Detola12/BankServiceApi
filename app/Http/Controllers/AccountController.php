<?php

namespace App\Http\Controllers;

use App\Repositories\AccountRepository;
use App\Services\AccountService;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function __construct(private readonly AccountService $accountService,
                                private readonly AccountRepository $accountRepository)
    {
    }

    public function generate(Request $request)
    {
        $request->validate([
            'type' => 'required|integer|exists:account_types,id'
        ],[
            'type.exists' => 'Account type does not exist'
        ]);
        $response = $this->accountService->generateAccount($request->user(), $request->type);
        return $response->compose();
    }

    public function getAllAccounts(Request $request)
    {
        $request->validate([
            'type' => 'nullable|int|exists:account_types,id'
        ], [
            'type.exists' => 'Account type does not exist'
        ]);
        $response = $this->accountRepository->getAllAccounts($request->type);
        return $response->compose();
    }

    public function addPin(Request $request)
    {
        $request->validate([
            'pin' => ['required','string', 'min:4', 'max:4']
        ]);
        $response = $this->accountService->setTransactionPin($request->user(), $request->pin);
        return $response->compose();
    }

    public function getAccountType()
    {
        $response = $this->accountRepository->getAccountTypes();
        return $response->compose();
    }

    public function resetPin(Request $request)
    {
        $request->validate([
            'pin' => ['required','string','min:4','max:4'],
        ]);

        $response = $this->accountService->resetPin($request->user(), $request->pin);
        return $response->compose();
    }

    public function getAccountById($id)
    {
        $response = $this->accountRepository->getAccountById($id);
        return $response->compose();
    }

}
