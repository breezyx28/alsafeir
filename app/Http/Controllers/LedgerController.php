<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;

class LedgerController extends Controller
{
    public function index(Request $request)
    {
        $accountId = $request->get('account_id');
        $accounts = Account::all();

        $entries = collect();
        $balance = 0;

        if ($accountId) {
            $account = Account::findOrFail($accountId);

            $entries = $account->journalEntryItems()
                ->with('journalEntry')
                ->orderBy('journal_entry_id', 'asc')
                ->get();

            foreach ($entries as $entry) {
                $balance += ($entry->debit - $entry->credit);
                $entry->running_balance = $balance;
            }
        } else {
            $account = null;
        }

        return view('ledger.index', compact('accounts', 'entries', 'account'));
    }
}
