<?php

namespace App\Http\Controllers;

use App\Enums\TransactionTypeEnum;
use App\Http\Requests\DepositRequest;
use App\Models\Transaction;
use App\Models\User;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class DepositController extends Controller
{
    public function index()
    {
        $depositTransactions = Transaction::where('transaction_type', TransactionTypeEnum::Deposit)->paginate();

        return response()->json($depositTransactions);
    }

    public function deposit(DepositRequest $request)
    {
        $attributes = $request->validated();
        $user = User::find($attributes['user_id']);
        if ($user) {
            $user->balance += $attributes['amount'];
            $user->save();

            return response()->json([
                'message' => __('Deposited successfully'),
                'amount' => $attributes['amount'],
                'new_balance' => $user->balance
            ], Response::HTTP_CREATED);
        }

        throw new Exception(__('Deposit failed, try with valid data.'));
    }
}
