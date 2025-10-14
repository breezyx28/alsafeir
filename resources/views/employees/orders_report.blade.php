<x-app-layout>
    <x-slot name="header">
        <h2>تقرير الطلبات للموظف: {{ $employee->name }}</h2>
    </x-slot>

    <div class="container py-4">
        <form method="GET" action="{{ route('employees.orders-report', $employee) }}" class="mb-4 row g-2">
            <div class="col-auto">
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control">
            </div>
            <div class="col-auto">
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary">فلتر</button>
            </div>
            <div class="col-auto">
                <a href="{{ route('employees.orders-report.print', $employee) . '?' . http_build_query(request()->all()) }}" target="_blank" class="btn btn-success">طباعة</a>
            </div>
        </form>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>رقم الطلب</th>
                    <th>العميل</th>
                    <th>تاريخ الإنجاز</th>
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
                            <td>{{ $order->completed_at }}</td>
                            <td>{{ $m->detail_type }}</td>
                            <td>{{ $m->quantity }}</td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
