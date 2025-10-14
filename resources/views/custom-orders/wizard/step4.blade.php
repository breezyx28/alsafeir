<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            إنشاء طلب جديد (الخطوة 4 من 4: المراجعة والماليات)
        </h2>
    </x-slot>

    <div class="container py-4" style="max-width: 900px;">
        
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

        {{-- قسم المراجعة --}}
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">مراجعة نهائية للطلب</h5>
            </div>
            <div class="card-body">
                {{-- تفاصيل العميل والطلب --}}
                <h6>1. تفاصيل العميل والطلب</h6>
                <table class="table table-sm table-bordered">
                    <tr>
                        <th>العميل</th><td>{{ $order->customer->name }}</td>
                        <th>الهاتف</th><td>{{ $order->customer->phone_primary }}</td>
                    </tr>
                    <tr>
                        <th>الفرع</th><td>{{ $order->branch->name }}</td>
                        <th>تاريخ الاستلام</th><td>{{ $order->expected_delivery_date }}</td>
                    </tr>
                </table>

                {{-- تفاصيل المقاسات --}}
                <h6 class="mt-4">2. بنود التفصيل</h6>
                <table class="table table-sm table-bordered">
                    <thead><tr><th>النوع</th><th>الكمية</th><th>المصدر</th></tr></thead>
                    <tbody>
                        @foreach($order->measurements as $m)
                        <tr><td>{{ $m->detail_type }}</td><td>{{ $m->quantity }}</td><td>{{ $m->fabric_source }}</td></tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- تفاصيل الأقمشة --}}
                @if($order->products->isNotEmpty())
                <h6 class="mt-4">3. الأقمشة المختارة</h6>
                <table class="table table-sm table-bordered">
                    <thead><tr><th>القماش</th><th>اللون</th><th>الكمية</th><th>السعر/متر</th></tr></thead>
                    <tbody>
                        @foreach($order->products as $p)
                        <tr>
                            <td>{{ $p->variant->product->name }}</td>
                            <td>{{ $p->variant->color }}</td>
                            <td>{{ $p->quantity }} متر</td>
                            <td>{{ number_format($p->price_per_meter, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>
        </div>

        {{-- قسم الماليات --}}
        <form method="POST" action="{{ route('order.wizard.processStep4', $order) }}">
            @csrf
            <div class="card">
                <div class="card-header"><h5 class="mb-0">خامساً: أدخل البيانات المالية</h5></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label>سعر التفصيل</label>
                            <input type="number" name="payment[tailoring_price]" class="form-control" value="{{ old('payment.tailoring_price', 0) }}" step="0.01" min="0" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>نسبة الخصم (%)</label>
                            <select name="payment[discount_percentage]" class="form-control">
                                @foreach ([0, 5, 7.5, 10] as $option)
                                    <option value="{{ $option }}" {{ old('payment.discount_percentage', 0) == $option ? 'selected' : '' }}>
                                        {{ $option }}%
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>المبلغ المدفوع</label>
                            <input type="number" name="payment[paid]" class="form-control" value="{{ old('payment.paid', 0) }}" step="0.01" min="0" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>طريقة الدفع</label>
                            <select name="payment[payment_method]" class="form-select">
                                <option value="كاش">كاش</option>
                                <option value="بطاقة">بطاقة</option>
                                <option value="تحويل">تحويل</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>رقم العملية (اختياري)</label>
                            <input type="text" name="payment[transaction_number]" class="form-control" value="{{ old('payment.transaction_number') }}">
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-between">
                    <a href="{{ route('order.wizard.step3', $order) }}" class="btn btn-secondary">→ العودة للخطوة 3</a>
                    <button type="submit" class="btn btn-success">✓ حفظ وإنهاء الطلب</button>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>
