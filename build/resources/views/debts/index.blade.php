<x-app-layout>
    <x-slot name="header">
        <h2 class="h4">قائمة المديونيات</h2>
    </x-slot>

    <div class="container py-3">
        <a href="{{ route('debts.create') }}" class="btn btn-success mb-3">إضافة مديونية جديدة</a>

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>الاسم</th>
                    <th>الهاتف</th>
                    <th>النوع</th>
                    <th>الحالة</th>
                    <th>المبلغ الكلي</th>
                    <th>المتبقي</th>
                    <th>الأقساط</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($debts as $debt)
                    <tr>
                        <td>{{ $debt->name }}</td>
                        <td>{{ $debt->phone }}</td>
                        <td>{{ $debt->type }}</td>
                        <td>{{ $debt->status }}</td>
                        <td>{{ number_format($debt->total_amount, 2) }}</td>
                        <td>{{ number_format($debt->total_amount - $debt->installments->sum('paid'), 2) }}</td>
                        <td>{{ $debt->installments->count() }}</td>
                        <td>
                            <a href="{{ route('debts.show', $debt->id) }}" class="btn btn-info btn-sm">عرض</a>
                            <a href="{{ route('debts.edit', $debt->id) }}" class="btn btn-warning btn-sm">تعديل</a>
                            <form action="{{ route('debts.destroy', $debt->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('هل أنت متأكد؟')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm">حذف</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $debts->links() }}
    </div>
</x-app-layout>
