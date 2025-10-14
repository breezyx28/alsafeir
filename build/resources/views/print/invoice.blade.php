<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>فاتورة طلب - {{ $order->order_number }}</title>
    <style>
        @media print {
            .no-print { display: none; }
            body { margin: 0; }
        }

        body {
            font-family: 'Cairo', sans-serif;
            width: 80mm;
            margin: auto;
            color: #000;
            font-size: 12px;
            background: #fff;
        }

        .invoice-container {
            width: 100%;
            padding: 5px;
        }

        .invoice-header {
            text-align: center;
            margin-bottom: 10px;
        }

        .invoice-header h2 {
            margin: 0;
            font-size: 16px;
        }

        .invoice-header p {
            margin: 2px 0;
            font-size: 12px;
        }

        .section-title {
            border-top: 1px dashed #000;
            margin-top: 8px;
            padding-top: 4px;
            font-weight: bold;
        }

        .details p {
            margin: 2px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }

        td, th {
            padding: 4px 0;
            text-align: right;
        }

        .total-row {
            font-weight: bold;
            border-top: 1px dashed #000;
            margin-top: 4px;
        }

        .footer {
            text-align: center;
            margin-top: 10px;
            font-size: 11px;
        }

        .notes {
            font-size: 10px;
            margin-top: 8px;
            border-top: 1px dashed #000;
            padding-top: 5px;
        }

        button {
            padding: 6px 10px;
            font-size: 14px;
            margin: 5px;
        }
    </style>
</head>
<body>

<div class="invoice-container">
    <div class="invoice-header">
        <h2>السفير جلابية </h2> <!-- اسم المحل -->
        <p>فاتورة رقم: {{ $order->order_number }}</p>
    </div>

    <div class="details">
        <p class="section-title">بيانات العميل</p>
        <p><strong>الاسم:</strong> {{ $order->customer->name }}</p>
        <p><strong>الهاتف:</strong> {{ $order->customer->phone_primary }}</p>

        <p class="section-title">تفاصيل الطلب</p>
        <p><strong>الفرع:</strong> {{ $order->branch->name }}</p>
        <p><strong>تاريخ الاستلام:</strong> {{ $order->expected_delivery_date }}</p>
         <p><strong>الموظف المسؤول:</strong> {{ $order->employee->name ?? '—' }}</p>
         {{-- <p><strong>نوع التفصيل:</strong> {{ $measurement->detail_type }}</p>
         @if($measurement->quantity)<div><strong>الكمية:</strong> {{ $measurement->quantity }}</div>@endif --}}
    </div>

    <p class="section-title">الملخص المالي</p>
    <table>
        <!-- <tr>
            <td>سعر التفصيل</td>
            <td>{{ number_format($order->payment->tailoring_price, 2) }} جنيه</td>
        </tr>
        <tr>
            <td>إجمالي الأقمشة</td>
            <td>{{ number_format($order->payment->fabrics_total, 2) }} جنيه</td>
        </tr> -->
        <tr class="total-row">
            <td>الإجمالي قبل الخصم</td>
            <td>{{ number_format($order->payment->total_before_discount, 2) }} جنيه</td>
        </tr>
        <tr>
            <td>الخصم ({{ $order->payment->discount_percentage }}%)</td>
            <td>-{{ number_format($order->payment->total_before_discount - $order->payment->total_after_discount, 2) }} جنيه</td>
        </tr>
        <tr class="total-row">
            <td>الإجمالي بعد الخصم</td>
            <td>{{ number_format($order->payment->total_after_discount, 2) }} جنيه</td>
        </tr>
        <tr>
            <td>المدفوع</td>
            <td>{{ number_format($order->payment->total_paid, 2) }} جنيه</td>
        </tr>
        <tr class="total-row">
            <td>المتبقي</td>
            <td>{{ number_format($order->payment->remaining_amount, 2) }} جنيه</td>
        </tr>
    </table>

<div class="footer">
    <p>شكراً لتعاملكم معنا</p>
    <h3> لمتابعة طلبك: <a href="https://alsafeir.com/my-orders" target="_blank">alsafeir.com/my-orders</a></h3>
</div>



    <div class="notes">
        <p>⚠️ المحل غير مسؤول بعد مرور 10 أيام من تاريخ الاستلام.</p>
        <p>⚠️ العربون لا يُرد.</p>
        <p>⚠️ في حالة التاخر عن الاستلام يحق للمحل تعديل الأسعار بدون إشعار مسبق.</p>
        <p>⚠️ يرجى مراجعة الفاتورة قبل مغادرة المحل.</p>
        <p>⚠️ يُرجى الاحتفاظ بالفاتورة لإثبات الطلب.</p>
    </div>
</div>

<div class="no-print" style="text-align: center;">
    <button onclick="window.print()">طباعة</button>
    <button onclick="window.close()">إغلاق</button>
</div>

</body>
</html>
