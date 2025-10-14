<x-app-layout>
    <x-slot name="header">
        <h2 class="h4">تفاصيل الاستبدال</h2>
    </x-slot>

    <div class="container py-4">
        <div class="card mb-4">
            <div class="card-header"><h5>بيانات الاستبدال</h5></div>
            <div class="card-body">
                <p><strong>رقم الفاتورة الأصلية:</strong> {{ $exchange->return->sale->invoice_number ?? '-' }}</p>
                <p><strong>تاريخ الاستبدال:</strong> {{ $exchange->exchange_date }}</p>
                <p><strong>المبلغ المدفوع من العميل:</strong> {{ number_format($exchange->amount_paid_by_customer, 2) }}</p>
                <p><strong>المبلغ المرجع للعميل:</strong> {{ number_format($exchange->amount_refunded_to_customer, 2) }}</p>
                <p><strong>الملاحظات:</strong> {{ $exchange->notes ?? '-' }}</p>
                <p><strong>المستخدم:</strong> {{ $exchange->user->name ?? '-' }}</p>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><h5>عناصر الاستبدال</h5></div>
            <div class="card-body p-0">
                <table class="table table-bordered table-sm mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>المنتج</th>
                            <th>المتغير</th>
                            <th>الكمية</th>
                            <th>سعر الوحدة</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($exchange->items as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->product->name ?? '-' }}</td>
                                <td>{{ $item->variant?->color ?? '-' }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ number_format($item->amount, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">لا توجد عناصر.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
