<x-app-layout>
    <div class="container mt-4">
        <h4>تفاصيل الوردية</h4>

        <div class="card mb-3">
            <div class="card-body">
                <p><strong>الموظف:</strong> {{ $shift->employee->name }}</p>
                <p><strong>البداية:</strong> {{ $shift->start_time }}</p>
                <p><strong>النهاية:</strong> {{ $shift->end_time }}</p>
                <a href="{{ route('shifts.print', $shift) }}" class="btn btn-primary" target="_blank">طباعة الوردية</a>
            </div>
        </div>

        <h5>الطلبات خلال الوردية</h5>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>رقم الطلب</th>
                    <th>العميل</th>
                    <th>المبلغ</th>
                    <th>تاريخ التنفيذ</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->customer->name ?? '-' }}</td>
                        <td>{{ number_format($order->total_amount, 2) }}</td>
                        <td>{{ $order->completed_at }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">لا توجد طلبات في هذه الوردية</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-app-layout>
