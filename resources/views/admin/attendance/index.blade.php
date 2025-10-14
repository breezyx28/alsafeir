<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight">سجل حضور الموظفين</h2>
    </x-slot>

    <div class="container py-5">
        <div class="card mb-4">
            <div class="card-header text-right fw-bold">فلترة السجلات</div>
            <div class="card-body">
                <form method="GET" class="row g-3 text-end">
                    <div class="col-md-3">
                        <label>الفرع</label>
                        <select name="branch_id" class="form-control">
                            <option value="">-- الكل --</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                                    {{ $branch->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label>الموظف</label>
                        <select name="employee_id" class="form-control">
                            <option value="">-- الكل --</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}" {{ request('employee_id') == $employee->id ? 'selected' : '' }}>
                                    {{ $employee->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label>من تاريخ</label>
                        <input type="date" name="from" value="{{ request('from') }}" class="form-control">
                    </div>

                    <div class="col-md-2">
                        <label>إلى تاريخ</label>
                        <input type="date" name="to" value="{{ request('to') }}" class="form-control">
                    </div>

                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">🔍 فلترة</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header text-right">
                <strong>جميع سجلات الحضور</strong>
            </div>

            <div class="card-body table-responsive">
                <table class="table table-bordered text-center">
                    <thead class="table-light">
                        <tr>
                            <th>اسم الموظف</th>
                            <th>الفرع</th>
                            <th>التاريخ</th>
                            <th>وقت الحضور</th>
                            <th>وقت الانصراف</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($attendances as $attendance)
                            <tr>
                                <td>{{ $attendance->employee->name ?? 'غير معروف' }}</td>
                                <td>{{ $attendance->employee->branch->name ?? '-' }}</td>
                                <td>{{ \Carbon\Carbon::parse($attendance->date)->format('Y-m-d') }}</td>
                                <td>
                                    {{ $attendance->check_in ? \Carbon\Carbon::parse($attendance->check_in)->format('H:i:s') : '-' }}
                                </td>
                                <td>
                                    {{ $attendance->check_out ? \Carbon\Carbon::parse($attendance->check_out)->format('H:i:s') : 'لم يسجل بعد' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">لا توجد بيانات</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-3">
                    {{ $attendances->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
