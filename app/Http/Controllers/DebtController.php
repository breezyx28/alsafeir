<?php

namespace App\Http\Controllers;

use App\Models\Debt;
use App\Models\DebtInstallment;
use App\Models\JournalEntry;
use App\Models\JournalEntryItem;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DebtController extends Controller
{
    public function index()
    {
        $debts = Debt::with('installments')->latest()->paginate(20);
        return view('debts.index', compact('debts'));
    }

    public function create()
    {
        return view('debts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'phone'       => 'nullable|string|max:20',
            'type'        => 'required|in:customer,supplier',
            'status'      => 'required|in:debit,credit',
            'total_amount'=> 'required|numeric|min:0.01',
            'notes'       => 'nullable|string|max:500',
            'installments_count' => 'nullable|integer|min:1',
            'installment_dates'  => 'nullable|array',
        ]);

        $user = Auth::user();
        $branch_id = $user->employee->branch_id ?? null;

        DB::transaction(function() use ($request, $user, $branch_id) {
            $debt = Debt::create([
                'name'         => $request->name,
                'phone'        => $request->phone,
                'type'         => $request->type,
                'status'       => $request->status,
                'total_amount' => $request->total_amount,
                'notes'        => $request->notes,
            ]);

            if ($request->filled('installments_count') && is_array($request->installment_dates)) {
                $perInstallment = round($request->total_amount / $request->installments_count, 2);
                foreach ($request->installment_dates as $date) {
                    DebtInstallment::create([
                        'debt_id'          => $debt->id,
                        'installment_date' => $date,
                        'amount'           => $perInstallment,
                        'paid'             => 0,
                    ]);
                }
            }

            // حسابات القيد
            $debitAccountId  = $request->status === 'debit' ? Account::where('code', 1100)->value('id') : Account::where('code', 2100)->value('id');
            $creditAccountId = Account::where('code', 5700)->value('id');

            if ($debitAccountId && $creditAccountId) {
                $journal = JournalEntry::create([
                    'entry_date'  => Carbon::now(),
                    'description' => "debt:{$debt->id}", // مرجع ثابت
                    'branch_id'   => $branch_id,
                    'user_id'     => $user->id,
                    'created_by'  => $user->id,
                ]);

                JournalEntryItem::create([
                    'journal_entry_id' => $journal->id,
                    'account_id'       => $debitAccountId,
                    'debit'            => $debt->total_amount,
                    'credit'           => 0,
                    'notes'            => "تسجيل المديونية",
                ]);

                JournalEntryItem::create([
                    'journal_entry_id' => $journal->id,
                    'account_id'       => $creditAccountId,
                    'debit'            => 0,
                    'credit'           => $debt->total_amount,
                    'notes'            => "تسجيل المديونية",
                ]);
            }
        });

        return redirect()->route('debts.index')->with('success', 'تم تسجيل المديونية بنجاح.');
    }

    public function show(Debt $debt)
    {
        $debt->load('installments');
        return view('debts.show', compact('debt'));
    }

    public function edit(Debt $debt)
    {
        $debt->load('installments');
        return view('debts.edit', compact('debt'));
    }

    public function update(Request $request, Debt $debt)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'phone'       => 'nullable|string|max:20',
            'type'        => 'required|in:customer,supplier',
            'status'      => 'required|in:debit,credit',
            'total_amount'=> 'required|numeric|min:0.01',
            'notes'       => 'nullable|string|max:500',
        ]);

        $user = Auth::user();
        $branch_id = $user->employee->branch_id ?? null;

        DB::transaction(function() use ($request, $debt, $user, $branch_id) {
            $debt->update([
                'name'         => $request->name,
                'phone'        => $request->phone,
                'type'         => $request->type,
                'status'       => $request->status,
                'total_amount' => $request->total_amount,
                'notes'        => $request->notes,
            ]);

            $journal = JournalEntry::where('description', "debt:{$debt->id}")->first();
            if ($journal) {
                $journal->update([
                    'branch_id'   => $branch_id,
                    'user_id'     => $user->id,
                    'created_by'  => $user->id,
                ]);

                $items = $journal->items;
                foreach ($items as $item) {
                    if ($item->debit > 0) {
                        $item->update(['debit' => $debt->total_amount]);
                    } else {
                        $item->update(['credit' => $debt->total_amount]);
                    }
                }
            }
        });

        return redirect()->route('debts.index')->with('success', 'تم تحديث المديونية بنجاح.');
    }

    public function destroy(Debt $debt)
    {
        DB::transaction(function() use ($debt) {
            $debt->installments()->delete();
            $journal = JournalEntry::where('description', "debt:{$debt->id}")->first();
            if ($journal) {
                $journal->items()->delete();
                $journal->delete();
            }
            $debt->delete();
        });

        return redirect()->route('debts.index')->with('success', 'تم حذف المديونية بنجاح.');
    }

    public function payInstallment(Request $request, DebtInstallment $installment)
    {
        $request->validate(['amount' => 'required|numeric|min:0.01']);

        $user = Auth::user();
        $branch_id = $user->employee->branch_id ?? null;
        $debt = $installment->debt;

        DB::transaction(function() use ($request, $installment, $user, $branch_id, $debt) {
            $installment->update(['paid' => $installment->paid + $request->amount]);

            $debitAccountId  = $debt->status === 'debit' ? Account::where('code', 1000)->value('id') : Account::where('code', 2000)->value('id'); // كاش/بنك
            $creditAccountId = $debt->status === 'debit' ? Account::where('code', 1100)->value('id') : Account::where('code', 2100)->value('id');

            if ($debitAccountId && $creditAccountId) {
                $journal = JournalEntry::create([
                    'entry_date'  => now(),
                    'description' => "debt:{$debt->id}:installment:{$installment->id}", // مرجع للسداد
                    'branch_id'   => $branch_id,
                    'user_id'     => $user->id,
                    'created_by'  => $user->id,
                ]);

                JournalEntryItem::create([
                    'journal_entry_id' => $journal->id,
                    'account_id'       => $debitAccountId,
                    'debit'            => $request->amount,
                    'credit'           => 0,
                    'notes'            => "سداد المديونية",
                ]);

                JournalEntryItem::create([
                    'journal_entry_id' => $journal->id,
                    'account_id'       => $creditAccountId,
                    'debit'            => 0,
                    'credit'           => $request->amount,
                    'notes'            => "سداد المديونية",
                ]);
            }
        });

        return back()->with('success', 'تم تسجيل سداد القسط بنجاح.');
    }
}
