<x-app-layout>
    <x-slot name="header">
        <h2 class="h4">قائمة الاستبدالات</h2>
    </x-slot>

    <div class="container py-4">

        <form method="GET" action="{{ route('exchanges.index') }}" class="mb-4">
            <div class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">رقم الفاتورة الأصلية</label>
                    <input type="text" name="invoice_number" value="{{ request('invoice_number') }}"
                           class="form-control" placeholder="أدخل رقم الفاتورة">
                </div>
                <div class="col-md-3">
                    <label class="form-label">تاريخ الاستبدال</label>
                    <input type="date" name="exchange_date" value="{{ request('exchange_date') }}"
                           class="form-control">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">بحث</button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('exchanges.index') }}" class="btn btn-secondary w-100">إعادة تعيين</a>
                </div>
            </div>
        </form>

        <div class="card shadow-sm">
            <div class="card-body p-0">
                <table class="table table-bordered table-sm mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>رقم الفاتورة الأصلية</th>
                            <th>تاريخ الاستبدال</th>
                            <th>المبلغ المدفوع من العميل</th>
                            <th>المبلغ المرجع للعميل</th>
                            <th>المستخدم</th>
                            <th>إجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($exchanges as $exchange)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $exchange->return->sale->invoice_number ?? '-' }}</td>
                                <td>{{ $exchange->exchange_date  }}</td>
                                <td>{{ number_format($exchange->amount_paid_by_customer, 2) }}</td>
                                <td>{{ number_format($exchange->amount_refunded_to_customer, 2) }}</td>
                                <td>{{ $exchange->user->name ?? '-' }}</td>
                                <td>
                                    <a href="{{ route('exchanges.show', $exchange->id) }}" class="btn btn-sm btn-info">
                                        عرض
                                    </a>
                                     
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">لا توجد استبدالات.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-3">
            {{ $exchanges->links() }}
        </div>
    </div>
</x-app-layout>
