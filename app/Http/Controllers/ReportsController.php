<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Order;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportsController extends Controller
{
    // شاشة التقرير مع فلتر التاريخ والموظف
    public function employeeOrders(Request $request)
    {
        $employees = Employee::all();
        $employeeId = $request->employee_id;
        $startDate = $request->start_date ? Carbon::parse($request->start_date)->startOfDay() : null;
        $endDate = $request->end_date ? Carbon::parse($request->end_date)->endOfDay() : null;

        $orders = Order::with('customer', 'measurements');

        if ($employeeId) {
            $orders->where('employee_id', $employeeId);
        }
        if ($startDate && $endDate) {
            $orders->whereBetween('order_date', [$startDate, $endDate]);
        }

        $orders = $orders->get();

        return view('reports.employee_orders', compact('employees', 'orders', 'employeeId', 'startDate', 'endDate'));
    }

    // نسخة للطباعة
    public function employeeOrdersPrint(Request $request)
    {
        $employeeId = $request->employee_id;
        $startDate = $request->start_date ? Carbon::parse($request->start_date)->startOfDay() : null;
        $endDate = $request->end_date ? Carbon::parse($request->end_date)->endOfDay() : null;

        $orders = Order::with('customer', 'measurements')
            ->when($employeeId, fn($q) => $q->where('employee_id', $employeeId))
            ->when($startDate && $endDate, fn($q) => $q->whereBetween('created_at', [$startDate, $endDate]))
            ->get();

        $employee = $employeeId ? Employee::find($employeeId) : null;

        return view('reports.employee_orders_print', compact('orders', 'employee', 'startDate', 'endDate'));
    }
}
