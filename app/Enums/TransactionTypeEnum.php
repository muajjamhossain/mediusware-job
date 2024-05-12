<?php

namespace App\Enums;

enum TransactionTypeEnum: string
{
    case Deposit = 'Deposit';
    case Withdrawal = 'Withdrawal';
}
