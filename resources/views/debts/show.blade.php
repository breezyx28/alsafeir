<x-app-layout>
    <x-slot name="header">
        <h2 class="h4">تفاصيل المديونية: {{ $debt->name }}</h2>
    </x-slot>

    <div class="container py-3">
        <div class="mb-3">
            <strong>الهاتف:</strong> {{ $debt->phone }}<br>
            <strong>النوع:</strong> {{ $debt->type }}<br>
            <strong>الحالة:</strong> {{ $debt->status }}<br>
            <strong>المبلغ الكلي:</strong> {{ number_format($debt->total_amount, 2) }}<br>
            <strong>المتبقي:</strong> {{ number_format($debt->total_amount - $debt->installments->sum('paid'), 2) }}<br>
            <strong>ملاحظات:</strong> {{ $debt->notes }}<br>
        </div>

        <h5>الأقساط</h5>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>تاريخ القسط</th>
                    <th>المبلغ</th>
                    <th>مدفوع</th>
                    <th>المتبقي</th>
                    <th>إدخال دفعة</th>
                </tr>
            </thead>
            <tbody>
                @foreach($debt->installments as $installment)
                    <tr>
                        <td>{{ $installment->installment_date }}</td>
                        <td>{{ number_format($installment->amount, 2) }}</td>
                        <td>{{ number_format($installment->paid, 2) }}</td>
                        <td>{{ number_format($installment->amount - $installment->paid, 2) }}</td>
                        <td>
                            @if($installment->amount - $installment->paid > 0)
                                <form action="{{ route('debts.installments.pay', $installment->id) }}" method="POST" class="d-flex">
                                    @csrf
                                    <input type="number" name="amount" step="0.01" max="{{ $installment->amount - $installment->paid }}" class="form-control form-control-sm me-1" placeholder="المبلغ" required>
                                    <button type="submit" class="btn btn-success btn-sm">سداد</button>
                                </form>
                            @else
                                <span class="text-success">مسدد بالكامل</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <a href="{{ route('debts.index') }}" class="btn btn-secondary">رجوع للقائمة</a>
    </div>
</x-app-layout>
