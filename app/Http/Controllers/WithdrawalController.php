<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Enums\TransactionTypeEnum;
use App\Services\IWithdrawService;
use App\Http\Controllers\Controller;
use App\Http\Requests\WithdrawRequest;
use Symfony\Component\HttpFoundation\Response;

class WithdrawalController extends Controller
{
    public function __construct(
        private IWithdrawService $service,
    ) {
    }

    public function index()
    {
        $withdrawalsTransactions = Transaction::where('transaction_type', TransactionTypeEnum::Withdrawal)->paginate();
        return response()->json($withdrawalsTransactions);
    }

    public function withdraw(WithdrawRequest $request)
    {
        // Validate the incoming request data
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0.01',
        ]);

        $user = User::findOrFail($request->user_id);
        $amount = $request->amount;
        $accountType = $user->account_type;
        $freeWithdrawal = false;

        // Check if it's a Friday
        if (Carbon::now()->dayOfWeek === Carbon::FRIDAY) {
            $freeWithdrawal = true;
        }

        // Check if it's the first 5K withdrawal of the month for Individual account
        if ($accountType == 'Individual') {
            $monthlyWithdrawals = Transaction::where('user_id', $user->id)
                ->where('transaction_type', 'withdrawal')
                ->whereMonth('created_at', Carbon::now()->month)
                ->sum('amount');

            if ($monthlyWithdrawals < 5000) {
                $freeWithdrawal = true;
            }
        }

        // Check if the withdrawal amount exceeds 1K
        if ($amount <= 1000) {
            $fee = 0;
        } else {
            $fee = ($accountType == 'Individual') ? $amount * 0.015 / 100 : $amount * 0.025 / 100;
        }

        // Deduct fee if it's not a free withdrawal
        if (!$freeWithdrawal) {
            $amount -= $fee;
        }

        // Check if the withdrawal amount exceeds the user's balance
        if ($amount > $user->balance) {
            return response()->json([
                'message' => 'Withdrawal failed. Insufficient balance.',
            ], Response::HTTP_BAD_REQUEST);
        }

        // Update user balance
        $user->balance -= $amount;
        $user->save();

        // Record the transaction
        Transaction::create([
            'user_id' => $user->id,
            'transaction_type' => 'withdrawal',
            'amount' => $request->amount,
            'fee' => $fee,
            'date' => Carbon::now(),
        ]);

        return response()->json([
            'message' => 'Withdrawal successful.',
            'amount' => $request->amount,
            'fee' => $fee,
            'balance' => $user->balance,
        ]);
    }

}
