<x-app-layout>
    <x-slot name="header">
        <h2 class="h4">قائمة فواتير المبيعات الجاهزة</h2>
    </x-slot>

    <div class="container py-3">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- نموذج البحث --}}
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-3">
                <input type="date" name="from_date" value="{{ request('from_date') }}" class="form-control" placeholder="من تاريخ">
            </div>
            <div class="col-md-3">
                <input type="date" name="to_date" value="{{ request('to_date') }}" class="form-control" placeholder="إلى تاريخ">
            </div>
            <div class="col-md-3">
                <input type="text" name="invoice_number" value="{{ request('invoice_number') }}" class="form-control" placeholder="رقم الفاتورة">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100">بحث</button>
            </div>
        </form>

        <a href="{{ route('ready_sales.create') }}" class="btn btn-primary mb-3">إنشاء فاتورة جديدة</a>

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>رقم الفاتورة</th>
                    <th>تاريخ البيع</th>
                    <th>الفرع</th>
                    <th>العميل</th>
                    <th>الصافي</th>
                    <th>حالة الدفع</th>
                    <th>بواسطة</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sales as $sale)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $sale->invoice_number }}</td>
                    <td>{{ $sale->sale_date }}</td>
                    <td>{{ $sale->branch->name ?? '-' }}</td>
                    <td>{{ $sale->customer->name ?? 'عميل نقدي' }}</td>
                    <td>{{ number_format($sale->net_amount, 2) }}</td>
                    <td>{{ ucfirst($sale->payment_status) }}</td>
                    <td>{{ $sale->user->name ?? '-' }}</td>
                    <td>
                        <div class="d-flex flex-column gap-1">
                            <a href="{{ route('ready_sales.show', $sale->id) }}" class="btn btn-sm btn-info">عرض</a>
                            <a href="{{ route('returns.create', $sale->id) }}" class="btn btn-sm btn-warning">إرجاع</a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center">لا توجد فواتير حتى الآن</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{ $sales->appends(request()->query())->links() }}
    </div>
</x-app-layout>
