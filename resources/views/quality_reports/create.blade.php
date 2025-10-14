<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __("إضافة تقرير جودة جديد") }}
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

                    <form action="{{ route("quality_reports.store") }}" method="POST">
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
                            <label for="order_item_identifier">معرف الصنف/القطعة:</label>
                            <input type="text" class="form-control" id="order_item_identifier" name="order_item_identifier" value="{{ old("order_item_identifier") }}" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="issue_type">نوع المشكلة:</label>
                            <input type="text" class="form-control" id="issue_type" name="issue_type" value="{{ old("issue_type") }}" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="description">وصف المشكلة:</label>
                            <textarea class="form-control" id="description" name="description" required>{{ old("description") }}</textarea>
                        </div>
                        <div class="form-group mb-3">
                            <label for="severity">الخطورة:</label>
                            <select class="form-control" id="severity" name="severity" required>
                                <option value="low" {{ old("severity") == "low" ? "selected" : "" }}>منخفضة</option>
                                <option value="medium" {{ old("severity") == "medium" ? "selected" : "" }}>متوسطة</option>
                                <option value="high" {{ old("severity") == "high" ? "selected" : "" }}>عالية</option>
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="status">الحالة:</label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="pending" {{ old("status") == "pending" ? "selected" : "" }}>معلق</option>
                                <option value="fixed" {{ old("status") == "fixed" ? "selected" : "" }}>تم الإصلاح</option>
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="reported_by">المبلغ عنه بواسطة:</label>
                            <input type="text" class="form-control" id="reported_by" name="reported_by" value="{{ old("reported_by") }}" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="reported_date">تاريخ الإبلاغ:</label>
                            <input type="date" class="form-control" id="reported_date" name="reported_date" value="{{ old("reported_date", date("Y-m-d")) }}" required>
                        </div>
                        <button type="submit" class="btn btn-success">حفظ</button>
                        <a href="{{ route("quality_reports.index") }}" class="btn btn-secondary">إلغاء</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>