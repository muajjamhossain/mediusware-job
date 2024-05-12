<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Transaction;
use App\Http\Controllers\Controller;
use App\Http\Resources\HomeResource;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function __invoke()
    {
        $transactions = Transaction::paginate();
        $currentBalance = Auth::user()->balance;

        return response()->json(new HomeResource([
            'transactions' => $transactions,
            'currentBalance' => $currentBalance,
        ]));
    }
}
