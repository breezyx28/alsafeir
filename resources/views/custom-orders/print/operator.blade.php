<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>كشف الطلبات للمشغل</title>
    <style>
        body {
            font-family: "Tahoma", sans-serif;
            font-size: 12px;
            direction: rtl;
            text-align: right;
            margin: 0;
            padding: 5px;
        }
        .header { text-align: center; margin-bottom: 5px; font-weight: bold; }
        .divider { border-top: 1px dashed #000; margin: 4px 0; }
        .status-title { font-weight: bold; margin: 5px 0; text-align: center; }
        .order-line { margin: 2px 0; }
        .final-summary { text-align: center; font-weight: bold; margin-top: 5px; border-top: 1px dashed #000; padding-top: 5px; }
    </style>
</head>
<body onload="window.print()">

    <div class="header">
        ⚡ كشف الطلبات للمشغل ⚡<br>
        تاريخ الطباعة: {{ now()->format('Y-m-d H:i') }}
    </div>

    @php
        $grouped = $orders->groupBy('status');
        $statuses = ['جاري التنفيذ','جاهز للتسليم','تم التسليم'];
        $totalOrdersAll = 0;
    @endphp

    @foreach($statuses as $status)
        @if(isset($grouped[$status]) && $grouped[$status]->count() > 0)
            <div class="status-title">--- {{ $status }} ---</div>

            @php
                $totalOrders = 0;
            @endphp

            @foreach($grouped[$status]->sortBy('expected_delivery_date') as $order)
                <div class="order-line">
                    رقم: {{ $order->order_number }} |
                    العميل: {{ $order->customer->name }} |
                    تاريخ الاستلام: {{ $order->expected_delivery_date }}
                </div>
                @php $totalOrders++; $totalOrdersAll++; @endphp
            @endforeach

            <div class="divider">إجمالي الطلبات لهذه الحالة: {{ $totalOrders }}</div>
        @endif
    @endforeach

    <div class="final-summary">
        ==========================<br>
        🔹 المجموع الكلي للطلبات: {{ $totalOrdersAll }} 🔹<br>
        ==========================
    </div>

</body>
</html>
