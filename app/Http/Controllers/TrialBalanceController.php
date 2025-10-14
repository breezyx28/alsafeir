<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;

class TrialBalanceController extends Controller
{
    public function index(Request $request)
    {
        // الفترة الزمنية المطلوبة
        $startDate = $request->start_date ?? now()->startOfMonth()->toDateString();
        $endDate   = $request->end_date ?? now()->endOfMonth()->toDateString();

        // جلب جميع الحسابات مع القيد المرتبط في الفترة المحددة
        $accounts = Account::with(['journalEntryItems.journalEntry' => function($q) use ($startDate, $endDate) {
            $q->whereBetween('entry_date', [$startDate, $endDate]);
        }])->get();

        $trialBalance = [];

        foreach ($accounts as $account) {
            $debitTotal  = $account->journalEntryItems->sum('debit');
            $creditTotal = $account->journalEntryItems->sum('credit');

            $trialBalance[] = [
                'account_name' => $account->name,
                'debit'        => max($debitTotal - $creditTotal, 0),
                'credit'       => max($creditTotal - $debitTotal, 0),
            ];
        }

        $totalDebit  = collect($trialBalance)->sum('debit');
        $totalCredit = collect($trialBalance)->sum('credit');

        return view('trial_balance.index', compact(
            'trialBalance', 'totalDebit', 'totalCredit', 'startDate', 'endDate'
        ));
    }
}
