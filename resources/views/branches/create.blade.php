<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-4">
            {{ __('إضافة فرع جديد') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="container">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ route('branches.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label text-end w-100">اسم الفرع</label>
                            <input type="text" name="name" class="form-control text-end" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-end w-100">العنوان </label>
                            <input type="text" name="address" class="form-control text-end" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-end w-100">المدير</label>
                            <input type="text" name="manager_name" class="form-control text-end">
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-end w-100">الهاتف</label>
                            <input type="text" name="phone" class="form-control text-end">
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-end w-100">الحالة</label>
                            <select name="status" class="form-select text-end">
                                <option value="1">نشط</option>
                                <option value="0">موقوف</option>
                            </select>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-success px-4">حفظ</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
