<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            متابعة الطلبات في المشغل
        </h2>
    </x-slot>

    <div class="card">
        <div class="card-header">
            عرض حالة الطلبات التي يتم العمل عليها حالياً في المشغل
        </div>
        <div class="card-body">
            <!-- شريط البحث -->
            <div class="row mb-4">
                <div class="col-md-8 mx-auto">
                    <form method="GET" action="{{ route('orders.tracking') }}">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="ابحث برقم الطلب أو اسم العميل..." value="{{ request('search') }}">
                            <button class="btn btn-primary" type="submit">بحث</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover table-bordered text-center align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>رقم الطلب</th>
                            <th>العميل</th>
                            <th>تاريخ الاستلام المتوقع</th>
                            <th style="width: 35%;">حالة الإنتاج في المشغل</th>
                            <th>الإجراء</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $order)
                            <tr>
                                <td>{{ $order->order_number }}</td>
                                <td>{{ $order->customer->name }}</td>
                                <td>{{ $order->expected_delivery_date }}</td>
                                <td>
                                    <div class="progress" style="height: 25px;">
                                        <div class="progress-bar bg-success" role="progressbar" 
                                             style="width: {{ $order->completion_percentage }}%;" 
                                             aria-valuenow="{{ $order->completion_percentage }}" 
                                             aria-valuemin="0" aria-valuemax="100">
                                            <strong class="text-dark">{{ number_format($order->completion_percentage, 0) }}%</strong>
                                        </div>
                                    </div>
                                    <small class="text-muted">{{ $order->completion_text }}</small>
                                </td>
                                <td>
                                    <a href="{{ route('custom-orders.show', $order) }}" class="btn btn-sm btn-outline-primary">
                                        عرض التفاصيل
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    لا توجد طلبات قيد التنفيذ حالياً.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
