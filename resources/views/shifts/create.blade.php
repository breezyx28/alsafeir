<x-app-layout>
    <div class="container mt-4">
        <h4>إضافة وردية جديدة</h4>

        <form action="{{ route('shifts.store') }}" method="POST" class="mt-3">
            @csrf

            <div class="mb-3">
                <label>الموظف</label>
                <select name="employee_id" class="form-control" required>
                    <option value="">-- اختر الموظف --</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>بداية الوردية</label>
                <input type="datetime-local" name="start_time" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>نهاية الوردية</label>
                <input type="datetime-local" name="end_time" class="form-control" required>
            </div>

            <button class="btn btn-success">حفظ</button>
            <a href="{{ route('shifts.index') }}" class="btn btn-secondary">إلغاء</a>
        </form>
    </div>
</x-app-layout>
