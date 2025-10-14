<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">أوامر التصنيع</h2>
    </x-slot>

    <div class="container mt-4">
        <div class="mb-3">
            <a href="{{ route('admin.production_orders.create') }}" class="btn btn-primary">إضافة أمر تصنيع جديد</a>
        </div>

        {{-- فلترة وبحث --}}
        <form method="GET" action="{{ route('admin.production_orders.index') }}" class="row mb-4">
            <div class="col-md-4">
                <label for="reference" class="form-label">رقم المرجع</label>
                <input type="text" name="reference" id="reference" value="{{ request('reference') }}" class="form-control">
            </div>

            <div class="col-md-4">
                <label for="branch_id" class="form-label">الفرع</label>
                <select name="branch_id" id="branch_id" class="form-control">
                    <option value="">-- الكل --</option>
                    @foreach ($branches as $branch)
                        <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                            {{ $branch->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-secondary me-2">تصفية</button>
                <a href="{{ route('admin.production_orders.index') }}" class="btn btn-light">إعادة تعيين</a>
            </div>
        </form>

        {{-- جدول الأوامر --}}
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>رقم الأمر</th>
                    <th>المنتج النهائي</th>
                    <th>الفرع الوجهة</th>
                    <th>الكمية</th>
                    <th>تاريخ التصنيع</th>
                    <th>ملاحظات</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($productionOrders as $order)
                    <tr>
                        <td>{{ $order->reference ?? '—' }}</td>
                        <td>{{ $order->product->name }} {{ $order->variant?->name ? ' - ' . $order->variant->name : '' }}</td>
                        <td>{{ $order->branch->name }}</td>
                        <td>{{ $order->quantity }}</td>
                        <td>{{ $order->production_date }}</td>
                        <td>{{ $order->notes ?? '—' }}</td>
                        <td>
                            <a href="{{ route('admin.production_orders.show', $order->id) }}" class="btn btn-sm btn-info">عرض</a>
                            {{-- ممكن تضيف زر حذف هنا إذا عايز --}}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">لا توجد أوامر تصنيع حالياً.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- صفحة الترقيم --}}
        <div class="d-flex justify-content-center mt-3">
            {{ $productionOrders->links() }}
        </div>
    </div>
</x-app-layout>
