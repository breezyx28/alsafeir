<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">تفاصيل فاتورة الشراء #{{ $purchaseOrder->id }}</h2>
    </x-slot>

    <div class="container mt-4">

        <div class="mb-3">
            <strong>المورد:</strong> {{ $purchaseOrder->supplier->name ?? '-' }}
        </div>
        <div class="mb-3">
            <strong>تاريخ الشراء:</strong> {{ $purchaseOrder->order_date  ?? '-' }}
        </div>
        <div class="mb-3">
            <strong>رقم الفاتورة:</strong> {{ $purchaseOrder->reference ?? '-' }}
        </div>
        <div class="mb-3">
            <strong>ملاحظات:</strong> {{ $purchaseOrder->notes ?? '-' }}
        </div>
        <div class="mb-3">
            <strong>حالة الفاتورة:</strong>
            @php
                $statusLabels = [
                    'draft' => 'مؤرشفة',
                    'received' => 'مستلمة',
                    'canceled' => 'ملغية',
                ];
            @endphp
            {{ $statusLabels[$purchaseOrder->status] ?? $purchaseOrder->status }}
        </div>

        <hr>

        <h5>تفاصيل الأصناف</h5>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>المنتج</th>
                    <th>اللون / المقاس</th>
                    <th>الكمية</th>
                    <th>السعر</th>
                    <th>الإجمالي</th>
                </tr>
            </thead>
            <tbody>
                @foreach($purchaseOrder->items as $item)
                    <tr>
                        <td>{{ $item->product->name ?? '-' }}</td>
                        <td>
                            @if($item->variant)
                                {{ $item->variant->color ?? '' }} {{ $item->variant->size ? ' / ' . $item->variant->size : '' }}
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ number_format($item->cost_price, 2) }}</td>
                        <td>{{ number_format($item->quantity * $item->cost_price, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="4" class="text-end">الإجمالي الكلي</th>
                    <th>{{ number_format($purchaseOrder->items->sum(fn($i) => $i->quantity * $i->cost_price), 2) }}</th>
                </tr>
            </tfoot>
        </table>

        <a href="{{ route('admin.purchases.index') }}" class="btn btn-secondary">عودة للقائمة</a>
    </div>
</x-app-layout>
