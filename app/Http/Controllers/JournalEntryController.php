<?php

namespace App\Http\Controllers;

use App\Models\JournalEntry;
use App\Models\JournalEntryItem;
use App\Models\Account;
use App\Models\GeneralLedger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class JournalEntryController extends Controller
{
    public function index()
    {
        $entries = JournalEntry::with('items.account')->latest()->paginate(20);
        return view('journal_entries.index', compact('entries'));
    }

    public function create()
    {
        $accounts = Account::all();
        return view('journal_entries.create', compact('accounts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'entry_date' => 'required|date',
            'description' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.account_id' => 'required|exists:accounts,id',
            'items.*.debit' => 'nullable|numeric|min:0',
            'items.*.credit' => 'nullable|numeric|min:0',
        ]);

        // تحقق أن مجموع المدين = مجموع الدائن
        $totalDebit = collect($request->items)->sum(fn($item) => floatval($item['debit'] ?? 0));
        $totalCredit = collect($request->items)->sum(fn($item) => floatval($item['credit'] ?? 0));

        if ($totalDebit !== $totalCredit) {
            return back()->withErrors(['items' => 'مجموع المدين لا يساوي مجموع الدائن.'])->withInput();
        }

        DB::transaction(function () use ($request) {
            $entry = JournalEntry::create([
                'entry_date'  => $request->entry_date,
                'description' => $request->description,
                'created_by'  => Auth::id(),
            ]);

            foreach ($request->items as $item) {
                $journalItem = JournalEntryItem::create([
                    'journal_entry_id' => $entry->id,
                    'account_id'       => $item['account_id'],
                    'debit'            => $item['debit'] ?? 0,
                    'credit'           => $item['credit'] ?? 0,
                    'notes'            => $item['notes'] ?? null,
                ]);

                // 📌 الترحيل التلقائي إلى دفتر الأستاذ
                GeneralLedger::create([
                    'journal_entry_id' => $entry->id,
                    'account_name'     => Account::find($item['account_id'])->name,
                    'debit'            => $item['debit'] ?? 0,
                    'credit'           => $item['credit'] ?? 0,
                    'balance'          => ($item['debit'] ?? 0) - ($item['credit'] ?? 0),
                    'entry_date'       => $request->entry_date,
                ]);
            }
        });

        return redirect()->route('journal_entries.index')->with('success', 'تم حفظ القيد المحاسبي وترحيله إلى الأستاذ بنجاح.');
    }

    public function show($id)
    {
        $entry = JournalEntry::with('items.account')->findOrFail($id);
        return view('journal_entries.show', compact('entry'));
    }
}
