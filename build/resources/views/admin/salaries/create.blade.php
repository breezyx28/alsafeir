<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight">احتساب مرتب جديد</h2>
    </x-slot>

    <div class="container py-4">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.salaries.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">الموظف</label>
                        <select name="employee_id" class="form-select" required>
                            <option value="">— اختر موظف —</option>
                            @foreach ($employees as $employee)
                                <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row mb-3">
                        <div class="col">
                            <label class="form-label">الشهر</label>
                            <select name="month" class="form-select" required>
                                @for ($m = 1; $m <= 12; $m++)
                                    <option value="{{ $m }}">{{ $m }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col">
                            <label class="form-label">السنة</label>
                            <select name="year" class="form-select" required>
                                @for ($y = date('Y'); $y >= 2022; $y--)
                                    <option value="{{ $y }}">{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">ملاحظات</label>
                        <textarea name="notes" class="form-control" rows="3"></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">احتساب الراتب</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
