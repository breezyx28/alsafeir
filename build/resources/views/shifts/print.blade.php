<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تقرير وردية - {{ $shift->employee->name }}</title>
    <style>
        body { font-family: 'Tajawal', sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: center; }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <h3>تقرير وردية</h3>
    <p><strong>الموظف:</strong> {{ $shift->employee->name }}</p>
    <p><strong>من:</strong> {{ $shift->start_time }} <strong>إلى:</strong> {{ $shift->end_time }}</p>

    <table>
        <thead>
            <tr>
                <th>رقم الطلب</th>
                <th>العميل</th>
                <th>المبلغ</th>
                <th>تاريخ التنفيذ</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->customer->name ?? '-' }}</td>
                    <td>{{ number_format($order->total_amount, 2) }}</td>
                    <td>{{ $order->completed_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <button class="no-print" onclick="window.print()">طباعة</button>
</body>
</html>
