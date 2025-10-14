<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ActivityLog;

class ActivityLogController extends Controller
{
   public function index(Request $request)
{
    $query = ActivityLog::with('user');

    if ($request->filled('user_id')) {
        $query->where('user_id', $request->user_id);
    }

    if ($request->filled('date_from')) {
        $query->whereDate('created_at', '>=', $request->date_from);
    }

    if ($request->filled('date_to')) {
        $query->whereDate('created_at', '<=', $request->date_to);
    }

    $logs = $query->latest()->paginate(20);
    $users = \App\Models\User::select('id', 'name')->get();

    return view('admin.activity_logs.index', compact('logs', 'users'));
}

}


