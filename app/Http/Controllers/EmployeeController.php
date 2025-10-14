<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Branch;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::with('branch')->paginate(10);
        return view('employees.index', compact('employees'));
    }

    public function create()
    {
        $branches = Branch::all();
        return view('employees.create', compact('branches'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:employees,email',
            'phone'       => 'nullable|string|max:20',
            'branch_id'   => 'required|exists:branches,id',
            'position'    => 'nullable|string|max:255',
            'national_id' => 'nullable|string|max:20',
            'hiring_date' => 'nullable|date',
            'salary'      => 'nullable|numeric',
            'status'      => 'required|boolean',
        ]);

        Employee::create($request->all());

        return redirect()->route('employees.index')->with('success', 'تمت إضافة الموظف بنجاح');
    }

    public function edit(Employee $employee)
    {
        $branches = Branch::all();
        return view('employees.edit', compact('employee', 'branches'));
    }

    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'nullable|email|unique:employees,email,' . $employee->id,
            'phone'       => 'nullable|string|max:20',
            'national_id' => 'nullable|string|max:20',
            'hiring_date' => 'nullable|date',
            'position'    => 'nullable|string|max:100',
            'salary'      => 'nullable|numeric',
            'status'      => 'required|boolean',
            'branch_id'   => 'required|exists:branches,id',
        ]);

        $employee->update($request->all());

        return redirect()->route('employees.index')->with('success', 'تم تحديث بيانات الموظف');
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()->route('employees.index')->with('success', 'تم حذف الموظف');
    }

    public function ordersReport(Request $request, Employee $employee)
    {
        $startDate = $request->start_date ? Carbon::parse($request->start_date)->startOfDay() : null;
        $endDate = $request->end_date ? Carbon::parse($request->end_date)->endOfDay() : null;

        $query = Order::with('customer', 'measurements')
            ->where('employee_id', $employee->id);

        if ($startDate && $endDate) {
            $query->whereBetween('completed_at', [$startDate, $endDate]);
        }

        $orders = $query->get();

        return view('employees.orders_report', compact('employee', 'orders', 'startDate', 'endDate'));
    }

    public function ordersReportPrint(Request $request, Employee $employee)
    {
        $startDate = $request->start_date ? Carbon::parse($request->start_date)->startOfDay() : null;
        $endDate = $request->end_date ? Carbon::parse($request->end_date)->endOfDay() : null;

        $query = Order::with('customer', 'measurements')
            ->where('employee_id', $employee->id);

        if ($startDate && $endDate) {
            $query->whereBetween('completed_at', [$startDate, $endDate]);
        }

        $orders = $query->get();

        return view('employees.orders_report_print', compact('employee', 'orders', 'startDate', 'endDate'));
    }
}
