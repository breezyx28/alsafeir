<x-app-layout>
    <div class="container mt-4">
        <div class="d-flex justify-content-between mb-3">
            <h4>قائمة الورديات</h4>
            <a href="{{ route('shifts.create') }}" class="btn btn-primary">+ إضافة وردية</a>
        </div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>الموظف</th>
                    <th>بداية الوردية</th>
                    <th>نهاية الوردية</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($shifts as $shift)
                    <tr>
                        <td>{{ $shift->employee->name }}</td>
                        <td>{{ $shift->start_time }}</td>
                        <td>{{ $shift->end_time }}</td>
                        <td>
                            <a href="{{ route('shifts.show', $shift) }}" class="btn btn-sm btn-info">عرض</a>
                            <a href="{{ route('shifts.edit', $shift) }}" class="btn btn-sm btn-warning">تعديل</a>
                            <form action="{{ route('shifts.destroy', $shift) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('هل أنت متأكد؟')">حذف</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $shifts->links() }}
    </div>
</x-app-layout>
