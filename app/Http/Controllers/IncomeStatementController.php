<?php

namespace App\Http\Controllers;

use App\Models\JournalEntryItem;
use App\Models\Account;
use Illuminate\Http\Request;
use Carbon\Carbon;

class IncomeStatementController extends Controller
{
    public function index(Request $request)
    {
        // تحديد الفترة الزمنية (افتراضي آخر شهر)
        $from = $request->from_date ?? Carbon::now()->startOfMonth()->toDateString();
        $to   = $request->to_date ?? Carbon::now()->endOfMonth()->toDateString();

        // جلب جميع حسابات الإيرادات والمصروفات
        $accounts = Account::whereIn('type', ['revenue', 'expense'])->get();

        $data = [];
        $totalDebit = 0;
        $totalCredit = 0;

        foreach ($accounts as $account) {
            $sumDebit  = JournalEntryItem::where('account_id', $account->id)
                ->whereHas('journalEntry', function($q) use ($from, $to) {
                    $q->whereBetween('entry_date', [$from, $to]);
                })->sum('debit');

            $sumCredit = JournalEntryItem::where('account_id', $account->id)
                ->whereHas('journalEntry', function($q) use ($from, $to) {
                    $q->whereBetween('entry_date', [$from, $to]);
                })->sum('credit');

            $data[] = [
                'account' => $account->name,
                'debit'   => $sumDebit,
                'credit'  => $sumCredit,
                'balance' => $sumCredit - $sumDebit, // إيجابيات الدخل صافي ربح/خسارة
            ];

            $totalDebit  += $sumDebit;
            $totalCredit += $sumCredit;
        }

        $netProfit = $totalCredit - $totalDebit; // صافي الربح: الدائن - المدين

        return view('income_statement.index', compact('data', 'totalDebit', 'totalCredit', 'netProfit', 'from', 'to'));
    }
}
