<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">قائمة إرجاعات المشتريات</h2>
    </x-slot>

    <div class="container mt-4">
       <a href="{{ route('admin.purchase_returns.create') }}" class="btn btn-primary mb-3">
            إضافة مرتجع جديد
        </a>
        <form method="GET" class="row mb-3">
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
                <select name="purchase_order_id" class="form-control">
                    <option value="">كل الفواتير</option>
                    @foreach ($purchaseOrders as $order)
                        <option value="{{ $order->id }}" {{ request('purchase_order_id') == $order->id ? 'selected' : '' }}>
                            فاتورة #{{ $order->id }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <input type="date" name="return_date" class="form-control" value="{{ request('return_date') }}">
            </div>

            <div class="col-md-3">
                <button class="btn btn-primary w-100">فلترة</button>
            </div>
        </form>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>رقم</th>
                    <th>المورد</th>
                    <th>الفاتورة</th>
                    <th>التاريخ</th>
                    <th>السبب</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($returns as $return)
                    <tr>
                        <td>{{ $return->id }}</td>
                        <td>{{ $return->supplier->name }}</td>
                        <td>#{{ $return->purchaseOrder->reference }}</td>
                        <td>{{ $return->return_date }}</td>
                        <td>{{ $return->notes ?? '-' }}</td>
                        <td>
                            <a href="{{ route('admin.purchase_returns.show', $return) }}" class="btn btn-sm btn-info">عرض</a>
                            <a href="{{ route('admin.purchase_returns.edit', $return) }}" class="btn btn-sm btn-warning">تعديل</a>
                            <form action="{{ route('admin.purchase_returns.destroy', $return) }}" method="POST" class="d-inline" onsubmit="return confirm('تأكيد الحذف؟');">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger">حذف</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">لا توجد نتائج</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div>{{ $returns->links() }}</div>
    </div>
</x-app-layout>
