<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">قائمة التوزيعات</h2>
    </x-slot>

    <div class="container mt-4">
        <a href="{{ route('admin.distributions.create') }}" class="btn btn-primary mb-3">إضافة توزيع جديد</a>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>رقم التوزيع</th>
                    <th>الفرع</th>
                    <th>رقم فاتورة الشراء</th>
                    <th>المنتج</th>
                    <th>اللون/المقاس</th>
                    <th>الكمية</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($distributions as $distribution)
                    <tr>
                        <td>{{ $distribution->id }}</td>
                        <td>{{ $distribution->branch->name ?? '-' }}</td>
                        <td>{{ $distribution->purchaseOrder->reference ?? '-' }}</td>
                        <td>{{ $distribution->product->name ?? '-' }}</td>
                        <td>{{ $distribution->variant->color ?? '-' }} / {{ $distribution->variant->size ?? '-' }}</td>
                        <td>{{ $distribution->quantity }}</td>
                        <td>
                            <a href="{{ route('admin.distributions.edit', $distribution) }}" class="btn btn-sm btn-warning">تعديل</a>
                            <form action="{{ route('admin.distributions.destroy', $distribution) }}" method="POST" class="d-inline-block" onsubmit="return confirm('هل أنت متأكد من الحذف؟');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">حذف</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">لا توجد توزيعات</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-3">{{ $distributions->links() }}</div>
    </div>
</x-app-layout>
