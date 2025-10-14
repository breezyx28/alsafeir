<x-app-layout>
    <x-slot name="header">
        <h2 class="h4">تفاصيل فاتورة: {{ $sale->invoice_number }}</h2>
    </x-slot>

    <div class="container py-3">
        <div class="mb-3">
            <strong>تاريخ البيع:</strong> {{ $sale->sale_date }}<br>
            <strong>الفرع:</strong> {{ $sale->branch->name ?? '-' }}<br>
            <strong>العميل:</strong> {{ $sale->customer->name ?? 'عميل نقدي' }}<br>
            <strong>طريقة الدفع:</strong> {{ ucfirst($sale->payment_method) }}<br>
            <strong>حالة الدفع:</strong> {{ ucfirst($sale->payment_status) }}<br>
            <strong>ملاحظات:</strong> {!! nl2br(e($sale->notes)) !!}
        </div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>المنتج</th>
                    <th>لون</th>
                    <th>مقاس</th>
                    <th>الكمية</th>
                    <th>سعر الوحدة</th>
                    <th>الإجمالي</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sale->items as $item)
                <tr>
                    <td>{{ $item->product->name ?? '-' }}</td>
                    <td>{{ $item->variant->color ?? '-' }}</td>
                    <td>{{ $item->variant->size ?? '-' }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format($item->unit_price, 2) }}</td>
                    <td>{{ number_format($item->sub_total, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-3">
            <strong>الإجمالي قبل الخصم:</strong> {{ number_format($sale->total_amount, 2) }}<br>
            <strong>الخصم:</strong> {{ number_format($sale->discount_amount, 2) }}<br>
            <strong>الصافي:</strong> {{ number_format($sale->net_amount, 2) }}
        </div>

        <div class="mt-4">
            <a href="{{ route('ready_sales.index') }}" class="btn btn-secondary">العودة للقائمة</a>
<a href="{{ route('ready_sales.print', $sale->id) }}" class="btn btn-primary" target="_blank">
    طباعة الفاتورة
</a>
        </div>
    </div>
</x-app-layout>
