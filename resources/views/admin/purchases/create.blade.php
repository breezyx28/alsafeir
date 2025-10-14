<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">إضافة فاتورة شراء</h2>
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

        <form method="POST" action="{{ route('admin.purchases.store') }}">
            @csrf

            <div class="mb-3">
                <label for="supplier">المورد</label>
                <select name="supplier_id" class="form-control" required>
                    <option value="">-- اختر المورد --</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="supplier">تاريخ الشراء</label>
                <input type="date" name="order_date" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="supplier"> رقم الفاتورة</label>
                <input type="text" name="reference" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="supplier"> ملاحظات </label>
                <input type="text" name="notes" class="form-control" required>
            </div>
            
            <div class="mb-3">
                <label>حالة الفاتورة</label>
                <select name="status" class="form-control" required>
                    <option value="draft">مؤرشفة </option>
                    <option value="received">مستلمة</option>
                    <option value="canceled">ملغية</option>
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
                    <tr class="item-row">
                        <td>
                            <select name="items[0][product_id]" class="form-control product-select" required>
                                <option value="">-- اختر --</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }} ({{ $product->sku }})</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <select name="items[0][variant_id]" class="form-control variant-select" required>
                                <option value="">-- اختر اللون/المقاس --</option>
                            </select>
                        </td>
                        <td>
                            <input type="number" name="items[0][quantity]" class="form-control" required min="1">
                        </td>
                        <td>
                            <input type="number" name="items[0][cost_price]" class="form-control" required min="0" step="0.01">
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger btn-sm remove-row">×</button>
                            
                        </td>
                    </tr>
                </tbody>
            </table>

            <button type="button" id="add-item" class="btn btn-secondary btn-sm">إضافة صف</button>
            <button type="submit" class="btn btn-success float-end">حفظ الفاتورة</button>
        </form>
    </div>

    <script>
        let rowIndex = 1;

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
                const productId = e.target.value;
                const variantSelect = row.querySelector('.variant-select');

                if (!productId) {
                    variantSelect.innerHTML = '<option value="">-- اختر اللون/المقاس --</option>';
                    return;
                }

                fetch(`/admin/products/${productId}/variants`)
                    .then(res => res.json())
                    .then(data => {
                        variantSelect.innerHTML = '<option value="">-- اختر اللون/المقاس --</option>';
                        data.forEach(variant => {
                            const text = [variant.color, variant.size].filter(Boolean).join(' / ');
                            const option = new Option(text, variant.id);
                            variantSelect.add(option);
                        });
                    });
            }
        });

    </script>
</x-app-layout>
