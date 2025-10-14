<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight">إضافة خصم</h2>
    </x-slot>

    <div class="container py-5">
        <div class="card p-4">
            <form action="{{ route('admin.deductions.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label>الموظف</label>
                    <select name="employee_id" class="form-select" required>
                        <option value="">اختر موظف</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label>المبلغ</label>
                    <input type="number" name="amount" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>السبب</label>
                    <textarea name="reason" class="form-control" rows="3" required></textarea>
                </div>

                <div class="mb-3">
                    <label>التاريخ</label>
                    <input type="date" name="date" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-success">حفظ</button>
            </form>
        </div>
    </div>
</x-app-layout>
