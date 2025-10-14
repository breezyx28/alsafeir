<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\JournalEntryItem;
use Illuminate\Http\Request;


class BalanceSheetController extends Controller
{
   public function index(Request $request)
    {
        // تحديد الفترة الزمنية (افتراضي: من بداية الشهر حتى اليوم)
        $from = $request->from_date ?? now()->startOfMonth()->toDateString();
        $to   = $request->to_date ?? now()->endOfMonth()->toDateString();

        // جلب الحسابات حسب النوع
        $assets = Account::where('type', 'asset')->get()->map(function($acc) use ($from, $to) {
            $debit  = JournalEntryItem::where('account_id', $acc->id)
                        ->whereHas('journalEntry', fn($q) => $q->whereBetween('entry_date', [$from, $to]))
                        ->sum('debit');
            $credit = JournalEntryItem::where('account_id', $acc->id)
                        ->whereHas('journalEntry', fn($q) => $q->whereBetween('entry_date', [$from, $to]))
                        ->sum('credit');
            return [
                'name' => $acc->name,
                'balance' => $debit - $credit,
            ];
        });

        $liabilities = Account::where('type', 'liability')->get()->map(function($acc) use ($from, $to) {
            $debit  = JournalEntryItem::where('account_id', $acc->id)
                        ->whereHas('journalEntry', fn($q) => $q->whereBetween('entry_date', [$from, $to]))
                        ->sum('debit');
            $credit = JournalEntryItem::where('account_id', $acc->id)
                        ->whereHas('journalEntry', fn($q) => $q->whereBetween('entry_date', [$from, $to]))
                        ->sum('credit');
            return [
                'name' => $acc->name,
                'balance' => $credit - $debit,
            ];
        });

        $equity = Account::where('type', 'equity')->get()->map(function($acc) use ($from, $to) {
            $debit  = JournalEntryItem::where('account_id', $acc->id)
                        ->whereHas('journalEntry', fn($q) => $q->whereBetween('entry_date', [$from, $to]))
                        ->sum('debit');
            $credit = JournalEntryItem::where('account_id', $acc->id)
                        ->whereHas('journalEntry', fn($q) => $q->whereBetween('entry_date', [$from, $to]))
                        ->sum('credit');
            return [
                'name' => $acc->name,
                'balance' => $credit - $debit,
            ];
        });

        $totalAssets = $assets->sum('balance');
        $totalLiabilities = $liabilities->sum('balance');
        $totalEquity = $equity->sum('balance');

        return view('balance_sheet.index', compact('assets', 'liabilities', 'equity', 'totalAssets', 'totalLiabilities', 'totalEquity', 'from', 'to'));
    }
}


