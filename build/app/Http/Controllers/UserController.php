<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Employee;

class UserController extends Controller
{
    public function assignEmployeeForm()
    {
        $users = User::with('employee')->get();
        $employees = Employee::all();
        return view('users.assign', compact('users', 'employees'));
    }

    public function assignEmployee(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'employee_id' => 'required|exists:employees,id',
        ]);

        $user = User::findOrFail($request->user_id);
        $user->employee_id = $request->employee_id;
        $user->save();

        return back()->with('success', 'تم ربط المستخدم بالموظف بنجاح');
    }
}

