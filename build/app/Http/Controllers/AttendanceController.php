<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    /**
     * عرض سجل الحضور الخاص بالمستخدم المرتبط بموظف.
     */
    public function index()
    {
        $employee = Auth::user()->employee;

        if (!$employee) {
            return redirect()->back()->with('error', 'المستخدم غير مرتبط بموظف.');
        }

        $attendances = Attendance::where('employee_id', $employee->id)
            ->latest()
            ->paginate(10);

        return view('attendance.index', compact('attendances'));
    }

    /**
     * تسجيل الحضور أو الانصراف بناءً على حالة اليوم.
     */
    public function store(Request $request)
    {
        $employee = Auth::user()->employee;

        if (!$employee) {
            return redirect()->back()->with('error', 'المستخدم غير مرتبط بموظف.');
        }

        $today = now()->toDateString();

        $attendance = Attendance::firstOrNew([
            'employee_id' => $employee->id,
            'date' => $today,
        ]);

        // إذا لم يكن سجل دخول موجود، نسجل الدخول
        if (!$attendance->exists || $attendance->check_in === null) {
            $attendance->check_in = now();
            $attendance->save();

            return redirect()->route('attendance.index')
                ->with('success', 'تم تسجيل وقت الحضور بنجاح.');
        }

        // إذا كان الدخول مسجل ولكن الخروج غير مسجل، نسجل الخروج
        if ($attendance->check_in && !$attendance->check_out) {
            $attendance->check_out = now();
            $attendance->save();

            return redirect()->route('attendance.index')
                ->with('success', 'تم تسجيل وقت الانصراف بنجاح.');
        }

        // إذا تم تسجيل الدخول والخروج مسبقًا
        return redirect()->route('attendance.index')
            ->with('info', 'تم تسجيل الحضور والانصراف بالفعل لليوم.');
    }
}
