<?php

namespace App\Services;

use App\Enums\AccountTypeEnum;
use App\Models\User;
use App\Models\Transaction;
use App\Enums\TransactionTypeEnum;
use App\Services\IWithdrawService;

class WithdrawService implements IWithdrawService
{
    private function calculateFee(User $user, $amount): float
    {
        $accountType = $user->account_type;
        $fee = 0;
        if ($accountType === AccountTypeEnum::Individual->value) {
            $enjoyFreeCharge = false;
            $isFriday = today()->format('l') === 'Friday' ? true : false;
            $runningMonthTransactionAmount = Transaction::whereMonth('created_at', now())
                ->where('user_id', $user->id)
                ->sum('balance');
            if ($runningMonthTransactionAmount && $runningMonthTransactionAmount <= 5000) {
                $enjoyFreeCharge = true;
            }
            if ($isFriday || $enjoyFreeCharge) {
                $fee = 0;
            } else {
                $appliedAmount = $amount;
                if ($amount <= 1000) {
                    $fee = 0;
                } else {
                    $appliedAmount -= 1000;
                    $fee = (0.015 * $appliedAmount) / 100;
                }
            }
        } else if ($accountType === AccountTypeEnum::Business->value) {
            $lessCharge = Transaction::where('user_id', $user->id)->sum('amount') >= 50_000;
            if ($lessCharge) {
                $fee = (0.015 * $amount) / 100;
            } else {
                $fee = (0.025 * $amount) / 100;
            }
        }

        return $fee;
    }

    public function withdraw(array $attributes): ?array
    {
        $user = User::find($attributes['user_id']);
        if ($user) {
            $user->balance -= $attributes['amount'];
            $user->save();

            $calculatedFee = $this->calculateFee($user, $attributes['amount']);
            $transaction = Transaction::create([
                'user_id' => $attributes['user_id'],
                'transaction_type' => TransactionTypeEnum::Withdrawal->value,
                'amount' => $attributes['amount'],
                'fee' => $calculatedFee,
                'date' => today(),
            ]);

            if ($transaction) {
                return [
                    'new_balance' => $user->balance,
                    'transaction' => $transaction,
                ];
            }
        }
    }
}
