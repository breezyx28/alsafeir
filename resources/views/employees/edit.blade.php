<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight">
            {{ __('تعديل بيانات الموظف') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="container">
            <div class="bg-white shadow rounded p-4">
                <form action="{{ route('employees.update', $employee) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">الاسم</label>
                        <input type="text" name="name" class="form-control" value="{{ $employee->name }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">البريد الإلكتروني</label>
                        <input type="email" name="email" class="form-control" value="{{ $employee->email }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">رقم الهاتف</label>
                        <input type="text" name="phone" class="form-control" value="{{ $employee->phone }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">رقم الهوية</label>
                        <input type="text" name="national_id" class="form-control" value="{{ $employee->national_id }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">تاريخ التعيين</label>
                        <input type="date" name="hiring_date" class="form-control" value="{{ $employee->hiring_date }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">الوظيفة</label>
                        <input type="text" name="position" class="form-control" value="{{ $employee->position }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">الراتب</label>
                        <input type="number" name="salary" class="form-control" value="{{ $employee->salary }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">الحالة</label>
                        <select name="status" class="form-select">
                            <option value="1" {{ $employee->status ? 'selected' : '' }}>نشط</option>
                            <option value="0" {{ !$employee->status ? 'selected' : '' }}>موقوف</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">الفرع</label>
                        <select name="branch_id" class="form-select">
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}" {{ $employee->branch_id == $branch->id ? 'selected' : '' }}>
                                    {{ $branch->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">تحديث</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
