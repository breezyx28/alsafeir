<?php

namespace App\Http\Controllers;

use App\Models\Reward;
use App\Models\Employee;
use App\Models\JournalEntry;
use App\Models\JournalEntryItem;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RewardController extends Controller
{
    public function index()
    {
        $rewards = Reward::with('employee')->latest()->paginate(10);
        return view('admin.rewards.index', compact('rewards'));
    }

    public function create()
    {
        $employees = Employee::all();
        return view('admin.rewards.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'reason' => 'nullable|string|max:255',
        ]);

        $employee = Employee::findOrFail($request->employee_id);

        DB::transaction(function() use ($request, $employee) {
            $reward = Reward::create($request->all());

            // جلب الحسابات
            $expenseAccountId = Account::where('code', 6200)->value('id'); // مصاريف حوافز
            $payableAccountId = Account::where('code', 2200)->value('id'); // مستحقات الموظفين

            if (!$expenseAccountId || !$payableAccountId) {
                throw new \Exception("الحسابات المطلوبة للحوافز غير موجودة");
            }

            $user = Auth::user();

            // إنشاء قيد اليومية
            $journalEntry = JournalEntry::create([
                'entry_date' => $request->date,
                'description' => "حافز موظف: {$employee->name}",
                'created_by' => $user->id,
            ]);

            // طرف مدين: مصاريف الحوافز
            JournalEntryItem::create([
                'journal_entry_id' => $journalEntry->id,
                'account_id' => $expenseAccountId,
                'debit' => $request->amount,
                'credit' => 0,
                'notes' => "حافز الموظف {$employee->name}",
            ]);

            // طرف دائن: مستحقات الموظف
            JournalEntryItem::create([
                'journal_entry_id' => $journalEntry->id,
                'account_id' => $payableAccountId,
                'debit' => 0,
                'credit' => $request->amount,
                'notes' => "حافز مستحق للموظف {$employee->name}",
            ]);
        });

        return redirect()->route('admin.rewards.index')->with('success', 'تمت إضافة الحافز وتسجيل القيد بنجاح');
    }

    public function edit(Reward $reward)
    {
        $employees = Employee::all();
        return view('admin.rewards.edit', compact('reward', 'employees'));
    }

    public function update(Request $request, Reward $reward)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'reason' => 'nullable|string|max:255',
        ]);

        $employee = Employee::findOrFail($request->employee_id);

        DB::transaction(function() use ($request, $reward, $employee) {
            $oldAmount = $reward->amount;
            $reward->update($request->all());

            $diff = $request->amount - $oldAmount;
            if ($diff == 0) return;

            $expenseAccountId = Account::where('code', 6200)->value('id');
            $payableAccountId = Account::where('code', 2200)->value('id');

            if (!$expenseAccountId || !$payableAccountId) {
                throw new \Exception("الحسابات المطلوبة للحوافز غير موجودة");
            }

            $user = Auth::user();
            $journalEntry = JournalEntry::create([
                'entry_date' => $request->date,
                'description' => "تعديل حافز موظف: {$employee->name}",
                'created_by' => $user->id,
            ]);

            if ($diff > 0) {
                // زيادة
                JournalEntryItem::create([
                    'journal_entry_id' => $journalEntry->id,
                    'account_id' => $expenseAccountId,
                    'debit' => $diff,
                    'credit' => 0,
                    'notes' => "زيادة حافز {$employee->name}",
                ]);
                JournalEntryItem::create([
                    'journal_entry_id' => $journalEntry->id,
                    'account_id' => $payableAccountId,
                    'debit' => 0,
                    'credit' => $diff,
                    'notes' => "زيادة مستحقات {$employee->name}",
                ]);
            } else {
                // نقصان
                $diff = abs($diff);
                JournalEntryItem::create([
                    'journal_entry_id' => $journalEntry->id,
                    'account_id' => $expenseAccountId,
                    'debit' => 0,
                    'credit' => $diff,
                    'notes' => "نقصان حافز {$employee->name}",
                ]);
                JournalEntryItem::create([
                    'journal_entry_id' => $journalEntry->id,
                    'account_id' => $payableAccountId,
                    'debit' => $diff,
                    'credit' => 0,
                    'notes' => "نقصان مستحقات {$employee->name}",
                ]);
            }
        });

        return redirect()->route('admin.rewards.index')->with('success', 'تم تعديل الحافز وتسجيل القيد بنجاح');
    }

    public function destroy(Reward $reward)
    {
        DB::transaction(function() use ($reward) {
            $expenseAccountId = Account::where('code', 6200)->value('id');
            $payableAccountId = Account::where('code', 2200)->value('id');

            if (!$expenseAccountId || !$payableAccountId) {
                throw new \Exception("الحسابات المطلوبة للحوافز غير موجودة");
            }

            $user = Auth::user();
            $journalEntry = JournalEntry::create([
                'entry_date' => Carbon::now(),
                'description' => "حذف حافز موظف: {$reward->employee->name}",
                'created_by' => $user->id,
            ]);

            JournalEntryItem::create([
                'journal_entry_id' => $journalEntry->id,
                'account_id' => $expenseAccountId,
                'debit' => 0,
                'credit' => $reward->amount,
                'notes' => "إلغاء حافز {$reward->employee->name}",
            ]);
            JournalEntryItem::create([
                'journal_entry_id' => $journalEntry->id,
                'account_id' => $payableAccountId,
                'debit' => $reward->amount,
                'credit' => 0,
                'notes' => "إلغاء مستحقات {$reward->employee->name}",
            ]);

            $reward->delete();
        });

        return redirect()->route('admin.rewards.index')->with('success', 'تم حذف الحافز وتسجيل القيد بنجاح');
    }
}
