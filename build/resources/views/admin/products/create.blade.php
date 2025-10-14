<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            إضافة منتج جديد
        </h2>
    </x-slot>

    <div class="container mt-4">
        <form method="POST" action="{{ route('admin.products.store') }}">
            @csrf

            {{-- بيانات المنتج الأساسية --}}
            <div class="mb-3">
                <label>اسم المنتج</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>SKU</label>
                <input type="text" name="sku" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>النوع</label>
                <select name="type" class="form-control" required>
                    <option value="ready">منتج جاهز</option>
                    <option value="raw_material">مادة خام (قماش)</option>
                </select>
            </div>

            <div class="mb-3">
                <label>التصنيف</label>
                <select name="category_id" class="form-control" required>
                    <option value="">-- اختر --</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>سعر البيع</label>
                <input type="number" step="0.01" name="base_price" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>سعر التكلفة</label>
                <input type="number" step="0.01" name="cost_price" class="form-control">
            </div>

            <div class="mb-3">
                <label>وحدة البيع</label>
                <select name="unit" class="form-control" required>
                    <option value="piece">قطعة</option>
                    <option value="meter">متر</option>
                </select>
            </div>

            <div class="mb-3">
                <label>الحالة</label>
                <select name="status" class="form-control" required>
                    <option value="active">نشط</option>
                    <option value="inactive">غير نشط</option>
                </select>
            </div>

            {{-- متغيرات المنتج --}}
            <hr>
            <h5>المتغيرات (ألوان + مقاسات)</h5>

            <div id="variant-container">
                <div class="variant-row row g-2 mb-2">
                    <div class="col-md-2">
                        <input type="text" name="variants[0][color]" class="form-control" placeholder="اللون">
                    </div>
                    <div class="col-md-2">
                        <input type="text" name="variants[0][size]" class="form-control" placeholder="المقاس">
                    </div>
                    <div class="col-md-2">
                        <input type="text" name="variants[0][barcode]" class="form-control" placeholder="الباركود">
                    </div>
                    <div class="col-md-2">
                        <input type="text" name="variants[0][quantity]" class="form-control" placeholder="الكمية">
                    </div>
                    <div class="col-md-2">
                        <input type="number" step="0.01" name="variants[0][price_override]" class="form-control" placeholder="سعر خاص (اختياري)">
                    </div>
                    <div class="col-md-2 d-grid">
                        <button type="button" class="btn btn-danger remove-variant">🗑 حذف</button>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <button type="button" id="add-variant" class="btn btn-primary">➕ إضافة متغير</button>
            </div>

            <button class="btn btn-success">💾 حفظ المنتج</button>
        </form>
    </div>

    <script>
        let variantIndex = 1;

        document.getElementById('add-variant').addEventListener('click', function () {
            const container = document.getElementById('variant-container');

            const html = `
                <div class="variant-row row g-2 mb-2">
                    <div class="col-md-2">
                        <input type="text" name="variants[${variantIndex}][color]" class="form-control" placeholder="اللون">
                    </div>
                    <div class="col-md-2">
                        <input type="text" name="variants[${variantIndex}][size]" class="form-control" placeholder="المقاس">
                    </div>
                    <div class="col-md-2">
                        <input type="text" name="variants[${variantIndex}][barcode]" class="form-control" placeholder="الباركود">
                    </div>
                    <div class="col-md-2">
                        <input type="text" name="variants[${variantIndex}][quantity]" class="form-control" placeholder="الكمية">
                    </div>
                    <div class="col-md-2">
                        <input type="number" step="0.01" name="variants[${variantIndex}][price_override]" class="form-control" placeholder="سعر خاص (اختياري)">
                    </div>
                    <div class="col-md-2 d-grid">
                        <button type="button" class="btn btn-danger remove-variant">🗑 حذف</button>
                    </div>
                </div>
            `;

            container.insertAdjacentHTML('beforeend', html);
            variantIndex++;
        });

        document.addEventListener('click', function (e) {
            if (e.target && e.target.classList.contains('remove-variant')) {
                e.target.closest('.variant-row').remove();
            }
        });
    </script>
</x-app-layout>
