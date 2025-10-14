<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight">قائمة الخصومات</h2>
    </x-slot>

    <div class="container py-5">
        <div class="mb-3 text-end">
            <a href="{{ route('admin.deductions.create') }}" class="btn btn-success">+ إضافة خصم</a>
        </div>

        <div class="card">
            <div class="card-body table-responsive">
                <table class="table table-bordered text-center">
                    <thead>
                        <tr>
                            <th>الموظف</th>
                            <th>المبلغ</th>
                            <th>السبب</th>
                            <th>التاريخ</th>
                            <th>الخيارات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($deductions as $deduction)
                            <tr>
                                <td>{{ $deduction->employee->name ?? 'غير معروف' }}</td>
                                <td>{{ number_format($deduction->amount) }}</td>
                                <td>{{ $deduction->reason }}</td>
                                <td>{{ $deduction->date }}</td>
                                <td>
                                    <a href="{{ route('admin.deductions.edit', $deduction) }}" class="btn btn-sm btn-primary">تعديل</a>
                                    <form action="{{ route('admin.deductions.destroy', $deduction) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger">حذف</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-3">
                    {{ $deductions->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
