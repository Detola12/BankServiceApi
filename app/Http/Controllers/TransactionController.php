<?php

namespace App\Http\Controllers;

use App\Models\User;
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
            'amount' => ['required', 'min:0', 'decimal:0,2'],
            'type' => 'nullable|int|exists:account_types,id'
        ]);

        $response = $this->transactionService->initiateDeposit($request->user(), $request->amount);
        return $response->compose();
    }

    public function withdraw(Request $request)
    {
        $request->validate([
            'pin' => ['required','string','min:4','max:4'],
            'amount' => ['required', 'min:0', 'decimal:0,2'],
            'type' => 'nullable|int|exists:account_types,id'
        ]);

        $this->checkPin($request->user(), $request->type, $request->pin);

        $response = $this->transactionService->initiateWithdraw($request->user(), $request->amount);
        return $response->compose();
    }

    public function transfer(Request $request)
    {
        $request->validate([
            'pin' => ['required','string','min:4','max:4'],
            'account_no' => ['required','string','exists:accounts,account_no'],
            'amount' => ['required', 'min:0', 'decimal:0,2'],
            'type' => 'nullable|int|exists:account_types,id'
        ], [
            'account_no.exists' => 'Account number does not exist'
        ]);

        $this->checkPin($request->user(), $request->type, $request->pin);

        $response = $this->transactionService->initiateTransfer($request->user(), $request->type, $request->account_no, $request->amount);
        return $response->compose();
    }

    protected function checkPin(User $user, int $type, $pin)
    {
        $hasPin = $this->accountService->hasSetupPin($user, $type);
        if (!$hasPin){
            return response()->json([
                'success' => false,
                'message' => 'Pin has not been set'
            ], 400);
        }
        $checkPin = $this->accountService->verifyPin($user, $type, $pin);
        if (!$checkPin){
            return response()->json([
                'success' => false,
                'message' => 'Incorrect Pin'
            ], 400);
        }
    }
}
