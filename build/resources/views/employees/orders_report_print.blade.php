<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تقرير الطلبات - {{ $employee->name }}</title>
    <style>
        body { font-family: 'Tajawal', sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 5px; text-align: center; }
    </style>
</head>
<body>
    <h3>تقرير الطلبات للموظف: {{ $employee->name }}</h3>
    <p>الفترة: {{ $startDate?->format('Y-m-d') }} إلى {{ $endDate?->format('Y-m-d') }}</p>

    <table>
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
</body>
</html>
