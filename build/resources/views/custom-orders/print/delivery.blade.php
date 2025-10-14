<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: "Tahoma", sans-serif;
            font-size: 12px;
            line-height: 1.4;
            direction: rtl;
            text-align: right;
            margin: 0;
            padding: 5px;
            width: 80mm;
        }
        .header {
            text-align: center;
            margin-bottom: 8px;
        }
        .header h2 {
            margin: 0;
            font-size: 14px;
        }
        .divider {
            border-top: 1px dashed #000;
            margin: 4px 0;
        }
        .order-line {
            margin: 2px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
            margin-bottom: 5px;
        }
        table th, table td {
            border-bottom: 1px dotted #000;
            padding: 2px 0;
            text-align: center;
        }
        .summary {
            font-weight: bold;
            margin: 3px 0;
            font-size: 12px;
            text-align: center;
        }
    </style>
</head>
<body onload="window.print()">

<div class="header">
    <h2>⚜️ {{ $title }} ⚜️</h2>
    <small>تاريخ الطباعة: {{ now()->format('Y-m-d H:i') }}</small>
</div>

@php
    $totalOrders = 0;
@endphp

@forelse($orders as $order)
    @php $totalOrders++; @endphp
    <div class="order-line">
        رقم الطلب: {{ $order->order_number }} | العميل: {{ $order->customer->name }}
    </div>

    <table>
        <thead>
            <tr>
                <th>نوع التفصيل</th>
                <th>الكمية</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->measurements as $m)
                <tr>
                    <td>{{ $m->detail_type }}</td>
                    <td>{{ $m->quantity }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="divider"></div>
@empty
    <p class="order-line text-center">لا توجد طلبات {{ $title }}</p>
@endforelse

<div class="summary">
    إجمالي الطلبات: {{ $totalOrders }}
</div>

</body>
</html>
