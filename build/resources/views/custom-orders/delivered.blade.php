<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            أرشيف الطلبات المسلّمة
        </h2>
    </x-slot>

    <div class="container-fluid py-4">
        <div class="card">
            <div class="card-header">
                <span>قائمة الطلبات المكتملة ({{ $orders->total() }})</span>
            </div>
            <div class="card-body">
                
                {{-- مربع البحث --}}
                <form method="GET" action="{{ route('custom-orders.delivered') }}" class="mb-4">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="ابحث برقم الطلب، اسم العميل أو رقم الهاتف..." value="{{ request('search') }}">
                        <button class="btn btn-secondary" type="submit">بحث في الأرشيف</button>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-hover table-bordered text-center">
                        <thead class="table-light">
                            <tr>
                                <th>رقم الطلب</th>
                                <th>العميل</th>
                                <th>الفرع</th>
                                <th>تاريخ الطلب</th>
                                <th>تاريخ التسليم</th>
                                <th>الإجمالي النهائي</th>
                                <th>الإجراء</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($orders as $order)
                                <tr>
                                    <td>{{ $order->order_number }}</td>
                                    <td>{{ $order->customer->name }}</td>
                                    <td>{{ $order->branch->name }}</td>
                                    <td>{{ $order->order_date }}</td>
                                    <td>{{ $order->updated_at }}</td> {{-- تاريخ آخر تحديث هو تاريخ التسليم --}}
                                    <td>{{ number_format(optional($order->payment)->total_after_discount ?? 0, 2) }}</td>
                                    <td>
                                        {{-- الإجراء الوحيد المتاح هو عرض التفاصيل --}}
                                        <a href="{{ route('custom-orders.show', $order) }}" class="btn btn-sm btn-info">عرض التفاصيل</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">لا توجد طلبات مسلمة في الأرشيف.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- روابط التنقل بين الصفحات --}}
                <div class="mt-3">
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
