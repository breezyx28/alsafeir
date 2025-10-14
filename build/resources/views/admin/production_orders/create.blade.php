<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">إنشاء أمر تصنيع</h2>
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

        <form method="POST" action="{{ route('admin.production_orders.store') }}">
            @csrf

            <div class="mb-3">
                <label>المرجع</label>
                <input type="text" name="reference" class="form-control">
            </div>

            <div class="mb-3">
                <label>تاريخ التصنيع</label>
                <input type="date" name="production_date" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>ملاحظات</label>
                <input type="text" name="notes" class="form-control">
            </div>

            <div class="mb-3">
                <label>الفرع الوجهة بعد التصنيع</label>
                <select name="branch_id" class="form-control" required>
                    <option value="">-- اختر الفرع --</option>
                    @foreach ($branches as $branch)
                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                    @endforeach
                </select>
            </div>

            <hr>
            <h5>المنتجات النهائية</h5>
            <table class="table table-bordered" id="final-products-table">
                <thead>
                    <tr>
                        <th>المنتج</th>
                        <th>اللون/المقاس</th>
                        <th>الكمية</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="final-product-row">
                        <td>
                            <select name="final_products[0][product_id]" class="form-control product-select" required>
                                <option value="">-- اختر --</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }} ({{ $product->sku }})</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <select name="final_products[0][variant_id]" class="form-control variant-select">
                                <option value="">-- اختر اللون/المقاس --</option>
                            </select>
                        </td>
                        <td>
                            <input type="number" name="final_products[0][quantity]" class="form-control" required min="1">
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger btn-sm remove-row">×</button>
                        </td>
                    </tr>
                </tbody>
            </table>
            <button type="button" id="add-final-product" class="btn btn-secondary btn-sm">إضافة منتج نهائي</button>

            <hr>
            <h5>المواد الخام المستخدمة</h5>
            <table class="table table-bordered" id="materials-table">
                <thead>
                    <tr>
                        <th>المادة الخام</th>
                        <th>اللون/المقاس</th>
                        <th>الكمية</th>
                        <th>الوحدة</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="material-row">
                        <td>
                            <select name="materials[0][product_id]" class="form-control raw-material-select" required>
                                <option value="">-- اختر --</option>
                                @foreach ($rawMaterials as $material)
                                    <option value="{{ $material->id }}">{{ $material->name }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <select name="materials[0][variant_id]" class="form-control variant-select">
                                <option value="">-- اختر اللون/المقاس --</option>
                            </select>
                        </td>
                        <td>
                            <input type="number" name="materials[0][quantity]" class="form-control" required step="0.01" min="0.01">
                        </td>
                        <td>
                            <input type="text" name="materials[0][unit]" class="form-control" required>
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger btn-sm remove-row">×</button>
                        </td>
                    </tr>
                </tbody>
            </table>
            <button type="button" id="add-material" class="btn btn-secondary btn-sm">إضافة مادة خام</button>

            <hr>
            <button type="submit" class="btn btn-success float-end">حفظ أمر التصنيع</button>
        </form>
    </div>

    <script>
        let finalIndex = 1;
        let materialIndex = 1;

        // نسخ صف
        function cloneRow(tableSelector, rowClass, namePrefix, indexVar) {
            const table = document.querySelector(tableSelector + ' tbody');
            const newRow = document.querySelector(rowClass).cloneNode(true);

            newRow.querySelectorAll('input, select').forEach(input => {
                const name = input.getAttribute('name');
                if (name) {
                    const newName = name.replace(/\[\d+\]/, `[${indexVar}]`);
                    input.setAttribute('name', newName);
                    input.value = '';
                }
            });

            table.appendChild(newRow);
        }

        document.getElementById('add-final-product').addEventListener('click', function () {
            cloneRow('#final-products-table', '.final-product-row', 'final_products', finalIndex++);
        });

        document.getElementById('add-material').addEventListener('click', function () {
            cloneRow('#materials-table', '.material-row', 'materials', materialIndex++);
        });

        document.addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-row')) {
                const row = e.target.closest('tr');
                const table = row.closest('tbody');
                if (table.children.length > 1) row.remove();
            }
        });

        // تحميل المتغيرات بناءً على المنتج
        document.addEventListener('change', function (e) {
            if (e.target.classList.contains('product-select') || e.target.classList.contains('raw-material-select')) {
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
