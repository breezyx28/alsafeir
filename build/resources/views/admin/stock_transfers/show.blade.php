<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">تفاصيل تحويل المخزون #{{ $stockTransfer->id }}</h2>
    </x-slot>

    <div class="container mt-4">
        <a href="{{ route('admin.stock_transfers.index') }}" class="btn btn-secondary mb-3">رجوع للقائمة</a>

        <table class="table table-bordered">
            <tr>
                <th>فرع المصدر</th>
                <td>{{ $stockTransfer->fromBranch->name }}</td>
            </tr>
            <tr>
                <th>فرع الوجهة</th>
                <td>{{ $stockTransfer->toBranch->name }}</td>
            </tr>
            <tr>
                <th>تاريخ التحويل</th>
                <td>{{ $stockTransfer->transfer_date }}</td>
            </tr>
            <tr>
                <th>رقم المرجع</th>
                <td>{{ $stockTransfer->reference ?? '-' }}</td>
            </tr>
            <tr>
                <th>ملاحظات</th>
                <td>{{ $stockTransfer->notes ?? '-' }}</td>
            </tr>
        </table>

        <h5>الأصناف المحولة</h5>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>المنتج</th>
                    <th>اللون/المقاس</th>
                    <th>الكمية</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($stockTransfer->items as $item)
                    <tr>
                        <td>{{ $item->product->name }}</td>
                        <td>{{ optional($item->variant)->color }} {{ optional($item->variant)->size }}</td>
                        <td>{{ $item->quantity }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
