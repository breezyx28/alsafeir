<x-app-layout>
    <x-slot name="header">
        <h2 class="h4">إدارة المنصرفات</h2>
    </x-slot>

    <div class="container py-4">
        <div class="mb-3 text-end">
            <a href="{{ route('expenses.create') }}" class="btn btn-primary">إضافة منصرف</a>
        </div>

        <div class="card">
            <div class="card-body table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>التاريخ</th>
                            <th>الحساب</th>
                            <th>الحساب النقدي</th>
                            <th>المبلغ</th>
                            <th>ملاحظات</th>
                            <th>إجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($expenses as $expense)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $expense->expense_date }}</td>
                                <td>{{ optional($expense->account)->code ?? '—' }}</td>
                                <td>{{ $expense->cashAccount->name }}</td>
                                <td>{{ number_format($expense->amount, 2) }}</td>
                                <td>{{ $expense->notes }}</td>
                                <td>
                                    <a href="{{ route('expenses.show', $expense) }}" class="btn btn-sm btn-info">عرض</a>
                                    <a href="{{ route('expenses.edit', $expense) }}" class="btn btn-sm btn-warning">تعديل</a>
                                    <form action="{{ route('expenses.destroy', $expense) }}" method="POST" style="display:inline-block">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('تأكيد الحذف؟')">حذف</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center">لا توجد منصرفات</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
