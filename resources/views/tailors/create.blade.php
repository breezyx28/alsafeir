<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __("إضافة ترزي جديد") }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route("tailors.store") }}" method="POST">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="name">الاسم:</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old("name") }}" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="phone">الهاتف:</label>
                            <input type="text" class="form-control" id="phone" name="phone" value="{{ old("phone") }}" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="address">العنوان:</label>
                            <textarea class="form-control" id="address" name="address">{{ old("address") }}</textarea>
                        </div>
                        <div class="form-group mb-3">
                            <label for="join_date">تاريخ الانضمام:</label>
                            <input type="date" class="form-control" id="join_date" name="join_date" value="{{ old("join_date") }}" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="status">الحالة:</label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="active" {{ old("status") == "active" ? "selected" : "" }}>نشط</option>
                                <option value="inactive" {{ old("status") == "inactive" ? "selected" : "" }}>غير نشط</option>
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="notes">ملاحظات:</label>
                            <textarea class="form-control" id="notes" name="notes">{{ old("notes") }}</textarea>
                        </div>
                        <div class="form-group mb-3">
                            <label for="id_number">رقم الهوية (اختياري):</label>
                            <input type="text" class="form-control" id="id_number" name="id_number" value="{{ old("id_number") }}">
                        </div>
                        <button type="submit" class="btn btn-success">حفظ</button>
                        <a href="{{ route("tailors.index") }}" class="btn btn-secondary">إلغاء</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
