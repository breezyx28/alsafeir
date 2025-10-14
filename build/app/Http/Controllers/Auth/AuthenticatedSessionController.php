<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Attendance;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Carbon\Carbon;

class AuthenticatedSessionController extends Controller
{
    /**
     * عرض صفحة تسجيل الدخول
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * تسجيل الدخول ومعه تسجيل الحضور
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        // ✅ تسجيل الحضور تلقائيًا عند تسجيل الدخول
        $user = Auth::user();

        if ($user && $user->employee_id) {
            Attendance::firstOrCreate(
                [
                    'employee_id' => $user->employee_id,
                    'date' => Carbon::today()->toDateString(),
                ],
                [
                    'check_in' => now(),
                ]
            );
        }

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * تسجيل الخروج ومعه تسجيل الانصراف
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = Auth::user();

        // ✅ تسجيل وقت الانصراف تلقائيًا عند تسجيل الخروج
        if ($user && $user->employee_id) {
            Attendance::where('employee_id', $user->employee_id)
                ->whereDate('date', Carbon::today()->toDateString())
                ->update([
                    'check_out' => now(),
                ]);
        }

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
