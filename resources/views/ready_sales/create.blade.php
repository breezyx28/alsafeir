<x-app-layout>
    <x-slot name="header">
        <h2 class="h4">إنشاء فاتورة مبيعات جاهزة</h2>
    </x-slot>

    <div class="container py-3">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('ready_sales.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="sale_date" class="form-label">تاريخ البيع</label>
                <input type="date" name="sale_date" id="sale_date" class="form-control" value="{{ old('sale_date', date('Y-m-d')) }}" required>
            </div>

            <div id="items-container">
                <h5>عناصر الفاتورة</h5>

                <div class="item-row row g-3 align-items-center mb-2">
                    <div class="col-md-5">
                        <label class="form-label">اختيار المنتج (المتغير)</label>
                        <select name="items[0][variant_id]" class="form-select variant-select" required>
                            <option value="">اختر المنتج</option>
                            @foreach($variants as $variant)
                                <option value="{{ $variant->id }}" data-product-id="{{ $variant->product_id }}" data-price="{{ $variant->price_override ?? $variant->product->price ?? 0 }}">
                                    {{ $variant->product->name }} - {{ $variant->color }} - {{ $variant->size }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">الكمية</label>
                        <input type="number" name="items[0][quantity]" class="form-control" min="1" value="1" required>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">سعر الوحدة</label>
                        <input type="number" step="0.01" name="items[0][unit_price]" class="form-control unit-price" readonly required>
                    </div>

                    <div class="col-md-2">
                        <button type="button" class="btn btn-danger btn-remove-item mt-4">حذف</button>
                    </div>
                </div>
            </div>

            <button type="button" id="add-item-btn" class="btn btn-primary mb-3">إضافة منتج</button>

            <div class="mb-3">
                <label for="discount_percent" class="form-label">نسبة الخصم (%)</label>
                <select name="discount_percent" id="discount_percent" class="form-select">
                    <option value="0" {{ old('discount_percent') == '0' ? 'selected' : '' }}>0%</option>
                    <option value="5" {{ old('discount_percent') == '5' ? 'selected' : '' }}>5%</option>
                    <option value="10" {{ old('discount_percent') == '10' ? 'selected' : '' }}>10%</option>
                    <option value="15" {{ old('discount_percent') == '15' ? 'selected' : '' }}>15%</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="payment_method" class="form-label">طريقة الدفع</label>
                <select name="payment_method" id="payment_method" class="form-select" required>
                    <option value="كاش" {{ old('payment_method') == 'كاش' ? 'selected' : '' }}>نقداً</option>
                    <option value="بطاقة" {{ old('payment_method') == 'بطاقة' ? 'selected' : '' }}>بطاقة</option>
                    <option value="تحويل بنكي" {{ old('payment_method') == 'تحويل بنكي' ? 'selected' : '' }}>تحويل بنكي</option>
                    <option value="اخرى" {{ old('payment_method') == 'اخرى' ? 'selected' : '' }}>أخرى</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="payment_status" class="form-label">حالة الدفع</label>
                <select name="payment_status" id="payment_status" class="form-select" required>
                    <option value="مدفوع" {{ old('payment_status') == 'مدفوع' ? 'selected' : '' }}>مدفوع</option>
                    <option value="قيد الانتظار" {{ old('payment_status') == 'قيد الانتظار' ? 'selected' : '' }}>قيد الانتظار</option>
                    <option value="مدفوع جزئي" {{ old('payment_status') == 'مدفوع جزئي' ? 'selected' : '' }}>مدفوع جزئي</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="notes" class="form-label">ملاحظات</label>
                <textarea name="notes" id="notes" class="form-control">{{ old('notes') }}</textarea>
            </div>

            <button type="submit" class="btn btn-success">حفظ الفاتورة</button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let itemsContainer = document.getElementById('items-container');
            let addItemBtn = document.getElementById('add-item-btn');

            function updateUnitPrice(select) {
                let selectedOption = select.options[select.selectedIndex];
                let price = selectedOption.dataset.price || 0;
                let unitPriceInput = select.closest('.item-row').querySelector('.unit-price');
                unitPriceInput.value = price;
            }

            itemsContainer.addEventListener('change', function (e) {
                if (e.target.classList.contains('variant-select')) {
                    updateUnitPrice(e.target);
                }
            });

            addItemBtn.addEventListener('click', function () {
                let index = itemsContainer.querySelectorAll('.item-row').length;
                let newRow = document.createElement('div');
                newRow.classList.add('item-row', 'row', 'g-3', 'align-items-center', 'mb-2');
                newRow.innerHTML = `
                    <div class="col-md-5">
                        <label class="form-label">اختيار المنتج (المتغير)</label>
                        <select name="items[${index}][variant_id]" class="form-select variant-select" required>
                            <option value="">اختر المنتج</option>
                            @foreach($variants as $variant)
                                <option value="{{ $variant->id }}" data-product-id="{{ $variant->product_id }}" data-price="{{ $variant->price_override ?? $variant->product->price ?? 0 }}">
                                    {{ $variant->product->name }} - {{ $variant->color }} - {{ $variant->size }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">الكمية</label>
                        <input type="number" name="items[${index}][quantity]" class="form-control" min="1" value="1" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">سعر الوحدة</label>
                        <input type="number" step="0.01" name="items[${index}][unit_price]" class="form-control unit-price" readonly required>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-danger btn-remove-item mt-4">حذف</button>
                    </div>
                `;
                itemsContainer.appendChild(newRow);
            });

            itemsContainer.addEventListener('click', function (e) {
                if (e.target.classList.contains('btn-remove-item')) {
                    let rows = itemsContainer.querySelectorAll('.item-row');
                    if (rows.length > 1) {
                        e.target.closest('.item-row').remove();
                    } else {
                        alert('يجب وجود عنصر واحد على الأقل.');
                    }
                }
            });

            let firstSelect = itemsContainer.querySelector('.variant-select');
            if (firstSelect) updateUnitPrice(firstSelect);
        });
    </script>
</x-app-layout>
