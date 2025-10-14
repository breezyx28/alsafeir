<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تقرير طلبات الموظف</title>
    <style>
        body {
            font-family: monospace; 
            font-size: 14px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #000;
            padding: 2px 5px;
            text-align: left;
        }
        .text-center {
            text-align: center;
        }
        .total-row {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h3 class="text-center">تقرير طلبات الموظف: {{ $employee?->name ?? 'جميع الموظفين' }}</h3>
    <p class="text-center">الفترة: {{ $startDate?->format('Y-m-d') ?? 'بدون' }} إلى {{ $endDate?->format('Y-m-d') ?? 'بدون' }}</p>

    <table>
        <thead>
            <tr>
                
                {{-- <th>رقم الطلب</th> --}}
                <th>اسم العميل</th>
                <th> نوع التفصيل</th>
                <th>الكمية</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totals = []; // مصفوفة لحساب الإجمالي حسب نوع التفصيل
            @endphp

            @foreach($orders as $order)
                @foreach($order->measurements as $m)
                    <tr>
                        {{-- <td>{{ $order->order_number }}</td> --}}
                        <td>{{ $order->customer->name }}</td>
                        <td>☐ {{ $m->detail_type }}</td>
                        <td>{{ $m->quantity }}</td>
                    </tr>

                    @php
                        if(!isset($totals[$m->detail_type])) {
                            $totals[$m->detail_type] = 0;
                        }
                        $totals[$m->detail_type] += $m->quantity;
                    @endphp
                @endforeach
            @endforeach
        </tbody>

        @if(count($totals) > 0)
            <tfoot>
                <tr class="total-row">
                    <td colspan="3">الإجمالي لكل نوع تفصيل</td>
                    <td></td>
                </tr>
                @foreach($totals as $type => $qty)
                    <tr class="total-row">
                        <td colspan="3">{{ $type }}</td>
                        <td>{{ $qty }}</td>
                    </tr>
                @endforeach
            </tfoot>
        @endif
    </table>

    <p class="text-center" style="margin-top: 20px;">
        <button onclick="window.print()" style="padding:5px 10px; font-size:12px;">طباعة التقرير</button>
    </p>
</body>
</html>
