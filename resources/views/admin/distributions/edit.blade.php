<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">تعديل توزيع المنتجات</h2>
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

        <form method="POST" action="{{ route('admin.distributions.update', $distribution->id) }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label>اختر الفرع</label>
                <select name="branch_id" class="form-control" required>
                    @foreach ($branches as $branch)
                        <option value="{{ $branch->id }}" {{ $distribution->branch_id == $branch->id ? 'selected' : '' }}>
                            {{ $branch->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>اختر فاتورة شراء</label>
                <select name="purchase_order_id" class="form-control" required>
                    @foreach ($purchaseOrders as $order)
                        <option value="{{ $order->id }}" {{ $distribution->purchase_order_id == $order->id ? 'selected' : '' }}>
                            رقم: {{ $order->reference }} | {{ $order->order_date }}
                        </option>
                    @endforeach
                </select>
            </div>

            <hr>

            <h5>المنتجات</h5>
            <table class="table table-bordered" id="items-table">
                <thead>
                    <tr>
                        <th>المنتج</th>
                        <th>اللون/المقاس</th>
                        <th>الكمية</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="item-row">
                        <td>
                            <select name="items[0][product_id]" class="form-control product-select" required>
                                <option value="">-- اختر --</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}"
                                        {{ $distribution->product_id == $product->id ? 'selected' : '' }}>
                                        {{ $product->name }} ({{ $product->sku }})
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <select name="items[0][variant_id]" class="form-control variant-select">
                                <option value="">-- اختر --</option>
                                @if ($distribution->variant)
                                    <option value="{{ $distribution->variant_id }}" selected>
                                        {{ $distribution->variant->color }} / {{ $distribution->variant->size }}
                                    </option>
                                @endif
                            </select>
                        </td>
                        <td>
                            <input type="number" name="items[0][quantity]" class="form-control"
                                   value="{{ $distribution->quantity }}" min="1" required>
                        </td>
                        <td>
                            {{-- لا تسمح بالحذف في التعديل الافتراضي لأنه سجل واحد --}}
                        </td>
                    </tr>
                </tbody>
            </table>

            <button type="submit" class="btn btn-primary float-end">تحديث التوزيع</button>
        </form>
    </div>

    <script>
        document.addEventListener('change', function (e) {
            if (e.target.classList.contains('product-select')) {
                const row = e.target.closest('tr');
                const productId = e.target.value;
                const variantSelect = row.querySelector('.variant-select');

                if (!productId) {
                    variantSelect.innerHTML = '<option value="">-- اختر --</option>';
                    return;
                }

                fetch(`/admin/products/${productId}/variants`)
                    .then(res => res.json())
                    .then(data => {
                        variantSelect.innerHTML = '<option value="">-- اختر --</option>';
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
