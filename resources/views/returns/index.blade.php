<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            قائمة الإرجاعات
        </h2>
    </x-slot>

    <div class="container py-4">

        {{-- فورم البحث --}}
        <form method="GET" action="{{ route('returns.index') }}" class="mb-4">
            <div class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">رقم الفاتورة</label>
                    <input type="text" name="invoice_number" value="{{ request('invoice_number') }}"
                           class="form-control" placeholder="أدخل رقم الفاتورة">
                </div>
                <div class="col-md-3">
                    <label class="form-label">تاريخ الإرجاع</label>
                    <input type="date" name="return_date" value="{{ request('return_date') }}"
                           class="form-control">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">بحث</button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('returns.index') }}" class="btn btn-secondary w-100">إعادة تعيين</a>
                </div>
            </div>
        </form>

        {{-- جدول الإرجاعات --}}
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <table class="table table-bordered table-sm mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>رقم الفاتورة الأصلية</th>
                            <th>تاريخ الإرجاع</th>
                            <th>المبلغ المرجع</th>
                            <th>الحالة</th>
                            <th>المستخدم</th>
                            <th>إجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($returns as $return)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $return->sale->invoice_number ?? '-' }}</td>
                                <td>{{ $return->return_date ? $return->return_date : '-' }}</td>
                                <td>{{ number_format($return->total_refund_amount, 2) }}</td>
                                <td>
                                    @if($return->status == 'completed')
                                        <span class="badge bg-success">مكتمل</span>
                                    @elseif($return->status == 'pending')
                                        <span class="badge bg-warning text-dark">قيد المراجعة</span>
                                    @else
                                        <span class="badge bg-danger">مرفوض</span>
                                    @endif
                                </td>
                                <td>{{ $return->user->name ?? '-' }}</td>
                                <td>
                                    <a href="{{ route('returns.show', $return->id) }}" class="btn btn-sm btn-info">
                                        عرض
                                    </a>
                                    <a href="{{ route('exchanges.create', $return->id) }}" class="btn btn-sm btn-success">استبدال</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">لا توجد عمليات إرجاع.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- روابط التصفح --}}
        <div class="mt-3">
            {{ $returns->links() }}
        </div>

    </div>
</x-app-layout>
