<?php

namespace App\Http\Controllers;

use App\Models\Salary;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Reward;
use App\Models\Deduction;
use App\Models\JournalEntry;
use App\Models\JournalEntryItem;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SalaryController extends Controller
{
    // عرض قائمة المرتبات
    public function index()
    {
        $salaries = Salary::with('employee')->latest()->paginate(15);
        return view('admin.salaries.index', compact('salaries'));
    }

    // نموذج إنشاء مرتب جديد
    public function create()
    {
        $employees = Employee::where('status', true)->get();
        return view('admin.salaries.create', compact('employees'));
    }

    // تنفيذ عملية احتساب المرتب وتسجيل قيد اليومية
    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'month'       => 'required|string',
            'year'        => 'required|string',
            'notes'       => 'nullable|string|max:500',
        ]);

        $employee = Employee::findOrFail($request->employee_id);

        $startDate = Carbon::createFromDate($request->year, $request->month, 1)->startOfMonth();
        $endDate = Carbon::createFromDate($request->year, $request->month, 1)->endOfMonth();

        // حساب أيام الحضور
        $workDays = Attendance::where('employee_id', $employee->id)
                              ->whereBetween('date', [$startDate, $endDate])
                              ->whereNotNull('check_in')
                              ->count();

        $totalDaysInMonth = $startDate->daysInMonth;
        $absentDays = $totalDaysInMonth - $workDays;

        // جمع الخصومات والحوافز
        $deductions = Deduction::where('employee_id', $employee->id)
                            ->whereBetween('date', [$startDate, $endDate])
                            ->sum('amount');

        $rewards = Reward::where('employee_id', $employee->id)
                        ->whereBetween('date', [$startDate, $endDate])
                        ->sum('amount');

        $absenceDeduction = $absentDays * ($employee->salary / $totalDaysInMonth);

        $netSalary = $employee->salary - $absenceDeduction + $rewards - $deductions;

        DB::transaction(function() use ($employee, $request, $workDays, $absentDays, $absenceDeduction, $rewards, $deductions, $netSalary) {

            // إنشاء سجل المرتب
            $salary = Salary::create([
                'employee_id'       => $employee->id,
                'month'             => $request->month,
                'year'              => $request->year,
                'basic_salary'      => $employee->salary,
                'work_days'         => $workDays,
                'absent_days'       => $absentDays,
                'absence_deduction' => $absenceDeduction,
                'bonuses_total'     => $rewards,
                'penalties_total'   => $deductions,
                'net_salary'        => $netSalary,
                'notes'             => $request->notes,
            ]);

            // ===== تسجيل قيد اليومية للمرتبات =====
            $user = Auth::user();

            $salaryExpenseAccountId = Account::where('code', 5100)->value('id'); // مصاريف مرتبات
            $cashAccountId = Account::where('code', 1100)->value('id');          // كاش
            $payableAccountId = Account::where('code', 2200)->value('id');       // ذمم موظفين / مستحقات

            $journalEntry = JournalEntry::create([
                'entry_date' => Carbon::now(),
                'description' => "صرف مرتب - {$employee->name} - {$request->month}/{$request->year}",
                'created_by' => $user->id,
            ]);

            // طرف مدين: مصاريف المرتبات
            JournalEntryItem::create([
                'journal_entry_id' => $journalEntry->id,
                'account_id' => $salaryExpenseAccountId,
                'debit' => $netSalary,
                'credit' => 0,
                'notes' => "صرف مرتب للموظف {$employee->name}",
            ]);

            // طرف دائن: الصندوق أو ذمم الموظف
            JournalEntryItem::create([
                'journal_entry_id' => $journalEntry->id,
                'account_id' => $cashAccountId, // إذا الدفع كاش
                'debit' => 0,
                'credit' => $netSalary,
                'notes' => "تسجيل المستحق للموظف {$employee->name}",
            ]);

        });

        return redirect()->route('admin.salaries.index')->with('success', 'تم احتساب المرتب وتسجيل قيد اليومية بنجاح');
    }
}
