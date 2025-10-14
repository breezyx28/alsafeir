<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight">تعديل خصم</h2>
    </x-slot>

    <div class="container py-5">
        <div class="card p-4">
            <form action="{{ route('admin.deductions.update', $deduction) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label>الموظف</label>
                    <select name="employee_id" class="form-select" required>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}" {{ $deduction->employee_id == $employee->id ? 'selected' : '' }}>
                                {{ $employee->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label>المبلغ</label>
                    <input type="number" name="amount" class="form-control" value="{{ $deduction->amount }}" required>
                </div>

                <div class="mb-3">
                    <label>السبب</label>
                    <textarea name="reason" class="form-control" rows="3">{{ $deduction->reason }}</textarea>
                </div>

                <div class="mb-3">
                    <label>التاريخ</label>
                    <input type="date" name="date" class="form-control" value="{{ $deduction->date }}" required>
                </div>

                <button type="submit" class="btn btn-primary">تحديث</button>
            </form>
        </div>
    </div>
</x-app-layout>
