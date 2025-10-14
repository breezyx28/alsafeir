<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight">📜 سجل النشاطات</h2>
    </x-slot>


    <div class="container py-4">
            <form method="GET" class="row mb-3">
    <div class="col-md-3">
        <label>المستخدم</label>
        <select name="user_id" class="form-control">
            <option value="">كل المستخدمين</option>
            @foreach ($users as $user)
                <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                    {{ $user->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-3">
        <label>من تاريخ</label>
        <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
    </div>

    <div class="col-md-3">
        <label>إلى تاريخ</label>
        <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
    </div>

    <div class="col-md-3 d-flex align-items-end">
        <button type="submit" class="btn btn-primary me-2">🔍 بحث</button>
        <a href="{{ route('admin.activity_logs.index') }}" class="btn btn-secondary">🔄 إعادة تعيين</a>
    </div>
</form>
        <div class="card p-4">
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th>العملية</th>
                            <th>الوصف</th>
                            <th>النموذج</th>
                            <th>المعرف</th>
                            <th>المستخدم</th>
                            <th>التاريخ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($logs as $log)
                            <tr>
                                <td>{{ $log->action }}</td>
                                <td>{{ $log->description }}</td>
                                <td>{{ $log->model_type }}</td>
                                <td>{{ $log->model_id }}</td>
                                <td>{{ $log->user?->name ?? '-' }}</td>
                                <td>{{ $log->created_at->format('Y-m-d H:i') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center">لا توجد سجلات</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $logs->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
