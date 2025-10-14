<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __("إضافة سجل إنتاج جديد") }}
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

                    <form action="{{ route("tailor_production_logs.store") }}" method="POST">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="tailor_id">الترزي:</label>
                            <select class="form-control" id="tailor_id" name="tailor_id" required>
                                <option value="">اختر ترزي</option>
                                @foreach ($tailors as $tailor)
                                    <option value="{{ $tailor->id }}" {{ old("tailor_id") == $tailor->id ? "selected" : "" }}>{{ $tailor->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="piece_rate_definition_id">نوع القطعة:</label>
                            <select class="form-control" id="piece_rate_definition_id" name="piece_rate_definition_id" required>
                                <option value="">اختر نوع قطعة</option>
                                @foreach ($pieceRateDefinitions as $definition)
                                    <option value="{{ $definition->id }}" {{ old("piece_rate_definition_id") == $definition->id ? "selected" : "" }}>{{ $definition->item_type }} ({{ $definition->rate }} SAR)</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="quantity">الكمية:</label>
                            <input type="number" class="form-control" id="quantity" name="quantity" value="{{ old("quantity") }}" required min="1">
                        </div>
                        <div class="form-group mb-3">
                            <label for="production_date">تاريخ الإنتاج:</label>
                            <input type="date" class="form-control" id="production_date" name="production_date" value="{{ old("production_date", date("Y-m-d")) }}" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="status">الحالة:</label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="completed" {{ old("status") == "completed" ? "selected" : "" }}>مكتمل</option>
                                <option value="under_review" {{ old("status") == "under_review" ? "selected" : "" }}>قيد المراجعة</option>
                                <option value="rejected" {{ old("status") == "rejected" ? "selected" : "" }}>مرفوض</option>
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="notes">ملاحظات:</label>
                            <textarea class="form-control" id="notes" name="notes">{{ old("notes") }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-success">حفظ</button>
                        <a href="{{ route("tailor_production_logs.index") }}" class="btn btn-secondary">إلغاء</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
