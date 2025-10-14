<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>كشف الطلبات</title>
    <style>
        body {
            font-family: "Tahoma", sans-serif;
            font-size: 12px;
            line-height: 1.4;
            direction: rtl;
            text-align: right;
            margin: 0;
            padding: 5px;
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
        .status-title {
            font-weight: bold;
            text-align: center;
            margin: 6px 0 2px;
            font-size: 13px;
            border-bottom: 1px solid #000;
        }
        .order-line {
            margin: 2px 0;
        }
        .paid {
            color: green;
            font-weight: bold;
        }
        .unpaid {
            color: red;
            font-weight: bold;
        }
        .summary {
            font-weight: bold;
            margin: 3px 0;
            font-size: 12px;
        }
        .final-summary {
            text-align: center;
            font-weight: bold;
            margin: 5px 0;
            font-size: 13px;
        }
    </style>
</head>
<body onload="window.print()">

    <div class="header">
        <h2>⚜️ السفير جلابية - كشف الطلبات ⚜️</h2>
        <small>تاريخ الطباعة: {{ now()->format('Y-m-d H:i') }}</small>
    </div>

    @php
        $grouped = $orders->groupBy('status');
        $statuses = ['جاري التنفيذ', 'جاهز للتسليم', 'تم التسليم'];

        $totalOrdersAll = 0;
        $totalRemainingAll = 0;
        $totalPaidFullAll = 0;
        $totalPaidAmountAll = 0;
    @endphp

    @foreach($statuses as $status)
        @if(isset($grouped[$status]) && $grouped[$status]->count() > 0)
            <div class="status-title">--- {{ $status }} ---</div>

            @php
                $totalOrders = 0;
                $totalRemaining = 0;
                $totalPaidFull = 0;
                $totalPaidAmount = 0;
            @endphp

            @foreach($grouped[$status]->sortBy('order_date') as $order)
                @php
                    $remaining = optional($order->payment)->remaining_amount ?? 0;
                    $total = optional($order->payment)->total_after_discount ?? 0;
                    $paidAmount = $total - $remaining;

                    $totalOrders++;
                    $totalRemaining += $remaining;
                    $totalPaidAmount += $paidAmount;

                    if($remaining <= 0.01) {
                        $totalPaidFull++;
                    }
                @endphp

                <div class="order-line">
                    الهاتف: {{ $order->customer->phone_primary }} | 
                    العميل: {{ $order->customer->name }} | 
                    المتبقي:
                    @if($remaining <= 0.01)
                        <span class="paid">مدفوع بالكامل ✓</span>
                    @else
                        <span class="unpaid">{{ number_format($remaining, 2) }} ج</span>
                    @endif
                </div>

            @endforeach

            {{-- ملخص كل حالة --}}
            <div class="summary">
                إجمالي الطلبات: {{ $totalOrders }} |
                إجمالي المتبقي: {{ number_format($totalRemaining,2) }} ج |
                عدد المدفوع بالكامل: {{ $totalPaidFull }} |
                إجمالي المبالغ المدفوعة: {{ number_format($totalPaidAmount,2) }} ج
            </div>

            <div class="divider"></div>

            @php
                $totalOrdersAll += $totalOrders;
                $totalRemainingAll += $totalRemaining;
                $totalPaidFullAll += $totalPaidFull;
                $totalPaidAmountAll += $totalPaidAmount;
            @endphp
        @endif
    @endforeach

    {{-- الملخص النهائي --}}
    <div class="final-summary">
        ==========================<br>
        🔻 المجموع الكلي 🔻<br>
        ==========================<br>
        إجمالي كل الطلبات: {{ $totalOrdersAll }}<br>
        إجمالي المتبقي الكلي: {{ number_format($totalRemainingAll,2) }} ج<br>
        إجمالي المدفوع بالكامل: {{ $totalPaidFullAll }} طلب<br>
        إجمالي المبالغ المدفوعة: {{ number_format($totalPaidAmountAll,2) }} ج
    </div>

    <div class="header">
        <small>تم بواسطة نظام HABIB TECHNOLOGY | {{ now()->format('Y-m-d') }}</small>
    </div>

</body>
</html>
