<?php

namespace App\Http\Controllers;

use App\Models\JournalEntryItem;
use App\Models\Account;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CashFlowController extends Controller
{
    public function index(Request $request)
    {
        // تحديد الفترة الزمنية (افتراضي آخر شهر)
        $from = $request->from_date ?? Carbon::now()->startOfMonth()->toDateString();
        $to   = $request->to_date ?? Carbon::now()->endOfMonth()->toDateString();

        // حسابات النقدية: الكاش والبنوك
        $cashAccounts = Account::whereIn('code', [1000, 1010, 1020])->get(); // مثال: 1000 كاش، 1010 بنك، 1020 حسابات أخرى

        $data = [];
        $totalIn  = 0;
        $totalOut = 0;

        foreach ($cashAccounts as $account) {
            $sumDebit = JournalEntryItem::where('account_id', $account->id)
                ->whereHas('journalEntry', function($q) use ($from, $to) {
                    $q->whereBetween('entry_date', [$from, $to]);
                })->sum('debit');

            $sumCredit = JournalEntryItem::where('account_id', $account->id)
                ->whereHas('journalEntry', function($q) use ($from, $to) {
                    $q->whereBetween('entry_date', [$from, $to]);
                })->sum('credit');

            $data[] = [
                'account' => $account->name,
                'inflow'  => $sumDebit,
                'outflow' => $sumCredit,
                'balance' => $sumDebit - $sumCredit,
            ];

            $totalIn  += $sumDebit;
            $totalOut += $sumCredit;
        }

        $netCash = $totalIn - $totalOut;

        return view('cash_flow.index', compact('data', 'totalIn', 'totalOut', 'netCash', 'from', 'to'));
    }
}
