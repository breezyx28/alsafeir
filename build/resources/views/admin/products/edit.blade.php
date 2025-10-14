<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            تعديل المنتج: {{ $product->name }}
        </h2>
    </x-slot>

    <div class="container mt-4">
        <form method="POST" action="{{ route('admin.products.update', $product) }}">
            @csrf
            @method('PUT')

            <!-- بيانات المنتج الأساسية -->
            <div class="mb-3">
                <label>اسم المنتج</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
            </div>

            <div class="mb-3">
                <label>SKU</label>
                <input type="text" name="sku" class="form-control" value="{{ old('sku', $product->sku) }}" required>
            </div>

            <div class="mb-3">
                <label>النوع</label>
                <select name="type" class="form-control" required>
                    <option value="ready" {{ $product->type == 'ready' ? 'selected' : '' }}>منتج جاهز</option>
                    <option value="raw_material" {{ $product->type == 'raw_material' ? 'selected' : '' }}>مادة خام (قماش)</option>
                </select>
            </div>

            <div class="mb-3">
                <label>التصنيف</label>
                <select name="category_id" class="form-control" required>
                    <option value="">-- اختر --</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>سعر البيع</label>
                <input type="number" step="0.01" name="base_price" class="form-control" value="{{ old('base_price', $product->base_price) }}" required>
            </div>

            <div class="mb-3">
                <label>سعر التكلفة</label>
                <input type="number" step="0.01" name="cost_price" class="form-control" value="{{ old('cost_price', $product->cost_price) }}">
            </div>

            <div class="mb-3">
                <label>وحدة البيع</label>
                <select name="unit" class="form-control" required>
                    <option value="piece" {{ $product->unit == 'piece' ? 'selected' : '' }}>قطعة</option>
                    <option value="meter" {{ $product->unit == 'meter' ? 'selected' : '' }}>متر</option>
                </select>
            </div>

            <div class="mb-3">
                <label>الحالة</label>
                <select name="status" class="form-control" required>
                    <option value="active" {{ $product->status == 'active' ? 'selected' : '' }}>نشط</option>
                    <option value="inactive" {{ $product->status == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                </select>
            </div>

            <!-- إدارة المتغيرات -->
            <h5>المتغيرات (الألوان والمقاسات)</h5>

            <table class="table table-bordered" id="variants-table">
                <thead>
                    <tr>
                        <th>اللون</th>
                        <th>المقاس</th>
                        <th>الباركود</th>
                        <th>الكمية</th>
                        <th>سعر خاص (اختياري)</th>
                        <th>إجراء</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($product->variants as $i => $variant)
                    <tr>
                        <td>
                            <input type="text" name="variants[{{ $i }}][color]" class="form-control" value="{{ old("variants.$i.color", $variant->color) }}">
                        </td>
                        <td>
                            <input type="text" name="variants[{{ $i }}][size]" class="form-control" value="{{ old("variants.$i.size", $variant->size) }}">
                        </td>
                        <td>
                            <input type="text" name="variants[{{ $i }}][barcode]" class="form-control" value="{{ old("variants.$i.barcode", $variant->barcode) }}">
                        </td>
                        <td>
                            <input type="text" name="variants[{{ $i }}][quantity]" class="form-control" value="{{ old("variants.$i.quantity", $variant->quantity) }}">
                        </td>
                        <td>
                            <input type="number" step="0.01" name="variants[{{ $i }}][price_override]" class="form-control" value="{{ old("variants.$i.price_override", $variant->price_override) }}">
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger btn-sm remove-variant">حذف</button>
                            <input type="hidden" name="variants[{{ $i }}][id]" value="{{ $variant->id }}">
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <button type="button" id="add-variant" class="btn btn-secondary mb-3">+ إضافة متغير جديد</button>

            <button class="btn btn-primary">تحديث المنتج</button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            let variantIndex = {{ $product->variants->count() }};

            document.getElementById('add-variant').addEventListener('click', () => {
                const tbody = document.querySelector('#variants-table tbody');
                const tr = document.createElement('tr');

                tr.innerHTML = `
                    <td><input type="text" name="variants[${variantIndex}][color]" class="form-control"></td>
                    <td><input type="text" name="variants[${variantIndex}][size]" class="form-control"></td>
                    <td><input type="text" name="variants[${variantIndex}][barcode]" class="form-control"></td>
                    <td><input type="text" name="variants[${variantIndex}][quantity]" class="form-control"></td>
                    <td><input type="number" step="0.01" name="variants[${variantIndex}][price_override]" class="form-control"></td>
                    <td><button type="button" class="btn btn-danger btn-sm remove-variant">حذف</button></td>
                `;

                tbody.appendChild(tr);
                variantIndex++;
            });

            document.querySelector('#variants-table').addEventListener('click', (e) => {
                if(e.target.classList.contains('remove-variant')) {
                    e.target.closest('tr').remove();
                }
            });
        });
    </script>
</x-app-layout>
