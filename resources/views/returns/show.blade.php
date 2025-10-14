<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            تفاصيل الإرجاع - فاتورة أصلية رقم {{ $return->sale->invoice_number ?? '-' }}
        </h2>
    </x-slot>

    <div class="container py-4">

        {{-- معلومات الإرجاع --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header">
                <strong>معلومات الإرجاع</strong>
            </div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-3">رقم الفاتورة الأصلية</dt>
                    <dd class="col-sm-9">{{ $return->sale->invoice_number ?? '-' }}</dd>

                    <dt class="col-sm-3">تاريخ الإرجاع</dt>
                    <dd class="col-sm-9">
                        {{ $return->return_date ? $return->return_date : '-' }}
                    </dd>

                    <dt class="col-sm-3">المبلغ المرجع</dt>
                    <dd class="col-sm-9">{{ number_format($return->total_refund_amount, 2) }}</dd>

                    <dt class="col-sm-3">الحالة</dt>
                    <dd class="col-sm-9">
                        @if($return->status == 'completed')
                            <span class="badge bg-success">مكتمل</span>
                        @elseif($return->status == 'pending')
                            <span class="badge bg-warning text-dark">قيد المراجعة</span>
                        @else
                            <span class="badge bg-danger">مرفوض</span>
                        @endif
                    </dd>

                    <dt class="col-sm-3">المستخدم</dt>
                    <dd class="col-sm-9">{{ $return->user->name ?? '-' }}</dd>

                    <dt class="col-sm-3">سبب الإرجاع</dt>
                    <dd class="col-sm-9">{{ $return->reason ?? '-' }}</dd>

                    <dt class="col-sm-3">ملاحظات</dt>
                    <dd class="col-sm-9">{{ $return->notes ?? '-' }}</dd>
                </dl>
            </div>
        </div>

        {{-- المنتجات المرجعة --}}
        <div class="card shadow-sm">
            <div class="card-header">
                <strong>المنتجات المرجعة</strong>
            </div>
            <div class="card-body p-0">
                <table class="table table-bordered table-sm mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>المنتج</th>
                            <th>المقاس</th>
                            <th>اللون</th>
                            <th>الكمية</th>
                            <th>المبلغ المرجع</th>
                            <th>حالة المنتج</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($return->items as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->product->name ?? '-' }}</td>
                                <td>{{ $item->variant->size ?? '-' }}</td>
                                <td>{{ $item->variant->color ?? '-' }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ number_format($item->refund_amount, 2) }}</td>
                                <td>
                                    @switch($item->condition)
                                        @case('new') جديد @break
                                        @case('used') مستعمل @break
                                        @case('damaged') تالف @break
                                        @default -
                                    @endswitch
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">لا توجد منتجات في هذا الإرجاع.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- زر الرجوع --}}
        <div class="mt-3">
            <a href="{{ route('returns.index') }}" class="btn btn-secondary">رجوع</a>
        </div>

    </div>
</x-app-layout>
