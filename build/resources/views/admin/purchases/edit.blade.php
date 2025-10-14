<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">تعديل فاتورة شراء: {{ $purchaseOrder->id }}</h2>
    </x-slot>

    <div class="container mt-4">

                @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
     <form method="POST" action="{{ route('admin.purchases.update', ['purchaseOrder' => $purchaseOrder->id]) }}">

            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="supplier">المورد</label>
                <select name="supplier_id" class="form-control" required>
                    <option value="">-- اختر المورد --</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" {{ old('supplier_id', $purchaseOrder->supplier_id) == $supplier->id ? 'selected' : '' }}>
                            {{ $supplier->name }}
                        </option>
                    @endforeach
                </select>
            </div>
           <div class="mb-3">
            <label for="order_date">تاريخ الشراء</label>
            <input type="date" name="order_date" class="form-control" value="{{ old('order_date', $purchaseOrder->order_date) }}" required>
            </div>

            <div class="mb-3">
                <label for="reference">رقم الفاتورة</label>
                <input type="text" name="reference" class="form-control" value="{{ old('reference', $purchaseOrder->reference) }}" required>
            </div>

            <div class="mb-3">
                <label for="notes">ملاحظات</label>
                <input type="text" name="notes" class="form-control" value="{{ old('notes', $purchaseOrder->notes) }}" required>
            </div>

            <div class="mb-3">
                <label>حالة الفاتورة</label>
                <select name="status" class="form-control" required>
                    <option value="draft" {{ old('status', $purchaseOrder->status) == 'draft' ? 'selected' : '' }}>مؤرشفة</option>
                    <option value="received" {{ old('status', $purchaseOrder->status) == 'received' ? 'selected' : '' }}>مستلمة</option>
                    <option value="canceled" {{ old('status', $purchaseOrder->status) == 'canceled' ? 'selected' : '' }}>ملغية</option>
                </select>
            </div>

            <hr>

            <h5>تفاصيل الأصناف</h5>
            <table class="table table-bordered" id="items-table">
                <thead>
                    <tr>
                        <th>المنتج</th>
                        <th>اللون/المقاس</th>
                        <th>الكمية</th>
                        <th>السعر</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(old('items', $purchaseOrder->items) as $index => $item)
                    <tr class="item-row">
                        <td>
                            <select name="items[{{ $index }}][product_id]" class="form-control product-select" required>
                                <option value="">-- اختر --</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}"
                                        {{ (is_array($item) ? ($item['product_id'] ?? '') : $item->product_id) == $product->id ? 'selected' : '' }}>
                                        {{ $product->name }} ({{ $product->sku }})
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <select name="items[{{ $index }}][variant_id]" class="form-control variant-select" required>
                                <option value="">-- اختر اللون/المقاس --</option>
                                {{-- سنملأها بالـ JS --}}
                            </select>
                        </td>
                        <td>
                            <input type="number" name="items[{{ $index }}][quantity]" class="form-control" required min="1" 
                                value="{{ is_array($item) ? ($item['quantity'] ?? '') : $item->quantity }}">
                        </td>
                        <td>
                            <input type="number" name="items[{{ $index }}][cost_price]" class="form-control" required min="0" step="0.01"
                                value="{{ is_array($item) ? ($item['cost_price'] ?? '') : $item->cost_price }}">
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger btn-sm remove-row">×</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <button type="button" id="add-item" class="btn btn-secondary btn-sm">إضافة صف</button>
            <button type="submit" class="btn btn-primary float-end">تحديث الفاتورة</button>
        </form>
    </div>

    <script>
        let rowIndex = {{ count(old('items', $purchaseOrder->items)) }};

        function fetchVariants(selectProduct, selectVariant, selectedVariantId = null) {
            const productId = selectProduct.value;
            selectVariant.innerHTML = '<option value="">-- جاري التحميل --</option>';
            if (!productId) {
                selectVariant.innerHTML = '<option value="">-- اختر اللون/المقاس --</option>';
                return;
            }
              fetch(`/admin/products/${productId}/variants`)
                .then(res => res.json())
                .then(data => {
                    selectVariant.innerHTML = '<option value="">-- اختر اللون/المقاس --</option>';
                    data.forEach(variant => {
                        const text = [variant.color, variant.size].filter(Boolean).join(' / ');
                        const option = new Option(text, variant.id);
                        if (selectedVariantId && selectedVariantId === variant.id) {
                            option.selected = true;
                        }
                        selectVariant.add(option);
                    });
                });
        }

        document.querySelectorAll('.item-row').forEach(row => {
            const selectProduct = row.querySelector('.product-select');
            const selectVariant = row.querySelector('.variant-select');
            const selectedVariantId = selectVariant.getAttribute('data-selected-variant-id');

            fetchVariants(selectProduct, selectVariant, selectedVariantId);
        });

        document.getElementById('add-item').addEventListener('click', function () {
            const table = document.querySelector('#items-table tbody');
            const newRow = document.querySelector('.item-row').cloneNode(true);

            newRow.querySelectorAll('input, select').forEach(input => {
                const name = input.getAttribute('name');
                if (name) {
                    const newName = name.replace(/\[\d+\]/, `[${rowIndex}]`);
                    input.setAttribute('name', newName);
                    input.value = '';
                }
                if(input.classList.contains('variant-select')){
                    input.innerHTML = '<option value="">-- اختر اللون/المقاس --</option>';
                }
            });

            table.appendChild(newRow);
            rowIndex++;
        });

        document.addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-row')) {
                const rows = document.querySelectorAll('.item-row');
                if (rows.length > 1) {
                    e.target.closest('tr').remove();
                }
            }
        });

        document.addEventListener('change', function (e) {
            if (e.target.classList.contains('product-select')) {
                const row = e.target.closest('tr');
                const variantSelect = row.querySelector('.variant-select');
                fetchVariants(e.target, variantSelect);
            }
        });
    </script>
</x-app-layout>
