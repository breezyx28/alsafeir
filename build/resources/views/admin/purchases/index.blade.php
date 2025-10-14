<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">قائمة فواتير الشراء</h2>
    </x-slot>

    <div class="container mt-4">

        <a href="{{ route('admin.purchases.create') }}" class="btn btn-primary mb-3">إضافة فاتورة جديدة</a>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form method="GET" class="row g-3 mb-4">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="بحث برقم الفاتورة أو الملاحظات" value="{{ request('search') }}">
            </div>

            <div class="col-md-3">
                <select name="supplier_id" class="form-control">
                    <option value="">كل الموردين</option>
                    @foreach ($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                            {{ $supplier->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <select name="status" class="form-control">
                    <option value="">كل الحالات</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>مؤرشفة</option>
                    <option value="received" {{ request('status') == 'received' ? 'selected' : '' }}>مستلمة</option>
                    <option value="canceled" {{ request('status') == 'canceled' ? 'selected' : '' }}>ملغية</option>
                </select>
            </div>

            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">بحث</button>
            </div>
        </form>

   <div class="card p-4">
     <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    {{-- <th>رقم الفاتورة</th> --}}
                    <th>المورد</th>
                    <th>تاريخ الانشاء</th>
                    <th>تاريخ الشراء</th>
                    <th>رقم الفاتورة</th>
                    <th>الملاحظات</th>
                    <th>الحالة</th>
                    <th>الإجمالي</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($orders as $order)
                <tr>
                    {{-- <td>{{ $order->id }}</td> --}}
                    <td>{{ $order->supplier->name ?? '-' }}</td>
                    <td>{{ $order->created_at->format('Y-m-d') }}</td>
                    <td>{{ $order->order_date ?? '-' }}</td>
                    <td>{{ $order->reference ?? '-'}}</td>
                    <td>{{ $order->notes  ?? '-' }}</td>
                   
                    <td>{{ $order->status  ?? '-' }}</td>
                    <td>{{ number_format($order->items->sum('total_cost'), 2) }}</td>
                    <td>
                        <a href="{{ route('admin.purchases.edit', $order) }}" class="btn btn-sm btn-warning">تعديل</a>
                        <a href="{{ route('admin.purchases.show', $order) }}" class="btn btn-sm btn-primary">عرض</a>

                        <form action="{{ route('admin.purchases.destroy', $order) }}" method="POST" class="d-inline-block" onsubmit="return confirm('هل أنت متأكد من الحذف؟');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">حذف</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">لا توجد فواتير شراء</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-3">{{ $orders->links() }}</div>
    </div>
   </div>
 </div>
</x-app-layout>
