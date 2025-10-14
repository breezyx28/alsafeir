<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight">
            إضافة حافز جديد
        </h2>
    </x-slot>

    <div class="container py-5">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.rewards.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">الموظف</label>
                        <select name="employee_id" class="form-select" required>
                            <option value="">اختر الموظف</option>
                            @foreach ($employees as $employee)
                                <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">التاريخ</label>
                        <input type="date" name="date" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">المبلغ</label>
                        <input type="number" name="amount" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">السبب</label>
                        <input type="text" name="reason" class="form-control">
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-success">حفظ</button>
                        <a href="{{ route('admin.rewards.index') }}" class="btn btn-secondary">رجوع</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
