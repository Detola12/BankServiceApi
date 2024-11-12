<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\AccountService;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function __construct(private readonly AccountService $accountService)
    {
    }

    public function generate(Request $request)
    {
        $response = $this->accountService->generateAccount($request->user());
        return $response->compose();
    }

    public function getAllAccounts()
    {
        $response = $this->accountService->getAllAccounts();
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
        $response = $this->accountService->getAccountById($id);
        return $response->compose();
    }

}
