<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight">
            تعديل الحافز
        </h2>
    </x-slot>

    <div class="container py-5">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.rewards.update', $reward) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">الموظف</label>
                        <select name="employee_id" class="form-select" required>
                            @foreach ($employees as $employee)
                                <option value="{{ $employee->id }}" {{ $reward->employee_id == $employee->id ? 'selected' : '' }}>
                                    {{ $employee->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">التاريخ</label>
                        <input type="date" name="date" class="form-control" value="{{ $reward->date }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">المبلغ</label>
                        <input type="number" name="amount" class="form-control" value="{{ $reward->amount }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">السبب</label>
                        <input type="text" name="reason" class="form-control" value="{{ $reward->reason }}">
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">تحديث</button>
                        <a href="{{ route('admin.rewards.index') }}" class="btn btn-secondary">إلغاء</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
