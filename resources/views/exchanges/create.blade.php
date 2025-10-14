<x-app-layout>
    <x-slot name="header">
        <h2 class="h4">إنشاء استبدال جديد</h2>
    </x-slot>

    <div class="container py-4">
        {{-- عرض الأخطاء --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('exchanges.store') }}">
            @csrf
            <input type="hidden" name="return_id" value="{{ $return->id }}">

            {{-- بيانات الإرجاع --}}
            <div class="card mb-4">
                <div class="card-header"><h5>بيانات الإرجاع</h5></div>
                <div class="card-body">
                    <p><strong>رقم الفاتورة الأصلية:</strong> {{ $return->sale->invoice_number ?? '-' }}</p>
                </div>
            </div>

            {{-- عناصر الاستبدال --}}
            <div class="card mb-4">
                <div class="card-header"><h5>عناصر الاستبدال</h5></div>
                <div class="card-body">
                    @foreach($return->items as $index => $item)
                        <div class="row g-2 align-items-center mb-2">
                            {{-- المنتج الأصلي --}}
                            <div class="col-md-4">
                                <label>المنتج الأصلي:</label>
                                <div>{{ $item->product->name }} @if($item->variant) - {{ $item->variant->name }} @endif</div>
                                <input type="hidden" name="items[{{ $index }}][original_variant_id]" value="{{ $item->variant_id }}">
                                <input type="hidden" name="items[{{ $index }}][original_price]" value="{{ $item->refund_amount / $item->quantity }}">
                            </div>

                            {{-- الكمية --}}
                            <div class="col-md-2">
                                <label>الكمية</label>
                                <input type="number" name="items[{{ $index }}][quantity]" value="{{ $item->quantity }}" min="1" max="{{ $item->quantity }}" class="form-control">
                            </div>

                            {{-- المنتج البديل --}}
                            <div class="col-md-4">
                                <label>اختر المنتج البديل:</label>
                                <select class="form-control exchange-product" data-index="{{ $index }}" name="items[{{ $index }}][new_variant_id]" required>
                                    <option value="">-- اختر --</option>
                                    @foreach($availableVariants as $variant)
                                        <option value="{{ $variant->id }}" data-price="{{ $variant->price_override ?? $variant->price }}">
                                            {{ $variant->product->name }} - {{ $variant->name ?? '' }} - اللون: {{ $variant->color ?? '-' }} - السعر: {{ number_format($variant->price_override ?? $variant->price, 2) }}
                                        </option>

                                    @endforeach
                                </select>
                            </div>

                            {{-- فرق السعر --}}
                            <div class="col-md-2">
                                <label>فرق السعر</label>
                                <input type="number" step="0.01" class="form-control price-diff" data-index="{{ $index }}" name="items[{{ $index }}][price_difference]" readonly value="0">
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>

            {{-- ملخص الدفع --}}
            <div class="mb-3">
                <label>المبلغ المدفوع من العميل</label>
                <input type="number" step="0.01" name="amount_paid_by_customer" class="form-control" value="0" id="amountPaid">
            </div>
            <div class="mb-3">
                <label>المبلغ المرجع للعميل</label>
                <input type="number" step="0.01" name="amount_refunded_to_customer" class="form-control" value="0" id="amountRefunded">
            </div>
            <div class="mb-3">
                <label>ملاحظات</label>
                <textarea name="notes" class="form-control"></textarea>
            </div>

            <button type="submit" class="btn btn-success">حفظ الاستبدال</button>
        </form>
    </div>

    {{-- JS لحساب فرق السعر تلقائي --}}
    <script>
    document.querySelectorAll('.exchange-product').forEach(function(select) {
        select.addEventListener('change', function() {
            const index = this.dataset.index;
            const originalPrice = parseFloat(document.querySelector(`input[name='items[${index}][original_price]']`).value);
            const newPrice = parseFloat(this.selectedOptions[0].dataset.price || 0);
            const diff = newPrice - originalPrice;
            document.querySelector(`.price-diff[data-index='${index}']`).value = diff.toFixed(2);

            // تحديث ملخص الدفع تلقائياً
            let totalPaid = 0;
            let totalRefunded = 0;
            document.querySelectorAll('.price-diff').forEach(function(input) {
                const val = parseFloat(input.value);
                if (val > 0) totalPaid += val;
                else totalRefunded += Math.abs(val);
            });
            document.getElementById('amountPaid').value = totalPaid.toFixed(2);
            document.getElementById('amountRefunded').value = totalRefunded.toFixed(2);
        });
    });
</script>

</x-app-layout>
