<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Account;
use App\Models\JournalEntry;
use App\Models\JournalEntryItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExpensesController extends Controller
{
    // قائمة المنصرفات
    public function index()
    {
        $expenses = Expense::with(['expenseAccount', 'cashAccount', 'user'])
            ->latest()
            ->paginate(20);

        return view('expenses.index', compact('expenses'));
    }

    // نموذج إنشاء صرف جديد
    public function create()
    {
        // حسابات المصروفات
        $expenseAccounts = Account::where('type', 'expense')->orderBy('code')->get();

        // حسابات الدفع (اختر من الأصول - الصندوق/البنك ضمن الأصول)
        $cashAccounts = Account::where('type', 'asset')->orderBy('code')->get();

        return view('expenses.create', compact('expenseAccounts', 'cashAccounts'));
    }

    // حفظ المنصرف + إنشاء قيد اليومية
    public function store(Request $request)
    {
        $request->validate([
            'expense_date'      => 'required|date',
            'amount'            => 'required|numeric|min:0.01',
            'expense_account_id'=> 'required|exists:accounts,id',
            'cash_account_id'   => 'required|exists:accounts,id',
            'notes'             => 'nullable|string',
        ]);

        $user = Auth::user();
        $branchId = optional($user->employee)->branch_id; // لو عندك relation employee->branch_id

        DB::transaction(function () use ($request, $user, $branchId) {
            // 1) إنشاء القيد الرئيسي
            $expenseAccountName = Account::where('id', $request->expense_account_id)->value('name') ?? 'مصروف';
            $journal = JournalEntry::create([
                'entry_date'  => $request->expense_date,
                'description' => "إثبات منصرف: {$expenseAccountName}" . ($request->notes ? " - {$request->notes}" : ''),
                'created_by'  => $user->id,
            ]);

            // 2) إنشاء عناصر القيد: مدين مصروف - دائن صندوق/بنك
            JournalEntryItem::create([
                'journal_entry_id' => $journal->id,
                'account_id'       => $request->expense_account_id, // مصروف
                'debit'            => $request->amount,
                'credit'           => 0,
                'notes'            => 'إثبات مصروف',
            ]);

            JournalEntryItem::create([
                'journal_entry_id' => $journal->id,
                'account_id'       => $request->cash_account_id, // صندوق/بنك
                'debit'            => 0,
                'credit'           => $request->amount,
                'notes'            => 'دفع المصروف',
            ]);

            // 3) إنشاء سجل المنصرف نفسه وربطه بالقيد
            Expense::create([
                'expense_date'       => $request->expense_date,
                'amount'             => $request->amount,
                'expense_account_id' => $request->expense_account_id,
                'cash_account_id'    => $request->cash_account_id,
                'branch_id'          => $branchId,
                'user_id'            => $user->id,
                'notes'              => $request->notes,
                'journal_entry_id'   => $journal->id,
            ]);
        });

        return redirect()->route('expenses.index')->with('success', 'تم إضافة المنصرف وقيده المحاسبي بنجاح');
    }

    // عرض تفاصيل صرف
    public function show(Expense $expense)
    {
        $expense->load(['expenseAccount', 'cashAccount', 'journalEntry', 'user']);
        return view('expenses.show', compact('expense'));
    }

    // نموذج تعديل صرف
    public function edit(Expense $expense)
    {
        $expenseAccounts = Account::where('type', 'expense')->orderBy('code')->get();
        $cashAccounts = Account::where('type', 'asset')->orderBy('code')->get();

        return view('expenses.edit', compact('expense', 'expenseAccounts', 'cashAccounts'));
    }

    // تحديث صرف + تحديث القيد المرتبط
    public function update(Request $request, Expense $expense)
    {
        $request->validate([
            'expense_date'      => 'required|date',
            'amount'            => 'required|numeric|min:0.01',
            'expense_account_id'=> 'required|exists:accounts,id',
            'cash_account_id'   => 'required|exists:accounts,id',
            'notes'             => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $expense) {
            // تحديث المنصرف
            $expense->update([
                'expense_date'       => $request->expense_date,
                'amount'             => $request->amount,
                'expense_account_id' => $request->expense_account_id,
                'cash_account_id'    => $request->cash_account_id,
                'notes'              => $request->notes,
            ]);

            // تحديث القيد
            if ($expense->journal_entry_id) {
                $journal = JournalEntry::find($expense->journal_entry_id);
                if ($journal) {
                    $expenseAccountName = Account::where('id', $request->expense_account_id)->value('name') ?? 'مصروف';
                    $journal->update([
                        'entry_date'  => $request->expense_date,
                        'description' => "إثبات منصرف: {$expenseAccountName}" . ($request->notes ? " - {$request->notes}" : ''),
                    ]);

                    // نحدّث البنود (نفترض عندنا بندين فقط)
                    foreach ($journal->items as $item) {
                        if ($item->debit > 0) {
                            $item->update([
                                'account_id' => $request->expense_account_id,
                                'debit'      => $request->amount,
                                'credit'     => 0,
                            ]);
                        } else {
                            $item->update([
                                'account_id' => $request->cash_account_id,
                                'debit'      => 0,
                                'credit'     => $request->amount,
                            ]);
                        }
                    }
                }
            }
        });

        return redirect()->route('expenses.index')->with('success', 'تم تعديل المنصرف وقيده بنجاح');
    }

    // حذف صرف + حذف قيده
    public function destroy(Expense $expense)
    {
        DB::transaction(function () use ($expense) {
            if ($expense->journal_entry_id) {
                $journal = JournalEntry::find($expense->journal_entry_id);
                if ($journal) {
                    $journal->items()->delete();
                    $journal->delete();
                }
            }
            $expense->delete();
        });

        return redirect()->route('expenses.index')->with('success', 'تم حذف المنصرف وقيده بنجاح');
    }
}
