<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{

    public function index(Request $request)
{
    $query = Attendance::with('employee.branch');

    if ($request->filled('branch_id')) {
        $query->whereHas('employee', function ($q) use ($request) {
            $q->where('branch_id', $request->branch_id);
        });
    }

    if ($request->filled('employee_id')) {
        $query->where('employee_id', $request->employee_id);
    }

    if ($request->filled('from')) {
        $query->whereDate('date', '>=', $request->from);
    }

    if ($request->filled('to')) {
        $query->whereDate('date', '<=', $request->to);
    }

    $attendances = $query->orderBy('date', 'desc')->paginate(15);

    $branches = \App\Models\Branch::all();
    $employees = \App\Models\Employee::all();

    return view('admin.attendance.index', compact('attendances', 'branches', 'employees'));
}


}

