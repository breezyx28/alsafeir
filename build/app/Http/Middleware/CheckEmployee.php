<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckEmployee
{
    public function handle(Request $request, Closure $next): Response
    {
        // تحقق أولاً إذا كان المستخدم مسجلاً دخوله
        if (!Auth::check()) {
            return redirect('login');
        }

        // تحقق إذا كان المستخدم المرتبط موظفاً ولديه فرع
        if (!Auth::user()->employee || !Auth::user()->employee->branch_id) {
            // إذا لم يكن موظفاً، قم بتسجيل خروجه وأرسله لصفحة خطأ
            Auth::logout();
            return redirect()->route('login')->with('error', 'حسابك غير مصرح له أو غير مرتبط بفرع. الرجاء مراجعة الإدارة.');
        }

        return $next($request);
    }
}
