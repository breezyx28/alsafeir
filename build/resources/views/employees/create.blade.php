<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight">
            {{ __('إضافة موظف جديد') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="container">
            <div class="bg-white shadow rounded p-4">
                <form action="{{ route('employees.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">الاسم</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">البريد الإلكتروني</label>
                        <input type="email" name="email" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">رقم الهاتف</label>
                        <input type="text" name="phone" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">رقم الهوية</label>
                        <input type="text" name="national_id" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">تاريخ التعيين</label>
                        <input type="date" name="hiring_date" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">الوظيفة</label>
                        <input type="text" name="position" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">الراتب</label>
                        <input type="number" name="salary" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">الحالة</label>
                        <select name="status" class="form-select">
                            <option value="1">نشط</option>
                            <option value="0">موقوف</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">الفرع</label>
                        <select name="branch_id" class="form-select" required>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-success">حفظ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
