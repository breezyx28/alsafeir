<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use App\Models\Employee;
use App\Models\Order;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ShiftController extends Controller
{
    /**
     * عرض قائمة الورديات
     */
    public function index()
    {
        $shifts = Shift::with('employee')->latest()->paginate(20);

        return view('shifts.index', compact('shifts'));
    }

    /**
     * شاشة إنشاء وردية جديدة
     */
    public function create()
    {
        $employees = Employee::all();
        return view('shifts.create', compact('employees'));
    }

    /**
     * تخزين وردية جديدة
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'start_time'  => 'required|date',
            'end_time'    => 'required|date|after:start_time',
        ]);

        Shift::create($data);

        return redirect()->route('shifts.index')->with('success', 'تم إنشاء الوردية بنجاح');
    }

    /**
     * عرض تفاصيل وردية
     */
    public function show(Shift $shift)
    {
        // نجيب الطلبات اللي خلصت خلال زمن الوردية للموظف المحدد
        $orders = Order::where('employee_id', $shift->employee_id)
            ->whereBetween('completed_at', [$shift->start_time, $shift->end_time])
            ->with('customer')
            ->get();

        return view('shifts.show', compact('shift', 'orders'));
    }

    /**
     * شاشة تعديل وردية
     */
    public function edit(Shift $shift)
    {
        $employees = Employee::all();
        return view('shifts.edit', compact('shift', 'employees'));
    }

    /**
     * تحديث وردية
     */
    public function update(Request $request, Shift $shift)
    {
        $data = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'start_time'  => 'required|date',
            'end_time'    => 'required|date|after:start_time',
        ]);

        $shift->update($data);

        return redirect()->route('shifts.index')->with('success', 'تم تحديث بيانات الوردية بنجاح');
    }

    /**
     * حذف وردية
     */
    public function destroy(Shift $shift)
    {
        $shift->delete();
        return redirect()->route('shifts.index')->with('success', 'تم حذف الوردية');
    }

    /**
     * طباعة تقرير الطلبات الخاصة بالوردية
     */
    public function print(Shift $shift)
    {
        $orders = Order::where('employee_id', $shift->employee_id)
            ->whereBetween('completed_at', [$shift->start_time, $shift->end_time])
            ->with('customer')
            ->get();

        return view('shifts.print', compact('shift', 'orders'));
    }
}
