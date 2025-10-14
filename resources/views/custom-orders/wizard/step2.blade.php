<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            إنشاء طلب جديد (الخطوة 2 من 4: تفاصيل الطلب)
        </h2>
    </x-slot>

    <div class="container py-4" style="max-width: 800px;">
        
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- عرض بيانات الطلب الحالية للتأكيد --}}
        <div class="card mb-4 bg-light">
            <div class="card-body">
                <div class="row">
                    <div class="col"><strong>العميل:</strong> {{ $order->customer->name }}</div>
                    <div class="col"><strong>الهاتف:</strong> {{ $order->customer->phone_primary }}</div>
                    <div class="col"><strong>الفرع:</strong> {{ $order->branch->name }}</div>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('order.wizard.processStep2', $order) }}">
            @csrf
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-4">ثانياً: أدخل تفاصيل الطلب</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">تاريخ الطلب</label>
                            <input type="date" name="order_date" class="form-control" value="{{ old('order_date', now()->format('Y-m-d')) }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">تاريخ الاستلام المتوقع</label>
                            <input type="date" name="expected_delivery_date" class="form-control" value="{{ old('expected_delivery_date') }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">تاريخ استلام المشغل (اختياري)</label>
                            <input type="date" name="operator_delivery_date" class="form-control" value="{{ old('operator_delivery_date') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">حالة الطلب الأولية</label>
                            <select name="status" class="form-select">
                                <option value="جاري التنفيذ" @selected(old('status') == 'جاري التنفيذ')>جاري التنفيذ</option>
                                <option value="جاهز للتسليم" @selected(old('status') == 'جاهز للتسليم')>جاهز للتسليم</option>
                                <option value="تم التسليم" @selected(old('status') == 'تم التسليم')>تم التسليم</option>
                            </select>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">ملاحظات عامة على الطلب (اختياري)</label>
                            <textarea name="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-between">
                    <a href="{{ route('order.wizard.step1') }}" class="btn btn-secondary">→ العودة للخطوة 1</a>
                    <button type="submit" class="btn btn-primary">التالي: المقاسات والأقمشة ←</button>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>
