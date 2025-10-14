<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            إدارة الطلبات
        </h2>
    </x-slot>

    <div class="container-fluid py-4">
        
        {{-- قسم الفلاتر والبحث --}}
        <div class="card mb-4">
            <div class="card-body">
                  <form method="GET" action="{{ route('custom-orders.index') }}" id="ajax-search-form">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-5">
                            <label for="search" class="form-label">بحث</label>
                            <input type="text" id="search" name="search" class="form-control" 
                                   placeholder="ابحث برقم الطلب، اسم العميل، أو الهاتف..." 
                                   value="{{ request('search') }}">
                        </div>
                        <div class="col-md-4">
                            <label for="status" class="form-label">الحالة</label>
                            <select id="status" name="status" class="form-select">
                                <option value="">كل الحالات</option>
                                <option value="جاري التنفيذ" @selected(request('status') == 'جاري التنفيذ')>جاري التنفيذ</option>
                                <option value="جاهز للتسليم" @selected(request('status') == 'جاهز للتسليم')>جاهز للتسليم</option>
                                <option value="تم التسليم" @selected(request('status') == 'تم التسليم')>تم التسليم</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary w-100">تطبيق</button>
                            <a href="{{ route('custom-orders.index') }}" class="btn btn-outline-secondary w-100 mt-2">إعادة تعيين</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- جدول عرض الطلبات --}}
        <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
        <span>قائمة الطلبات ({{ $orders->total() }})</span>
        
        <div class="d-flex gap-2">
            <a href="{{ route('order.wizard.step1') }}" class="btn btn-success">
                + إنشاء طلب جديد
            </a>
            <a href="{{ route('custom-orders.print.filter') }}" class="btn btn-outline-dark">
                🖨 طباعة الطلبات
            </a>
        </div>
    </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 text-center">
                        <thead class="table-light">
                            <tr>
                                <th>رقم الطلب</th>
                                <th>العميل</th>
                                <th>الهاتف</th>
                                <th>الفرع</th>
                                <th>تاريخ الطلب</th>
                                <th>تاريخ الاستلام</th>
                                <th>المبلغ الإجمالي</th>
                                <th>المتبقي</th>
                                <th>الحالة</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($orders as $order)
                                <tr>
                                    <td>{{ $order->order_number }}</td>
                                    <td>{{ $order->customer->name }}</td>
                                    <td>{{ $order->customer->phone_primary }}</td>
                                    <td>{{ $order->branch->name }}</td>
                                    <td>{{ $order->order_date }}</td>
                                    <td>{{ $order->expected_delivery_date }}</td>
                                    <td>{{ number_format(optional($order->payment)->total_after_discount ?? 0, 2) }}</td>
                                    <td class="text-danger fw-bold">{{ number_format($order->payment->remaining_amount?? 0, 2) }}</td>
                                    <td>
                                        @php
                                            $statusClass = match($order->status) {
                                                'جاري التنفيذ' => 'bg-danger',
                                                'جاهز للتسليم' => 'bg-success',
                                                'تم التسليم' => 'bg-primary',
                                                default => 'bg-secondary',
                                            };
                                        @endphp

                                        <span class="badge {{ $statusClass }}">{{ $order->status }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('custom-orders.show', $order) }}" class="btn btn-sm btn-info">
                                            عرض
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center py-4">لا توجد طلبات تطابق معايير البحث.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
