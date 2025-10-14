<x-app-layout>
    <x-slot name="header">
        <h2>تقرير طلبات الموظفين</h2>
    </x-slot>

    <div class="container py-4">
        <form method="GET" action="{{ route('reports.employee-orders') }}" class="row g-2 mb-4">
            <div class="col-md-3">
                <select name="employee_id" class="form-select">
                    <option value="">-- اختر الموظف --</option>
                    @foreach($employees as $employee)
                        <option value="{{ $employee->id }}" {{ $employee->id == $employeeId ? 'selected' : '' }}>{{ $employee->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <input type="date" name="start_date" class="form-control" value="{{ $startDate?->format('Y-m-d') }}">
            </div>
            <div class="col-md-3">
                <input type="date" name="end_date" class="form-control" value="{{ $endDate?->format('Y-m-d') }}">
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary">فلتر</button>
                <a href="{{ route('reports.employee-orders.print', request()->all()) }}" target="_blank" class="btn btn-success">طباعة</a>
            </div>
        </form>

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>رقم الطلب</th>
                    <th>اسم العميل</th>
                    <th>نوع التفصيل</th>
                    <th>الكمية</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                    @foreach($order->measurements as $m)
                        <tr>
                            <td>{{ $order->order_number }}</td>
                            <td>{{ $order->customer->name }}</td>
                            <td>{{ $m->detail_type }}</td>
                            <td>{{ $m->quantity }}</td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
