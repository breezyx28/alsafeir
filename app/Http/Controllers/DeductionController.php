<?php

namespace App\Http\Controllers;

use App\Models\Deduction;
use App\Models\Employee;
use App\Models\Account;
use App\Models\JournalEntry;
use App\Models\JournalEntryItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DeductionController extends Controller
{
    public function index()
    {
        $deductions = Deduction::with('employee')->latest()->paginate(10);
        return view('admin.deductions.index', compact('deductions'));
    }

    public function create()
    {
        $employees = Employee::all();
        return view('admin.deductions.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'amount' => 'required|numeric|min:0',
            'reason' => 'nullable|string',
            'date' => 'required|date',
        ]);

        $user = Auth::user();

        DB::transaction(function() use ($request, $user) {
            // حفظ الخصم
            $deduction = Deduction::create($request->all());

            // إنشاء القيد اليومي للخصم
            $journal = JournalEntry::create([
                'entry_date' => $deduction->date,
                'description' => 'خصم موظف: ' . $deduction->employee->name,
                'user_id' => $user->id,
                'created_by' => $user->id,
                // 'reference' => "deduction:{$deduction->id}",
            ]);

            $expenseAccount = Account::where('code', '5200')->firstOrFail(); // مصروفات خصومات الموظفين
            $cashAccount    = Account::where('code', '1100')->firstOrFail(); // الصندوق

            // مدين: حساب المصروف
            JournalEntryItem::create([
                'journal_entry_id' => $journal->id,
                'account_id' => $expenseAccount->id,
                'debit' => $deduction->amount,
                'credit' => 0,
                'notes' => $deduction->reason,
            ]);

            // دائن: الصندوق
            JournalEntryItem::create([
                'journal_entry_id' => $journal->id,
                'account_id' => $cashAccount->id,
                'debit' => 0,
                'credit' => $deduction->amount,
                'notes' => 'خصم موظف: ' . $deduction->employee->name,
            ]);
        });

        return redirect()->route('admin.deductions.index')->with('success', 'تم إضافة الخصم وإنشاء القيد المحاسبي بنجاح');
    }

    public function edit(Deduction $deduction)
    {
        $employees = Employee::all();
        return view('admin.deductions.edit', compact('deduction', 'employees'));
    }

    public function update(Request $request, Deduction $deduction)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'amount' => 'required|numeric|min:0',
            'reason' => 'nullable|string',
            'date' => 'required|date',
        ]);

        $user = Auth::user();

        DB::transaction(function() use ($request, $deduction, $user) {
            // تحديث الخصم
            $deduction->update($request->all());

            // تحديث القيد المرتبط
             $journal = JournalEntry::where('description', 'like', "%{$deduction->id}%")->first();

            if ($journal) {
                $journal->update([
                    'entry_date' => $request->date,
                    'description' => 'خصم موظف: ' . $deduction->employee->name,
                    'created_by' => $user->id,
                ]);

                foreach ($journal->items as $item) {
                    if ($item->debit > 0) {
                        $item->update([
                            'account_id' => Account::where('code', '5200')->value('id'),
                            'debit' => $request->amount,
                            'credit' => 0,
                            'notes' => $request->reason,
                        ]);
                    } else {
                        $item->update([
                            'account_id' => Account::where('code', '1100')->value('id'),
                            'debit' => 0,
                            'credit' => $request->amount,
                            'notes' => 'خصم موظف: ' . $deduction->employee->name,
                        ]);
                    }
                }
            }
        });

        return redirect()->route('admin.deductions.index')->with('success', 'تم تحديث بيانات الخصم بنجاح');
    }

    public function destroy(Deduction $deduction)
    {
        DB::transaction(function() use ($deduction) {
            // حذف القيد المرتبط
           $journal = JournalEntry::where('description', 'like', "%{$deduction->id}%")->first();
            if ($journal) {
                $journal->items()->delete();
                $journal->delete();
            }

            // حذف الخصم نفسه
            $deduction->delete();
        });

        return redirect()->route('admin.deductions.index')->with('success', 'تم حذف الخصم بنجاح');
    }
}
