<?php

namespace App\Services;
interface IWithdrawService
{
    public function withdraw(array $attr): ?array;
}
