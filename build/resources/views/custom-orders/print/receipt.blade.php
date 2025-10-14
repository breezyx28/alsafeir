<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>إيصال التسليم</title>
    <style>
        body {
            font-family: 'Tajawal', sans-serif;
            width: 80mm; /* عرض ورق الطابعة الحرارية */
            margin: 0;
            padding: 0;
            font-size: 13px;
        }
        .receipt {
            padding: 10px;
            text-align: center;
            border: 1px dashed #000;
        }
        h2 {
            margin: 0;
            font-size: 18px;
        }
        h4 {
            margin: 5px 0;
            font-size: 15px;
            border-bottom: 1px dashed #000;
            padding-bottom: 5px;
        }
        .info {
            margin: 8px 0;
            text-align: right;
        }
        .info p {
            margin: 2px 0;
        }
        table {
            width: 100%;
            font-size: 13px;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table thead th {
            border-bottom: 1px solid #000;
            padding-bottom: 4px;
        }
        table tbody td {
            padding: 4px 0;
            border-bottom: 1px dotted #999;
            text-align: center;
        }
        .total {
            margin-top: 10px;
            font-weight: bold;
            border-top: 1px dashed #000;
            padding-top: 5px;
        }
        .footer {
            margin-top: 10px;
            font-size: 12px;
            border-top: 1px dashed #000;
            padding-top: 5px;
        }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="receipt">
        <h2>السفير جلابية</h2>
        <h4>إيصال استلام</h4>
        <h4><strong>رقم الاستلام:</strong> {{ $order->delivery_code }}</h4>

        <div class="info">
            <p><strong>رقم الطلب:</strong> {{ $order->order_number }}</p>
            <p><strong>العميل:</strong> {{ $order->customer->name }}</p>
            <p><strong>المبلغ المتبقي:</strong> {{ number_format($order->payment->remaining_amount, 2) }} ج.س</p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>النوع</th>
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

        <p class="total">إجمالي القطع: {{ $order->measurements->sum('quantity') }}</p>

        <div class="footer">
            <p>شكراً لتعاملكم معنا</p>
            {{-- <p>يرجى الاحتفاظ بهذا الإيصال عند الاستلام</p> --}}
        </div>
    </div>
</body>
</html>
