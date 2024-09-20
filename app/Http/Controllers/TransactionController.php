<?php

namespace App\Http\Controllers;

use App\Services\AccountService;
use App\Services\TransactionService;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function __construct(private readonly TransactionService $transactionService,
                                private readonly AccountService $accountService)
    {
    }

    public function deposit(Request $request)
    {
        $request->validate([
            'amount' => ['required', 'min:0', 'decimal:0,2']
        ]);

        $response = $this->transactionService->initiateDeposit($request->user(), $request->amount);
        return $response->compose();
    }

    public function withdraw(Request $request)
    {
        $request->validate([
            'pin' => ['required','string','min:4','max:4'],
            'amount' => ['required', 'min:0', 'decimal:0,2']
        ]);

        $response = $this->transactionService->initiateWithdraw($request->user(), $request->amount);
        return $response->compose();
    }

    public function transfer(Request $request)
    {
        $request->validate([
            'pin' => ['required','string','min:4','max:4'],
            'account_no' => ['required','string'],
            'amount' => ['required', 'min:0', 'decimal:0,2']
        ]);

        $checkPin = $this->accountService->verifyPin($request->user(), $request->pin);
        if (!$checkPin){
            return response()->json([
                'success' => false,
                'message' => 'Incorrect Pin'
            ]);
        }

        $response = $this->transactionService->initiateTransfer($request->user(), $request->account_no, $request->amount);
        return $response->compose();
    }
}
